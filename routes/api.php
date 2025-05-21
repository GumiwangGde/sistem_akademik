<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mobile\AuthController as MobileAuthController;
use App\Http\Controllers\Mobile\DosenController;
use App\Http\Controllers\Mobile\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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

// Mobile Mahasiswa Routes
Route::middleware(['auth:sanctum', 'role:mahasiswa'])->prefix('mobile/mahasiswa')->group(function () {
    // Profile
    Route::get('/profile', [MahasiswaController::class, 'profile']);
    
    // FRS Management
    Route::get('/matakuliah', [MahasiswaController::class, 'getAvailableMatakuliah']);
    Route::get('/frs', [MahasiswaController::class, 'getFRS']);
    Route::post('/frs', [MahasiswaController::class, 'createFRS']);
    Route::delete('/frs/{id}', [MahasiswaController::class, 'deleteFRS']);
    
    // Jadwal
    Route::get('/jadwal', [MahasiswaController::class, 'getJadwal']);
    
    // Nilai
    Route::get('/nilai', [MahasiswaController::class, 'getNilai']);
});

// Mobile Dosen Routes
Route::middleware(['auth:sanctum', 'role:dosen'])->prefix('mobile/dosen')->group(function () {
    // Profile
    Route::get('/profile', [DosenController::class, 'profile']);
    
    // Matakuliah & Jadwal
    Route::get('/matakuliah', [DosenController::class, 'getMatakuliah']);
    Route::get('/jadwal', [DosenController::class, 'getJadwal']);
    
    // FRS Approval (for dosen wali)
    Route::get('/frs/pending', [DosenController::class, 'getPendingFRS']);
    Route::put('/frs/approve', [DosenController::class, 'approveFRS']);
    Route::get('/mahasiswa-wali', [DosenController::class, 'getMahasiswaWali']);
    
    // Nilai Management
    Route::get('/matakuliah/{id_mk}/mahasiswa', [DosenController::class, 'getMahasiswaByMatakuliah']);
    Route::put('/nilai', [DosenController::class, 'inputNilai']);
});