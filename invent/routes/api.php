<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'registerapi']);
Route::apiResource('roles', RolesController::class);
Route::apiResource('users', UsersController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('locations', LocationController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('loans', LoanController::class);