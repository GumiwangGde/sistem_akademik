<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MatakuliahController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard untuk pengguna yang sudah terverifikasi
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profile untuk pengguna terautentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Kelas dan Users hanya untuk pengguna yang terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
    Route::get('/users', [UserController::class, 'index'])->name('users');
});

// Rute Admin yang hanya bisa diakses oleh admin terverifikasi
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Dashboard untuk Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Rute Matakuliah untuk Admin
    Route::get('/admin/matakuliah', function () {
        return view('admin.matakuliah.index');
    })->name('admin.matakuliah.index');

    // Rute Dosen (menggunakan resource untuk CRUD)
    Route::prefix('admin')->group(function () {
        Route::resource('dosen', DosenController::class);
        Route::put('/dosen/update/{id}', [DosenController::class, 'update'])->name('dosen.update');

    });
});

// Menyertakan rute autentikasi
require __DIR__ . '/auth.php';
