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
use App\Http\Controllers\Mobile\Dosen\DosenBeritaController; // PENAMBAHAN: Controller Berita Dosen

// Import Mahasiswa controllers
use App\Http\Controllers\Mobile\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mobile\Mahasiswa\FrsController as MahasiswaFrsController;
use App\Http\Controllers\Mobile\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Mobile\Mahasiswa\NilaiController as MahasiswaNilaiController;
use App\Http\Controllers\Mobile\Mahasiswa\MahasiswaDashboardController; // Sudah ada sebelumnya
use App\Http\Controllers\Mobile\Mahasiswa\MahasiswaBeritaController; // PENAMBAHAN: Controller Berita Mahasiswa


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

// Mobile Mahasiswa Routes
Route::middleware(['auth:sanctum', 'role:mahasiswa'])->prefix('mobile/mahasiswa')->name('mobile.mahasiswa.')->group(function () {
    // Profile
    Route::get('/profile', [MahasiswaProfileController::class, 'profile'])->name('profile');
    
    // FRS Management
    Route::get('/matakuliah/available', [MahasiswaFrsController::class, 'getAvailableMatakuliah'])->name('frs.availableMatakuliah');
    Route::get('/frs', [MahasiswaFrsController::class, 'getMyFRS'])->name('frs.index'); // Diubah ke getMyFRS dan diberi nama index
    Route::post('/frs', [MahasiswaFrsController::class, 'createFRS'])->name('frs.store');
    Route::delete('/frs/{id_frs}', [MahasiswaFrsController::class, 'deleteFRS'])->name('frs.destroy');
    
    // Jadwal
    Route::get('/jadwal', [MahasiswaJadwalController::class, 'getJadwal'])->name('jadwal.index');
    Route::get('/dashboard/jadwal-hari-ini', [MahasiswaDashboardController::class, 'getJadwalHariIni'])->name('dashboard.jadwalHariIni');
    
    // Nilai
    Route::get('/nilai', [MahasiswaNilaiController::class, 'getNilai'])->name('nilai.index');

    // --- PENAMBAHAN ROUTE BERITA UNTUK MAHASISWA ---
    Route::get('/berita', [MahasiswaBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/{slug}', [MahasiswaBeritaController::class, 'show'])->name('berita.show');
    // --- AKHIR PENAMBAHAN ROUTE BERITA MAHASISWA ---
});

// Mobile Dosen Routes
Route::middleware(['auth:sanctum', 'role:dosen'])->prefix('mobile/dosen')->name('mobile.dosen.')->group(function () {
    // Profile
    Route::get('/profile', [DosenProfileController::class, 'profile'])->name('profile');
    Route::put('/profile', [DosenProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Matakuliah & Jadwal
    Route::get('/matakuliah', [DosenMatakuliahController::class, 'getMatakuliah'])->name('matakuliah.index'); // Ini mungkin jadwal yang diampu
    Route::get('/jadwal', [DosenMatakuliahController::class, 'getJadwal'])->name('jadwal.index'); // Ini mungkin juga jadwal yang diampu
    Route::get('/dashboard/jadwal-hari-ini', [DosenDashboardController::class, 'getJadwalHariIni'])->name('dashboard.jadwalHariIni');
    
    // FRS Approval (for dosen wali)
    Route::get('/frs/pending', [DosenFrsController::class, 'getPendingFRS'])->name('frs.pending');
    Route::put('/frs/approve', [DosenFrsController::class, 'approveFRS'])->name('frs.approve'); // Menggunakan PUT lebih sesuai untuk update status
    Route::get('/frs/mahasiswa/{id_mahasiswa}', [DosenFrsController::class, 'getAllFrsForMahasiswa'])->name('frs.mahasiswa');
    
    // Wali specific
    Route::get('/mahasiswa-wali', [DosenWaliController::class, 'getMahasiswaWali'])->name('wali.mahasiswa');
    
    // Nilai Management
    Route::get('/matakuliah/{id_mk_jadwal}/mahasiswa', [DosenNilaiController::class, 'getMahasiswaByMatakuliah'])->name('nilai.mahasiswaByMatakuliah'); // id_mk_jadwal lebih deskriptif
    Route::put('/nilai', [DosenNilaiController::class, 'inputNilai'])->name('nilai.storeOrUpdate');

    // --- PENAMBAHAN ROUTE BERITA UNTUK DOSEN ---
    Route::get('/berita', [DosenBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/{slug}', [DosenBeritaController::class, 'show'])->name('berita.show');
    // --- AKHIR PENAMBAHAN ROUTE BERITA DOSEN ---
});
