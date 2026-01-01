<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    // Check if logged in
    if (Illuminate\Support\Facades\Auth::check()) {
        if (auth()->user()->role === 'admin')
            return redirect()->route('admin.dashboard');
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('users/{user}/export', [ItemController::class, 'exportUserItems'])->name('users.export_items');
    Route::resource('users', AdminUserController::class);


    // Monitoring
    Route::get('monitoring', [\App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('monitoring/{user}', [\App\Http\Controllers\Admin\MonitoringController::class, 'showUser'])->name('monitoring.user');
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
    Route::resource('items', ItemController::class);
});
