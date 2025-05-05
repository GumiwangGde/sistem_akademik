<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('admin', function () {
    return '<h1>Hello admin</h1>';
})->middleware(['auth', 'verified', 'role:admin']);

Route::get('dosen', function () {
    return '<h1>Hello dosen</h1>';
})->middleware(['auth', 'verified', 'role:dosen']);

Route::get('mahasiswa', function () {
    return '<h1>Hello mahasiswa</h1>';
})->middleware(['auth', 'verified', 'role:mahasiswa']);

require __DIR__ . '/auth.php';
