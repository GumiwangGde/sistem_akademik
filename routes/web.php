<?php

    use App\Http\Controllers\DosenController;
    use App\Http\Controllers\KelasController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
    use Illuminate\Auth\Events\Verified;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;

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

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::get('/users', [UserController::class, 'index'])->name('users');
    });    

    // Rute Admin
    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::get('admin', function () {
            return view('dashboard'); 
        })->name('admin.dashboard');

    // CRUD Dosen oleh admin

        // Menampilkan daftar dosen
        Route::get('dosen', [DosenController::class, 'index'])->name('dosen.index');

        // Menampilkan form tambah dosen
        Route::get('dosen/create', [DosenController::class, 'create'])->name('dosen.create');

        // Proses simpan dosen
        Route::post('dosen', [DosenController::class, 'store'])->name('dosen.store');

        // Hapus dosen
        Route::delete('dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');
    });
    
    // Rute Dosen
    Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // Rute Mahasiswa
    Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    require __DIR__ . '/auth.php';