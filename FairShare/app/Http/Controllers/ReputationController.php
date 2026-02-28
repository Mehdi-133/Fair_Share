<?php

namespace App\Http\Controllers;

use App\Models\Reputation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReputationController extends Controller
{
    public static function recordLeaveOutcome(int $userId, bool $leftWithDebt, ?int $colocationId = null): Reputation
    {
        $score = $leftWithDebt ? -1 : 1;
        $reason = $leftWithDebt
            ? 'Left colocation' . ($colocationId ? (' #' . $colocationId) : '') . ' with debt'
            : 'Left colocation' . ($colocationId ? (' #' . $colocationId) : '') . ' without debt';

        return Reputation::create([
            'user_id' => $userId,
            'score' => $score,
            'reason' => $reason,
        ]);
    }

    public static function totalForUser(int $userId): int
    {
        return (int) Reputation::query()
            ->where('user_id', $userId)
            ->sum('score');
    }

    public static function historyForUser(int $userId, int $limit = 20): Collection
    {
        return Reputation::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }


}
