<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\SettlementCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (! $this->userBelongsToColocation((int) Auth::id(), $colocation)) {
            abort(403);
        }

        $colocation->load([
            'users',
            'expenses.category',
            'expenses.payer',
            'categories'
        ]);

        $settlementSummary = $this->settlementCalculator->recalculateForColocation($colocation);

        return view('colocations.show', compact('colocation', 'settlementSummary'));
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

        $colocation->load('users', 'invitations', 'categories');

        return view('colocations.manage', compact('colocation'));
    }


    public function leave(Colocation $colocation)
    {
        if ($colocation->status === 'cancelled') {
            return redirect()
                ->route('colocations.show', $colocation)
                ->withErrors('This colocation is cancelled and read-only.');
        }

        if ($colocation->isOwner(Auth::id())) {
            return back()->withErrors('Owner cannot leave.');
        }

        $colocation->users()->updateExistingPivot(Auth::id(), [
            'left_at' => now()
        ]);

        return redirect()->route('dashboard');
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
}
