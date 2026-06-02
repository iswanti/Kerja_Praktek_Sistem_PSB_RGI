<?php

use App\Http\Controllers\AlumniPendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GelombangController;
use App\Http\Controllers\JadwalWawancaraController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WawancaraController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

require __DIR__.'/auth.php'; // ✅ Hapus yang duplikat

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.photo.update');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    })->name('notifications.read');

    /*
    |--------------------------------------------------------------------------
    | Siswa — tidak pakai menu permission
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role?->nama;
        if (in_array($role, ['Superadmin', 'Admin'])) {
            return redirect()->route('admin.dashboard');
        }
        if ($role === 'Tim Wawancara') {
            return app(DashboardController::class)->timWawancara();
        }
        return app(DashboardController::class)->siswa();
    })->middleware('verified')->name('dashboard');

    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');

    Route::get('/seleksi', [SeleksiController::class, 'index'])->name('seleksi.index');
    Route::get('/seleksi/pretest', [SeleksiController::class, 'pretest'])->name('seleksi.pretest');
    Route::post('/seleksi/pretest', [SeleksiController::class, 'submitPretest'])->name('seleksi.submit');

    Route::get('/info-pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.publik');

    /*
    |--------------------------------------------------------------------------
    | Admin — pakai menu permission
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard (cukup auth, tidak perlu permission)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export-ringkasan', [DashboardController::class, 'exportRingkasan'])
        ->name('dashboard.export-ringkasan');

        // ===== PENDAFTARAN =====
        Route::middleware('menu:Pendaftaran,create')->group(function () {
            Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
            Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
        });

        Route::middleware('menu:Pendaftaran,read')->group(function () {
            Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
            Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
        });

        Route::middleware('menu:Pendaftaran,update')->group(function () {
            Route::get('/pendaftaran/{id}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
            Route::put('/pendaftaran/{id}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
            Route::post('/pendaftaran/{id}/verifikasi', [PendaftaranController::class, 'verifikasi'])->name('pendaftaran.verifikasi');
        });

        Route::middleware('menu:Pendaftaran,delete')
            ->delete('/pendaftaran/{id}', [PendaftaranController::class, 'destroy'])
            ->name('pendaftaran.destroy');

        // ===== PENGGUNA =====
        Route::middleware('menu:Pengguna,read')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
        });

        Route::middleware('menu:Pengguna,create')->group(function () {
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
        });

        Route::middleware('menu:Pengguna,update')->group(function () {
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
            Route::get('/users/{id}/password', [UserController::class, 'editPassword'])->name('users.password.edit');
            Route::put('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.password.update');
        });

        Route::middleware('menu:Pengguna,delete')
            ->delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        // ===== KEWENANGAN =====
        Route::middleware('menu:Kewenangan,read')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            // Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
        });

        Route::middleware('menu:Kewenangan,create')->group(function () {
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        });

        Route::middleware('menu:Kewenangan,update')->group(function () {
            Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
            Route::get('/roles/{id}/permission', [RoleController::class, 'permission'])->name('roles.permission');
            Route::put('/roles/{id}/permission', [RoleController::class, 'updatePermission'])->name('roles.permission.update');
        });

        Route::middleware('menu:Kewenangan,delete')
            ->delete('/roles/{id}', [RoleController::class, 'destroy'])
            ->name('roles.destroy');

        // ===== GELOMBANG =====
        Route::middleware('menu:Gelombang,read')->group(function () {
            Route::get('/gelombang', [GelombangController::class, 'index'])->name('gelombang.index');
        });

        Route::middleware('menu:Gelombang,create')->group(function () {
            Route::get('/gelombang/create', [GelombangController::class, 'create'])->name('gelombang.create');
            Route::post('/gelombang', [GelombangController::class, 'store'])->name('gelombang.store');
        });

        Route::middleware('menu:Gelombang,update')->group(function () {
            Route::get('/gelombang/{id}/edit', [GelombangController::class, 'edit'])->name('gelombang.edit');
            Route::put('/gelombang/{id}', [GelombangController::class, 'update'])->name('gelombang.update');
        });

        Route::middleware('menu:Gelombang,delete')
            ->delete('/gelombang/{id}', [GelombangController::class, 'destroy'])
            ->name('gelombang.destroy');

        // ===== JADWAL WAWANCARA =====
        Route::middleware('menu:Jadwal Wawancara,read')->group(function () {
            Route::get('/jadwal-wawancara', [JadwalWawancaraController::class, 'index'])->name('jadwal-wawancara.index');
        });

        Route::middleware('menu:Jadwal Wawancara,create')->group(function () {
            Route::get('/jadwal-wawancara/create', [JadwalWawancaraController::class, 'create'])->name('jadwal-wawancara.create');
            Route::post('/jadwal-wawancara', [JadwalWawancaraController::class, 'store'])->name('jadwal-wawancara.store');
        });

        Route::middleware('menu:Jadwal Wawancara,update')->group(function () {
            Route::get('/jadwal-wawancara/{id}/edit', [JadwalWawancaraController::class, 'edit'])->name('jadwal-wawancara.edit');
            Route::put('/jadwal-wawancara/{id}', [JadwalWawancaraController::class, 'update'])->name('jadwal-wawancara.update');
        });

        Route::middleware('menu:Jadwal Wawancara,delete')
            ->delete('/jadwal-wawancara/{id}', [JadwalWawancaraController::class, 'destroy'])
            ->name('jadwal-wawancara.destroy');

        // ===== WAWANCARA =====
        Route::middleware('menu:Wawancara,read')->group(function () {
            Route::get('/wawancara', [WawancaraController::class, 'index'])->name('wawancara.index');
            Route::get('/wawancara/{id}', [WawancaraController::class, 'show'])->name('wawancara.show');
        });

        Route::middleware('menu:Wawancara,update')->group(function () {
            Route::get('/wawancara/{id}/edit', [WawancaraController::class, 'edit'])->name('wawancara.edit');
            Route::post('/wawancara/{id}', [WawancaraController::class, 'store'])->name('wawancara.store');
        });

        // ===== BANK SOAL =====
        Route::middleware('menu:Bank Soal,read')->group(function () {
            Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');
        });

        Route::middleware('menu:Bank Soal,create')->group(function () {
            Route::get('/soal/create', [SoalController::class, 'create'])->name('soal.create');
            Route::post('/soal', [SoalController::class, 'store'])->name('soal.store');
        });

        Route::middleware('menu:Bank Soal,update')->group(function () {
            Route::get('/soal/{soal}/edit', [SoalController::class, 'edit'])->name('soal.edit');
            Route::put('/soal/{soal}', [SoalController::class, 'update'])->name('soal.update');
        });

        Route::middleware('menu:Bank Soal,delete')
            ->delete('/soal/{soal}', [SoalController::class, 'destroy'])
            ->name('soal.destroy');
        
        Route::middleware('menu:Hasil Pretest,read')->group(function () {
            Route::get('/hasil-pretest', [PretestController::class, 'index'])
                ->name('pretest.index');
        });

        Route::middleware('menu:Hasil Pretest,download')->group(function () {
            Route::get('/hasil-pretest/export', [PretestController::class, 'export'])
                ->name('pretest.export');
        });

        // ===== DATA ALUMNI =====
        Route::middleware('menu:Data Alumni,read')->group(function () {
            Route::get('/alumni', [AlumniPendaftaranController::class, 'index'])
                ->name('alumni.index');
        });

        Route::middleware('menu:Data Alumni,create')->group(function () {
            Route::get('/alumni/create', [AlumniPendaftaranController::class, 'create'])
                ->name('alumni.create');
            Route::post('/alumni', [AlumniPendaftaranController::class, 'store'])
                ->name('alumni.store');
            Route::post('/alumni/import', [AlumniPendaftaranController::class, 'import'])
                ->name('alumni.import');
            Route::get('/alumni/template', [AlumniPendaftaranController::class, 'template']) // ← tambah ini
                ->name('alumni.template');
        });

        Route::middleware('menu:Data Alumni,update')->group(function () {
            Route::get('/alumni/{id}/edit', [AlumniPendaftaranController::class, 'edit'])
                ->name('alumni.edit');

            Route::put('/alumni/{id}', [AlumniPendaftaranController::class, 'update'])
                ->name('alumni.update');
        });

        Route::middleware('menu:Data Alumni,delete')
            ->delete('/alumni/{id}', [AlumniPendaftaranController::class, 'destroy'])
            ->name('alumni.destroy');
        
    });
});

/*
|--------------------------------------------------------------------------
| Wilayah — public, tidak perlu auth
|--------------------------------------------------------------------------
*/
Route::get('/provinsi', [WilayahController::class, 'provinsi']);
Route::get('/kabupaten/{id}', [WilayahController::class, 'kabupaten']);
Route::get('/kecamatan/{id}', [WilayahController::class, 'kecamatan']);
Route::get('/kelurahan/{id}', [WilayahController::class, 'kelurahan']);