<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\SettlementCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class SettlementController extends Controller
{
    public function __construct(private SettlementCalculator $settlementCalculator)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = (int) Auth::id();
        $colocation = $this->getActiveColocationForUser($userId);

        if (!$colocation) {
            return view('balances.index', [
                'totalPaid' => 0,
                'individualShare' => 0,
                'currentBalance' => 0,
                'memberBalances' => collect(),
                'settlementSuggestions' => collect(),
                'paidSettlements' => collect(),
                'noColocation' => true,
                'currentUserId' => $userId,
            ]);
        }

        $summary = $this->buildSummaryWithPaidSettlements($colocation);
        $currentUserBalance = $this->extractCurrentUserBalance($summary['balances'], $userId);

        return view('balances.index', [
            'totalPaid' => $summary['total'],
            'individualShare' => $summary['share'],
            'currentBalance' => $currentUserBalance,
            'memberBalances' => $summary['balances'],
            'settlementSuggestions' => $summary['settlements'],
            'paidSettlements' => $summary['paidSettlements'],
            'noColocation' => false,
            'currentUserId' => $userId,
        ]);
    }

    public function markAsPaid(Request $request)
    {
        $userId = (int) Auth::id();
        $authUser = $request->user();
        $isGlobalAdmin = (bool) ($authUser?->isGlobalAdmin());

        $data = $request->validate([
            'from_user_id' => ['required', 'integer'],
            'to_user_id' => ['required', 'integer', 'different:from_user_id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $fromUserId = (int) $data['from_user_id'];
        $toUserId = (int) $data['to_user_id'];
        $amount = round((float) $data['amount'], 2);

        if (! $isGlobalAdmin && $fromUserId !== $userId) {
            throw ValidationException::withMessages([
                'amount' => 'Only the debtor can mark this settlement as paid.',
            ]);
        }

        $colocation = $isGlobalAdmin
            ? $this->findActiveColocationForSettlementPair($fromUserId, $toUserId)
            : $this->getActiveColocationForUser($userId);

        if (! $colocation) {
            return redirect()->route('balances.index')
                ->withErrors('No active colocation found for this settlement.');
        }

        $memberIds = $this->getActiveMemberIds($colocation);

        if (! in_array($fromUserId, $memberIds, true) || ! in_array($toUserId, $memberIds, true)) {
            throw ValidationException::withMessages([
                'amount' => 'This settlement is not valid for your active colocation.',
            ]);
        }

        $summary = $this->buildSummaryWithPaidSettlements($colocation);
        $matchingSuggestion = collect($summary['settlements'])->first(function (array $item) use ($fromUserId, $toUserId) {
            return (int) $item['from']->id === $fromUserId && (int) $item['to']->id === $toUserId;
        });

        if (! $matchingSuggestion) {
            throw ValidationException::withMessages([
                'amount' => 'No pending settlement found for this pair.',
            ]);
        }

        $maxAmount = round((float) $matchingSuggestion['amount'], 2);
        if ($amount > $maxAmount + 0.00001) {
            throw ValidationException::withMessages([
                'amount' => 'Amount exceeds the pending settlement value (' . number_format($maxAmount, 2) . ' MAD).',
            ]);
        }

        $senderColumn = $this->settlementSenderColumn();
        $receiverColumn = $this->settlementReceiverColumn();

        DB::table('settlements')->insert([
            $senderColumn => $fromUserId,
            $receiverColumn => $toUserId,
            'expense_id' => null,
            'amount' => $amount,
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('balances.index')
            ->with('success', 'Settlement marked as paid.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    private function getActiveColocationForUser(int $userId): ?Colocation
    {
        return Colocation::where('status', 'active')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->whereNull('colocation_user.left_at');
            })
            ->first();
    }

    private function buildSummaryWithPaidSettlements(Colocation $colocation): array
    {
        $summary = $this->settlementCalculator->recalculateForColocation($colocation);
        $memberIds = $this->getActiveMemberIds($colocation);

        $paidSettlements = $this->getPaidSettlements($memberIds);

        if (empty($memberIds)) {
            $summary['paidSettlements'] = collect();
            return $summary;
        }

        $balanceMap = [];
        foreach ($summary['balances'] as $row) {
            $id = (int) $row['user']->id;
            $balanceMap[$id] = [
                'user' => $row['user'],
                'paid' => (float) $row['paid'],
                'share' => (float) $row['share'],
                'balance' => (float) $row['balance'],
            ];
        }

        foreach ($paidSettlements as $settlement) {
            $senderId = (int) $settlement->sender_id;
            $receiverId = (int) $settlement->receiver_id;
            $amount = (float) $settlement->amount;

            if (! isset($balanceMap[$senderId], $balanceMap[$receiverId])) {
                continue;
            }

            $balanceMap[$senderId]['balance'] += $amount;
            $balanceMap[$receiverId]['balance'] -= $amount;
        }

        $adjustedBalances = collect(array_values($balanceMap));
        $summary['balances'] = $adjustedBalances;
        $summary['settlements'] = $this->settlementCalculator->buildSettlementSuggestions($adjustedBalances);
        $summary['paidSettlements'] = $paidSettlements;

        return $summary;
    }

    private function getPaidSettlements(array $memberIds): Collection
    {
        if (empty($memberIds) || ! Schema::hasTable('settlements')) {
            return collect();
        }

        $senderColumn = $this->settlementSenderColumn();
        $receiverColumn = $this->settlementReceiverColumn();

        return DB::table('settlements as s')
            ->join('users as sender', 'sender.id', '=', 's.' . $senderColumn)
            ->join('users as receiver', 'receiver.id', '=', 's.' . $receiverColumn)
            ->whereNotNull('s.paid_at')
            ->whereIn('s.' . $senderColumn, $memberIds)
            ->whereIn('s.' . $receiverColumn, $memberIds)
            ->orderByDesc('s.paid_at')
            ->select([
                's.id',
                's.amount',
                's.paid_at',
                'sender.name as sender_name',
                'receiver.name as receiver_name',
                DB::raw('s.' . $senderColumn . ' as sender_id'),
                DB::raw('s.' . $receiverColumn . ' as receiver_id'),
            ])
            ->get();
    }

    private function extractCurrentUserBalance(Collection $balances, int $userId): float
    {
        foreach ($balances as $balance) {
            if ((int) $balance['user']->id === $userId) {
                return (float) $balance['balance'];
            }
        }

        return 0.0;
    }

    private function settlementSenderColumn(): string
    {
        return Schema::hasColumn('settlements', 'sender_id') ? 'sender_id' : 'payer_id';
    }

    private function settlementReceiverColumn(): string
    {
        return Schema::hasColumn('settlements', 'received_id') ? 'received_id' : 'receiver_id';
    }

    private function getActiveMemberIds(Colocation $colocation): array
    {
        return $colocation->users()
            ->whereNull('colocation_user.left_at')
            ->pluck('users.id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function findActiveColocationForSettlementPair(int $fromUserId, int $toUserId): ?Colocation
    {
        return Colocation::query()
            ->where('status', 'active')
            ->whereHas('users', function ($query) use ($fromUserId) {
                $query->where('users.id', $fromUserId)
                    ->whereNull('colocation_user.left_at');
            })
            ->whereHas('users', function ($query) use ($toUserId) {
                $query->where('users.id', $toUserId)
                    ->whereNull('colocation_user.left_at');
            })
            ->first();
    }
}
