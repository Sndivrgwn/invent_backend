<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/search-items', [ItemController::class, 'search']);
// routes/api.php atau routes/web.php (sesuai penggunaan)
Route::get('/location/{id}', [LocationController::class, 'show']);
Route::delete('/location/{id}', [LocationController::class, 'destroy']);


Route::put('/analytics/{id}', [App\Http\Controllers\AnalyticsController::class, 'update']);
Route::delete('/analytics/{id}', [App\Http\Controllers\AnalyticsController::class, 'destroy']);

Route::get('/users/{id}', [UserManagementController::class, 'show']);
Route::get('/users/{id}/loans', [UserManagementController::class, 'userLoans']);
Route::put('/users/{id}', [UserManagementController::class, 'update']);
Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);

Route::get('/history/{id}', [App\Http\Controllers\HistoryController::class, 'show']);
Route::put('/history/{id}', [App\Http\Controllers\HistoryController::class, 'update']);
Route::delete('/history/{id}', [App\Http\Controllers\HistoryController::class, 'destroy']);


Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'loginapi']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'registerapi']);
Route::apiResource('roles', RolesController::class);
Route::apiResource('users', UsersController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('locations', LocationController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('loans', LoanController::class);
Route::apiResource('returns', ReturnController::class);
