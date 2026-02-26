<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::redirect('/dashboard', '/dashboard/user')->name('dashboard');

    Route::view('/dashboard/user', 'dashboard.user')->name('dashboard.user');
    Route::view('/dashboard/owner', 'dashboard.owner')->name('dashboard.owner');
    Route::view('/dashboard/admin', 'dashboard.admin')->name('dashboard.admin');

    Route::prefix('colocations')->name('colocations.')->group(function () {
        Route::get('/', [ColocationController::class, 'index'])->name('index');
        Route::get('/create', [ColocationController::class, 'create'])->name('create');
        Route::post('/', [ColocationController::class, 'store'])->name('store');
        Route::get('/{colocation}', [ColocationController::class, 'show'])->name('show');
        Route::get('/{colocation}/manage', [ColocationController::class, 'manage'])->name('manage');
        Route::post('/{colocation}/leave', [ColocationController::class, 'leave'])->name('leave');
        Route::post('/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('cancel');
    });

    Route::view('/expenses', 'expenses.index')->name('expenses.index');
    Route::view('/expenses/create', 'expenses.create')->name('expenses.create');

    Route::view('/balances', 'balances.index')->name('balances.index');
    Route::view('/admin', 'admin.index')->name('admin.index');

    Route::post('/invitations/{colocation}/send', [InvitationController::class, 'invite'])->name('invitations.send');

    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::post('/{token}/accept', [InvitationController::class, 'acceptInvitation'])->name('accept');
        Route::post('/{token}/refuse', [InvitationController::class, 'refuseInvitation'])->name('refuse');
    });
});

Route::get('/invitations/{token}', [InvitationController::class, 'showInvitation'])->name('invitations.show');
Route::view('/invitations-error', 'invitations.error')->name('invitations.error');
