<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\WawancaraController;
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

Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/{id}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
Route::post('/pendaftaran/{id}/verifikasi', [PendaftaranController::class, 'verifikasi'])->name('pendaftaran.verifikasi');

Route::put('/pendaftaran/{id}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');

Route::delete('/pendaftaran/{id}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');

require __DIR__.'/auth.php';

Route::get('/provinsi', [WilayahController::class, 'provinsi']);
Route::get('/kabupaten/{id}', [WilayahController::class, 'kabupaten']);
Route::get('/kecamatan/{id}', [WilayahController::class, 'kecamatan']);
Route::get('/kelurahan/{id}', [WilayahController::class, 'kelurahan']);

Route::get('/dashboard-admin', [DashboardController::class, 'index'])
    ->name('dashboard-admin');

Route::get('/wawancara', [WawancaraController::class, 'index'])->name('wawancara.index');
Route::get('/wawancara/{id}/edit', [WawancaraController::class, 'edit'])
    ->name('wawancara.edit');

Route::post('/wawancara/{id}', [WawancaraController::class, 'store'])
    ->name('wawancara.store');