<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
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

/*
|--------------------------------------------------------------------------
| ROLE ROUTES
|--------------------------------------------------------------------------
*/

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/admin', function () {
        return "Halaman Admin";
    });

    // MANAJEMEN USER
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
});

// INSTRUKTUR
Route::middleware(['auth', 'role:instruktur'])->group(function () {
    Route::get('/instruktur', function () {
        return "Halaman Instruktur";
    });
});

// MANAJEMEN
Route::middleware(['auth', 'role:manajemen'])->group(function () {
    Route::get('/manajemen', function () {
        return "Halaman Manajemen";
    });
});

// SISWA
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa', function () {
        return "Halaman Siswa";
    });
});

require __DIR__.'/auth.php';