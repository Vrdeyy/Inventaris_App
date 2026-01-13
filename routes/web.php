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
    Route::post('users/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
    Route::resource('users', AdminUserController::class);


    // Monitoring & History
    Route::get('monitoring', [\App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('monitoring/{user}', [\App\Http\Controllers\Admin\MonitoringController::class, 'showUser'])->name('monitoring.user');

    Route::get('items/{item}', [\App\Http\Controllers\Admin\ItemHistoryController::class, 'show'])->name('items.show');
    Route::get('items-history', [\App\Http\Controllers\Admin\ItemHistoryController::class, 'history'])->name('items.history');
    Route::get('items-history/{user}', [\App\Http\Controllers\Admin\ItemHistoryController::class, 'userHistory'])->name('items.user_history');
    Route::get('items-history-export', [\App\Http\Controllers\Admin\ItemHistoryController::class, 'exportHistory'])->name('items.history.export');

    // Centralized Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/generate', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');

    // Template Settings
    Route::get('settings/template', [\App\Http\Controllers\Admin\TemplateController::class, 'index'])->name('templates.index');
    Route::post('settings/template', [\App\Http\Controllers\Admin\TemplateController::class, 'upload'])->name('templates.upload');
    Route::get('settings/template/download/{type}', [\App\Http\Controllers\Admin\TemplateController::class, 'download'])->name('templates.download');

    // Maintenance (Pemeliharaan)
    Route::get('maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('maintenance/clear-history', [\App\Http\Controllers\Admin\MaintenanceController::class, 'clearHistory'])->name('maintenance.clear_history');
    Route::post('maintenance/clear-items', [\App\Http\Controllers\Admin\MaintenanceController::class, 'clearItems'])->name('maintenance.clear_items');
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
    Route::resource('items', ItemController::class);
});
