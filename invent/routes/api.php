<?php

use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request){
//     return $request->user();
// });
Route::apiResource('roles', RolesController::class);
Route::apiResource('users', UsersController::class);