<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.attempt');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::view('/blocked', 'auth.blocked')
    ->middleware('auth')
    ->name('blocked');

Route::middleware(['auth', 'not.banned'])->group(function () {
    Route::redirect('/dashboard', '/dashboard/user')->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');

    Route::get('/dashboard/user', [UserController::class, 'dashboard'])->name('dashboard.user');
    Route::view('/dashboard/owner', 'dashboard.owner')->name('dashboard.owner');
    Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])
        ->middleware('global.admin')
        ->name('dashboard.admin');

    Route::prefix('colocations')->name('colocations.')->group(function () {
        Route::get('/', [ColocationController::class, 'index'])->name('index');
        Route::get('/create', [ColocationController::class, 'create'])->name('create');
        Route::post('/', [ColocationController::class, 'store'])->name('store');
        Route::get('/{colocation}', [ColocationController::class, 'show'])->name('show');
        Route::get('/{colocation}/manage', [ColocationController::class, 'manage'])->name('manage');
        Route::post('/{colocation}/leave', [ColocationController::class, 'leave'])->name('leave');
        Route::post('/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('cancel');
        Route::post('/{colocation}/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/{colocation}/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{colocation}/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('/balances', [SettlementController::class, 'index'])->name('balances.index');
    Route::post('/balances/mark-paid', [SettlementController::class, 'markAsPaid'])->name('balances.markPaid');
    Route::get('/admin', [AdminController::class, 'index'])
        ->middleware('global.admin')
        ->name('admin.index');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'banUser'])
        ->middleware('global.admin')
        ->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [AdminController::class, 'unbanUser'])
        ->middleware('global.admin')
        ->name('admin.users.unban');

    Route::post('/invitations/{colocation}/send', [InvitationController::class, 'invite'])->name('invitations.send');

    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::post('/{token}/accept', [InvitationController::class, 'acceptInvitation'])->name('accept');
        Route::post('/{token}/refuse', [InvitationController::class, 'refuseInvitation'])->name('refuse');
    });
});

Route::get('/invitations/{token}', [InvitationController::class, 'showInvitation'])->name('invitations.show');
Route::view('/invitations-error', 'invitations.error')->name('invitations.error');
