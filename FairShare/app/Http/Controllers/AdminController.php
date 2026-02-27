<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = $this->buildStats();

        return view('dashboard.admin', $stats);
    }

    public function index(): View
    {
        return view('admin.index', array_merge($this->buildStats(), [
            'users' => User::query()
                ->latest()
                ->paginate(15),
        ]));
    }

    private function buildStats(): array
    {
        $totalUsers = User::count();
        $totalColocations = Colocation::count();
        $totalExpenses = Expense::count();
        $bannedUsers = $this->getBannedUsers();
        $today = now();

        $activeColocations = Colocation::query()->where('status', 'active')->count();
        $cancelledColocations = Colocation::query()->where('status', 'cancelled')->count();

        $pendingInvitations = Invitation::query()->where('status', 'pending')->count();
        $acceptedInvitations = Invitation::query()->where('status', 'accepted')->count();
        $refusedInvitations = Invitation::query()->where('status', 'refused')->count();

        $expensesThisMonth = (float) (Expense::query()
            ->whereBetween('date', [$today->copy()->startOfMonth()->toDateString(), $today->copy()->endOfMonth()->toDateString()])
            ->sum('amount'));

        $expensesLast30Days = (float) (Expense::query()
            ->whereBetween('date', [$today->copy()->subDays(30)->toDateString(), $today->toDateString()])
            ->sum('amount'));

        $avgExpenseAmount = (float) (Expense::query()->avg('amount') ?? 0);
        $biggestExpense = (float) (Expense::query()->max('amount') ?? 0);

        $activeMemberships = Schema::hasTable('colocation_user')
            ? DB::table('colocation_user')->whereNull('left_at')->count()
            : 0;

        $totalOwners = Schema::hasTable('colocation_user')
            ? DB::table('colocation_user')->where('role', 'owner')->whereNull('left_at')->count()
            : 0;

        $usersThisMonth = User::query()
            ->whereBetween('created_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
            ->count();

        $expensesThisWeek = Expense::query()
            ->whereBetween('date', [$today->copy()->startOfWeek()->toDateString(), $today->copy()->endOfWeek()->toDateString()])
            ->count();

        $topSpenders = User::query()
            ->withSum('expensePaid as total_paid', 'amount')
            ->orderByDesc('total_paid')
            ->limit(5)
            ->get();

        return [
            'totalUsers' => $totalUsers,
            'totalColocations' => $totalColocations,
            'totalExpenses' => $totalExpenses,
            'bannedUsers' => $bannedUsers,
            'activeColocations' => $activeColocations,
            'cancelledColocations' => $cancelledColocations,
            'pendingInvitations' => $pendingInvitations,
            'acceptedInvitations' => $acceptedInvitations,
            'refusedInvitations' => $refusedInvitations,
            'expensesThisMonth' => $expensesThisMonth,
            'expensesLast30Days' => $expensesLast30Days,
            'avgExpenseAmount' => $avgExpenseAmount,
            'biggestExpense' => $biggestExpense,
            'activeMemberships' => $activeMemberships,
            'totalOwners' => $totalOwners,
            'usersThisMonth' => $usersThisMonth,
            'expensesThisWeek' => $expensesThisWeek,
            'topSpenders' => $topSpenders,
        ];
    }

    private function getBannedUsers(): Collection
    {
        if (Schema::hasColumn('users', 'is_banned')) {
            return User::query()
                ->where('is_banned', true)
                ->latest()
                ->get();
        }

        if (Schema::hasColumn('users', 'is_baned')) {
            return User::query()
                ->where('is_baned', true)
                ->latest()
                ->get();
        }

        return collect();
    }
}
