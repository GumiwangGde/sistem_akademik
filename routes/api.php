<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mobile\AuthController as MobileAuthController;
// Import the new Dosen controllers
use App\Http\Controllers\Mobile\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Mobile\Dosen\MatakuliahController as DosenMatakuliahController;
use App\Http\Controllers\Mobile\Dosen\FrsController as DosenFrsController;
use App\Http\Controllers\Mobile\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Mobile\Dosen\WaliController as DosenWaliController;
// The old DosenController is no longer needed here if all its methods are moved
// use App\Http\Controllers\Mobile\DosenController;
use App\Http\Controllers\Mobile\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\Dosen\DosenDashboardController;


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
    Route::get('/profile', [DosenProfileController::class, 'profile']);
    Route::put('/profile', [DosenProfileController::class, 'updateProfile']); // Changed from post to put for updates
    
    // Matakuliah & Jadwal
    Route::get('/matakuliah', [DosenMatakuliahController::class, 'getMatakuliah']);
    Route::get('/jadwal', [DosenMatakuliahController::class, 'getJadwal']);
    Route::get('/dashboard/jadwal-hari-ini', [DosenDashboardController::class, 'getJadwalHariIni']);
    
    // FRS Approval (for dosen wali)
    Route::get('/frs/pending', [DosenFrsController::class, 'getPendingFRS']);
    Route::put('/frs/approve', [DosenFrsController::class, 'approveFRS']); // Changed from post to put for updates/status changes
    
    // Wali specific
    Route::get('/mahasiswa-wali', [DosenWaliController::class, 'getMahasiswaWali']);
    
    // Nilai Management
    Route::get('/matakuliah/{id_mk}/mahasiswa', [DosenNilaiController::class, 'getMahasiswaByMatakuliah']);
    Route::put('/nilai', [DosenNilaiController::class, 'inputNilai']); // Changed from post to put for inputting/updating nilai
});