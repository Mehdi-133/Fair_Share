<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $bannedUsers = $this->getBannedUsers();

        return view('dashboard.admin', [
            'totalUsers' => User::count(),
            'totalColocations' => Colocation::count(),
            'totalExpenses' => Expense::count(),
            'bannedUsers' => $bannedUsers,
        ]);
    }

    public function index(): View
    {
        return view('admin.index', [
            'totalUsers' => User::count(),
            'totalColocations' => Colocation::count(),
            'totalExpenses' => Expense::count(),
            'users' => User::query()
                ->latest()
                ->paginate(15),
        ]);
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
