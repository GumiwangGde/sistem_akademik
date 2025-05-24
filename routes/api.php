<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mobile\AuthController as MobileAuthController;

// Import Dosen controllers
use App\Http\Controllers\Mobile\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Mobile\Dosen\MatakuliahController as DosenMatakuliahController;
use App\Http\Controllers\Mobile\Dosen\FrsController as DosenFrsController;
use App\Http\Controllers\Mobile\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Mobile\Dosen\WaliController as DosenWaliController;
use App\Http\Controllers\Mobile\Dosen\DosenDashboardController;

// Import Mahasiswa controllers (BARU)
use App\Http\Controllers\Mobile\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mobile\Mahasiswa\FrsController as MahasiswaFrsController;
use App\Http\Controllers\Mobile\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Mobile\Mahasiswa\NilaiController as MahasiswaNilaiController;
// Hapus MahasiswaController lama jika semua sudah dipindah:
// use App\Http\Controllers\Mobile\MahasiswaController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
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

// Mobile Mahasiswa Routes (DIPERBARUI)
Route::middleware(['auth:sanctum', 'role:mahasiswa'])->prefix('mobile/mahasiswa')->group(function () {
    // Profile
    Route::get('/profile', [MahasiswaProfileController::class, 'profile']);
    
    // FRS Management
    Route::get('/matakuliah/available', [MahasiswaFrsController::class, 'getAvailableMatakuliah']); // Path diubah agar lebih deskriptif
    Route::get('/frs', [MahasiswaFrsController::class, 'getFRS']);
    Route::post('/frs', [MahasiswaFrsController::class, 'createFRS']);
    Route::delete('/frs/{id_frs}', [MahasiswaFrsController::class, 'deleteFRS']); // Menggunakan {id_frs} agar konsisten
    
    // Jadwal
    Route::get('/jadwal', [MahasiswaJadwalController::class, 'getJadwal']);
    
    // Nilai
    Route::get('/nilai', [MahasiswaNilaiController::class, 'getNilai']);
});

// Mobile Dosen Routes
Route::middleware(['auth:sanctum', 'role:dosen'])->prefix('mobile/dosen')->group(function () {
    // Profile
    Route::get('/profile', [DosenProfileController::class, 'profile']);
    Route::put('/profile', [DosenProfileController::class, 'updateProfile']);
    
    // Matakuliah & Jadwal
    Route::get('/matakuliah', [DosenMatakuliahController::class, 'getMatakuliah']);
    Route::get('/jadwal', [DosenMatakuliahController::class, 'getJadwal']);
    Route::get('/dashboard/jadwal-hari-ini', [DosenDashboardController::class, 'getJadwalHariIni']);
    
    // FRS Approval (for dosen wali)
    Route::get('/frs/pending', [DosenFrsController::class, 'getPendingFRS']);
    Route::put('/frs/approve', [DosenFrsController::class, 'approveFRS']);
    Route::get('/frs/mahasiswa/{id_mahasiswa}', [DosenFrsController::class, 'getAllFrsForMahasiswa']);
    
    // Wali specific
    Route::get('/mahasiswa-wali', [DosenWaliController::class, 'getMahasiswaWali']);
    
    // Nilai Management
    Route::get('/matakuliah/{id_mk}/mahasiswa', [DosenNilaiController::class, 'getMahasiswaByMatakuliah']);
    Route::put('/nilai', [DosenNilaiController::class, 'inputNilai']);
});
