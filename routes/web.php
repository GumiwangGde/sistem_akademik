<?php

    use App\Http\Controllers\DosenController;
    use App\Http\Controllers\KelasController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
    use Illuminate\Auth\Events\Verified;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;
    use App\http\Controller\MatakuliahController;

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
    // Dashboard untuk admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');  
    })->name('admin.dashboard');

    Route::get('/admin/matakuliah', function() {
        return view('admin.matakuliah.index');
    })->name('admin.matakuliah.index');

    Route::get('/admin/dosen', function() {
        return view('admin.dosen.index');
    })->name('admin.dosen.index');

    // Tambah rute create untuk dosen
    Route::get('/admin/dosen/create', [DosenController::class, 'create'])->name('admin.dosen.create');
    Route::post('/admin/dosen', [DosenController::class, 'store'])->name('admin.dosen.store');

});

    
    // // Rute Dosen
    // Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
    //     Route::get('/dashboard', function () {
    //         return view('dashboard');
    //     })->name('dashboard');
    // });

    // // Rute Mahasiswa
    // Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    //     Route::get('/dashboard', function () {
    //         return view('dashboard');
    //     })->name('dashboard');
    // });

    require __DIR__ . '/auth.php';