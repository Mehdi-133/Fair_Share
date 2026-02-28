<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\SettlementCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;



class ColocationController extends Controller
{
    public function __construct(private SettlementCalculator $settlementCalculator)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $colocations = Colocation::with('users')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->latest()
            ->get();

        $canCreateColocation = !$this->userHasActiveColocation(Auth::id());

        return view('colocations.index', compact('colocations', 'canCreateColocation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->userHasActiveColocation(Auth::id())) {
            return redirect()
                ->route('colocations.index')
                ->withErrors('You already have an active colocation. Cancel it before creating a new one.');
        }

        return view('colocations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->userHasActiveColocation(Auth::id())) {
            return redirect()
                ->route('colocations.index')
                ->withErrors('You already have an active colocation. Cancel it before creating a new one.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string|max:100',

        ]);

        $colocation = Colocation::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => 'active',
            'invite_code' => Str::random(10)
        ]);

        $colocation->users()->attach(Auth::id(), [
            'role' => 'owner',
            'joined_at' => now(),
            'left_at' => null
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation)
    {
        $userId = (int) Auth::id();
        $membership = $colocation->users()
            ->where('users.id', $userId)
            ->first();

        if (! $membership) {
            abort(403);
        }

        $isFormerMember = ! is_null($membership->pivot->left_at);

        $colocation->load([
            'users.reputations',
            'expenses.category',
            'expenses.payer',
            'categories'
        ]);

        $settlementSummary = $this->settlementCalculator->recalculateForColocation($colocation);

        return view('colocations.show', compact('colocation', 'settlementSummary', 'isFormerMember'));
    }

    public function manage(Colocation $colocation)
    {
        if ($colocation->status === 'cancelled') {
            return redirect()
                ->route('colocations.show', $colocation)
                ->withErrors('This colocation is cancelled and read-only.');
        }

        if (!$colocation->isOwner(Auth::id())) {
            abort(403);
        }

        $colocation->load('users.reputations', 'invitations', 'categories');

        return view('colocations.manage', compact('colocation'));
    }


    public function leave(Colocation $colocation)
    {
        if ($colocation->status === 'cancelled') {
            return redirect()
                ->route('colocations.show', $colocation)
                ->withErrors('This colocation is cancelled and read-only.');
        }

        $userId = (int) Auth::id();

        if ($colocation->isOwner($userId)) {
            return back()->withErrors('Owner cannot leave.');
        }

        $activeMembership = $colocation->users()
            ->where('users.id', $userId)
            ->whereNull('colocation_user.left_at')
            ->exists();

        if (! $activeMembership) {
            return back()->withErrors('You are no longer an active member of this colocation.');
        }

        $leftWithDebt = $this->transferLeavingMemberDebtToOwner($colocation, $userId);
        ReputationController::recordLeaveOutcome($userId, $leftWithDebt, (int) $colocation->id);

        $colocation->users()->updateExistingPivot($userId, [
            'left_at' => now()
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('message', 'You left the colocation. Reputation updated (' . ($leftWithDebt ? '-1' : '+1') . ').');
    }

    public function cancel(Colocation $colocation)
    {
        if ($colocation->status === 'cancelled') {
            return redirect()
                ->route('colocations.show', $colocation)
                ->withErrors('This colocation is already cancelled.');
        }

        if (!$colocation->isOwner(Auth::id())) {
            abort(403);
        }

        $activeNonOwnerCount = $colocation->users()
            ->wherePivotNull('left_at')
            ->wherePivot('role', '!=', 'owner')
            ->count();

        $summary = $this->settlementCalculator->recalculateForColocation($colocation);
        $hasOutstandingDebt = !empty($summary['settlements']);

        // Cancel is allowed if:
        // 1) no other active members remain, OR
        // 2) there is no outstanding debt.
        if ($activeNonOwnerCount > 0 && $hasOutstandingDebt) {
            return back()->withErrors(
                'Cannot cancel yet. Remove all other active members or settle all debts first.'
            );
        }

        $colocation->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('dashboard');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function userHasActiveColocation(int $userId): bool
    {
        return Colocation::where('status', 'active')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->whereNull('colocation_user.left_at');
            })
            ->exists();
    }

    private function userBelongsToColocation(int $userId, Colocation $colocation): bool
    {
        return $colocation->users()
            ->where('users.id', $userId)
            ->whereNull('colocation_user.left_at')
            ->exists();
    }

    private function transferLeavingMemberDebtToOwner(Colocation $colocation, int $leavingUserId): bool
    {
        $summary = $this->settlementCalculator->recalculateForColocation($colocation);

        $balance = collect($summary['balances'])
            ->first(fn (array $row) => (int) $row['user']->id === $leavingUserId);

        if (! $balance) {
            return false;
        }

        $owedAmount = abs((float) $balance['balance']);
        if ((float) $balance['balance'] >= 0 || $owedAmount <= 0.00001) {
            return false;
        }

        $owner = $colocation->users()
            ->wherePivot('role', 'owner')
            ->wherePivotNull('left_at')
            ->first();

        if (! $owner) {
            return true;
        }

        $senderColumn = Schema::hasColumn('settlements', 'sender_id') ? 'sender_id' : 'payer_id';
        $receiverColumn = Schema::hasColumn('settlements', 'received_id') ? 'received_id' : 'receiver_id';

        DB::table('settlements')->insert([
            $senderColumn => $leavingUserId,
            $receiverColumn => (int) $owner->id,
            'expense_id' => null,
            'amount' => round($owedAmount, 2),
            'paid_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }
}
