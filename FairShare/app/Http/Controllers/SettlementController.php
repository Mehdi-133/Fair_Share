<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\SettlementCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
                'memberBalances' => [],
                'settlementSuggestions' => [],
                'noColocation' => true,
                'currentUserId' => $userId,
            ]);
        }

        $summary = $this->settlementCalculator->recalculateForColocation($colocation);

        $currentUserBalance = 0.0;
        foreach ($summary['balances'] as $balance) {
            if ((int) $balance['user']->id === $userId) {
                $currentUserBalance = (float) $balance['balance'];
                break;
            }
        }

        return view('balances.index', [
            'totalPaid' => $summary['total'],
            'individualShare' => $summary['share'],
            'currentBalance' => $currentUserBalance,
            'memberBalances' => $summary['balances'],
            'settlementSuggestions' => $summary['settlements'],
            'noColocation' => false,
            'currentUserId' => $userId,
        ]);
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
}
