<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\LoginController; // Tidak digunakan di sini
use App\Http\Controllers\MatakuliahController; // Ini JadwalKuliahController
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TahunAjaranController;    // Controller Baru
use App\Http\Controllers\ProdiController;           // Controller Baru
use App\Http\Controllers\MasterMatakuliahController; // Controller Baru
use App\Http\Controllers\BeritaController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Rute Profile untuk pengguna terautentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Admin yang hanya bisa diakses oleh admin terverifikasi
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/calendar/upload', [DashboardController::class, 'uploadCalendar'])->name('calendar.upload');
    Route::get('/laporan/unduh', [ReportController::class, 'download'])->name('laporan.unduh');

    // Rute Ruang (Ini akan memiliki nama seperti admin.ruang.index)
    Route::resource('ruang', RuangController::class);

    // Rute Dosen (sesuai definisi Anda sebelumnya, ini menghasilkan nama seperti admin.dosen.index)
    Route::resource('dosen', DosenController::class)->names([
        'index' => 'dosen.index',
        'create' => 'dosen.create',
        'store' => 'dosen.store',
        'show' => 'dosen.show',
        'edit' => 'dosen.edit',
        'update' => 'dosen.update',
        'destroy' => 'dosen.destroy',
    ]);

    // Rute Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Route untuk Kelas
    Route::resource('kelas', KelasController::class)->names([
        'index' => 'kelas.index',
        'create' => 'kelas.create',
        'store' => 'kelas.store',
        'show' => 'kelas.detail',
        'edit' => 'kelas.edit',
        'update' => 'kelas.update',
        'destroy' => 'kelas.destroy',
    ]);
    Route::post('/kelas/{kelas}/toggle-status', [KelasController::class, 'toggleStatus'])->name('kelas.toggleStatus');


    // Route untuk Jadwal Kuliah (menggunakan MatakuliahController)
    Route::resource('matakuliah', MatakuliahController::class)->names([
        'index' => 'matakuliah.index',
        'create' => 'matakuliah.create',
        'store' => 'matakuliah.store',
        'show' => 'matakuliah.show',
        'edit' => 'matakuliah.edit',
        'update' => 'matakuliah.update',
        'destroy' => 'matakuliah.destroy',
    ]);

    // Route untuk Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class)->names([
        'index' => 'mahasiswa.index',
        'create' => 'mahasiswa.create',
        'store' => 'mahasiswa.store',
        'show' => 'mahasiswa.show',
        'edit' => 'mahasiswa.edit',
        'update' => 'mahasiswa.update',
        'destroy' => 'mahasiswa.destroy',
    ]);

    // Route untuk Tahun Ajaran
    Route::resource('tahunajaran', TahunAjaranController::class);
    Route::post('tahunajaran/{tahunajaran}/set-active', [TahunAjaranController::class, 'setActive'])->name('tahunajaran.setActive');

    // Route untuk Prodi
    Route::resource('prodi', ProdiController::class);

    // Route untuk Master Mata Kuliah
    Route::resource('mastermatakuliah', MasterMatakuliahController::class);

    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita' 
    ]);

});

// Menyertakan rute autentikasi
require __DIR__ . '/auth.php';
