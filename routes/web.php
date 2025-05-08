    <?php

    use App\Http\Controllers\DosenController;
    use App\Http\Controllers\KelasController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
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

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::get('/users', [UserController::class, 'index'])->name('users');
    });    

    // Rute Admin
    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::get('admin', function () {
            return '<h1>Hello admin</h1>';
        });

        // Admin bisa mengakses CRUD dosen
        Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
            Route::resource('dosen', DosenController::class)->parameters([
                'dosen' => 'dosen'
            ]);
    });

    // Rute Dosen
    Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
        Route::get('dashboard-dosen', function () {
            return '<h1>Hello dosen</h1>';
        });
    });

    // Rute Mahasiswa
    Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
        Route::get('mahasiswa', function () {
            return '<h1>Hello mahasiswa</h1>';
        });
    });

    require __DIR__ . '/auth.php';
