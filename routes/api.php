<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mobile\AuthController as MobileAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Mobile Authentication Routes
Route::post('/mobile/login', [MobileAuthController::class, 'login']);
Route::post('/mobile/logout', [MobileAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/mobile/user', [MobileAuthController::class, 'user'])->middleware('auth:sanctum');

// Web Authentication Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');