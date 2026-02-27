<?php

namespace App\Services;

use App\Models\Colocation;
use Illuminate\Support\Collection;

class SettlementCalculator
{
    public function recalculateForColocation(Colocation $colocation): array
    {
        $colocation->load([
            'users' => function ($query) {
                $query->wherePivotNull('left_at');
            },
            'expenses',
        ]);

        $members = $colocation->users;
        $total = (float) $colocation->expenses->sum('amount');
        $membersCount = max($members->count(), 1);
        $share = $total / $membersCount;

        $balances = $members->map(function ($member) use ($colocation, $share) {
            $paid = (float) $colocation->expenses
                ->where('payer_id', $member->id)
                ->sum('amount');

            return [
                'user' => $member,
                'paid' => $paid,
                'share' => $share,
                'balance' => $paid - $share,
            ];
        })->values();

        return [
            'total' => $total,
            'share' => $share,
            'balances' => $balances,
            'settlements' => $this->buildSettlementSuggestions($balances),
        ];
    }

    public function buildSettlementSuggestions(Collection $balances): array
    {
        $creditors = [];
        $debtors = [];

        foreach ($balances as $item) {
            $balance = (float) $item['balance'];
            if ($balance > 0) {
                $creditors[] = [
                    'user' => $item['user'],
                    'remaining' => $balance,
                ];
            } elseif ($balance < 0) {
                $debtors[] = [
                    'user' => $item['user'],
                    'remaining' => abs($balance),
                ];
            }
        }

        $result = [];
        $debtorIndex = 0;
        $creditorIndex = 0;

        while ($debtorIndex < count($debtors) && $creditorIndex < count($creditors)) {
            $amount = min(
                $debtors[$debtorIndex]['remaining'],
                $creditors[$creditorIndex]['remaining']
            );

            if ($amount > 0) {
                $result[] = [
                    'from' => $debtors[$debtorIndex]['user'],
                    'to' => $creditors[$creditorIndex]['user'],
                    'amount' => $amount,
                ];
            }

            $debtors[$debtorIndex]['remaining'] -= $amount;
            $creditors[$creditorIndex]['remaining'] -= $amount;

            if ($debtors[$debtorIndex]['remaining'] <= 0.00001) {
                $debtorIndex++;
            }

            if ($creditors[$creditorIndex]['remaining'] <= 0.00001) {
                $creditorIndex++;
            }
        }

        return $result;
    }
}
