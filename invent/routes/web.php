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
use App\Http\Controllers\ManageLoanController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\profilController;
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
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/newLoan', [NewLoanController::class, 'index'])->name('newLoan');
    Route::get('/manageLoan', [ManageLoanController::class, 'index'])->name('pages.manageLoan');
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::get('/settings', [SettingController::class, 'index'])->name('setting');
    Route::get('/profil', [profilController::class, 'index'])->name('profil');

    Route::post('/users/store', [UserManagementController::class, 'store'])->name('users.store');
    Route::post('/locations', [LocationController::class, 'store']);


    Route::get('/items/filter', [ItemController::class, 'filter']);

    Route::get('/history/filter', [HistoryController::class, 'filter'])->name('history.filter');

    // routes/web.php


    Route::get('/loans/export-history', [LoanController::class, 'exportHistory'])->name('loans.exportHistory');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

    Route::get('/batre', function () {
        return "baterai abis";
    });
});
