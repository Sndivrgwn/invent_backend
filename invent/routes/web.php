<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NewLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('home');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'actionlogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ItemController::class, 'getAllItems'])->name('products');
    Route::get('/inventory', [LocationController::class, 'index'])->name('inventory');
    Route::get('/loan', [LoanController::class, 'index'])->name('loan');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/settings', [SettingController::class, 'index'])->name('setting');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/newLoan', [NewLoanController::class, 'index'])->name('newLoan');
    Route::post('/loans', [LoanController::class, 'store']);

    Route::get('/items/filter', [ItemController::class, 'filter']);
// routes/web.php


    Route::get('/loans/export-history', [LoanController::class, 'exportHistory'])->name('loans.exportHistory');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

    Route::get('/batre', function () {
        return "baterai abis";
    });
});
