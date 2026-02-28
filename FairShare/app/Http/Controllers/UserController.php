<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\SettlementCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(SettlementCalculator $settlementCalculator): View
    {
        $userId = (int) Auth::id();

        $colocation = Colocation::query()
            ->where('status', 'active')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->whereNull('colocation_user.left_at');
            })
            ->with([
                'expenses' => function ($query) {
                    $query->with(['category', 'payer'])->latest();
                },
            ])
            ->first();

        if (! $colocation) {
            $lastColocationId = DB::table('colocation_user')
                ->where('user_id', $userId)
                ->orderByDesc('updated_at')
                ->value('colocation_id');

            if ($lastColocationId) {
                $formerColocation = Colocation::query()
                    ->with([
                        'expenses' => function ($query) {
                            $query->with(['category', 'payer'])->latest();
                        },
                    ])
                    ->find($lastColocationId);

                if ($formerColocation) {
                    return view('dashboard.user', [
                        'colocation' => $formerColocation,
                        'recentExpenses' => $formerColocation->expenses->take(7),
                        'currentBalance' => 0,
                        'paidByUser' => (float) $formerColocation->expenses->where('payer_id', $userId)->sum('amount'),
                        'individualShare' => 0,
                        'totalExpenses' => (float) $formerColocation->expenses->sum('amount'),
                        'pendingSettlements' => collect(),
                        'isFormerMemberView' => true,
                    ]);
                }
            }

            return view('dashboard.user', [
                'colocation' => null,
                'recentExpenses' => collect(),
                'currentBalance' => 0,
                'paidByUser' => 0,
                'individualShare' => 0,
                'totalExpenses' => 0,
                'pendingSettlements' => collect(),
                'isFormerMemberView' => false,
            ]);
        }

        $summary = $settlementCalculator->recalculateForColocation($colocation);

        $currentBalance = 0.0;
        foreach ($summary['balances'] as $balance) {
            if ((int) $balance['user']->id === $userId) {
                $currentBalance = (float) $balance['balance'];
                break;
            }
        }

        $pendingSettlements = collect($summary['settlements'])
            ->filter(fn (array $row) => (int) $row['from']->id === $userId)
            ->values();

        return view('dashboard.user', [
            'colocation' => $colocation,
            'recentExpenses' => $colocation->expenses->take(7),
            'currentBalance' => $currentBalance,
            'paidByUser' => (float) $colocation->expenses->where('payer_id', $userId)->sum('amount'),
            'individualShare' => (float) ($summary['share'] ?? 0),
            'totalExpenses' => (float) ($summary['total'] ?? 0),
            'pendingSettlements' => $pendingSettlements,
            'isFormerMemberView' => false,
        ]);
    }

    public function profile()
    {
        return view('users.profile');
    }

}

