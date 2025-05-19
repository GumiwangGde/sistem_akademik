<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RuangController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard untuk pengguna yang sudah terverifikasi
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route untuk ruang
// Rute Ruang untuk Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Rute Ruang
    Route::resource('admin/ruang', RuangController::class)->names([
        'index' => 'admin.ruang.index',
        'create' => 'admin.ruang.create',
        'store' => 'admin.ruang.store',
        'show' => 'admin.ruang.show',
        'edit' => 'admin.ruang.edit',
        'update' => 'admin.ruang.update',
        'destroy' => 'admin.ruang.destroy'
    ])->middleware(['auth', 'verified', 'role:admin']);
});

// Rute Profile untuk pengguna terautentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// // Rute Kelas dan Users hanya untuk pengguna yang terverifikasi
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
//     Route::get('admin/users', [UserController::class, 'index'])->name('users');
// });

// Rute Admin yang hanya bisa diakses oleh admin terverifikasi
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Dashboard untuk Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // // Rute Matakuliah untuk Admin
    // Route::get('/admin/matakuliah', function () {
    //     return view('admin.matakuliah.index');
    // })->name('admin.matakuliah.index');

    // Rute Dosen (menggunakan resource untuk CRUD)
    Route::prefix('admin')->group(function () {
        Route::resource('dosen', DosenController::class);

        // Pindahkan rute users ke sini, dalam grup admin
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy'); // Route delete user

        // Route untuk Kelas
        Route::resource('kelas', KelasController::class); // Ini sudah mencakup route DELETE /kelas/{kelas}
        Route::post('/kelas/{kelas}/activate', [KelasController::class, 'activate'])->name('kelas.activate');
        Route::get('/kelas/{id}/detail', [KelasController::class, 'detail'])->name('kelas.detail');

        Route::resource('matakuliah', MatakuliahController::class); // Resource route for Matakuliah

        // Route untuk Mahasiswa
        Route::resource('mahasiswa', MahasiswaController::class);
    });
});

// Menyertakan rute autentikasi
require __DIR__ . '/auth.php';
