<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\KnnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Posyandu
    Route::resource('balita', BalitaController::class)->parameters([
        'balita' => 'balita'
    ]);
    Route::resource('pemeriksaan', PemeriksaanController::class);

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Only Actions
    Route::middleware('role:admin')->group(function () {
        Route::get('/evaluasi-knn', [KnnController::class, 'evaluasi'])->name('knn.evaluasi');
        Route::post('/kunjungan/import', [ImportController::class, 'importCsv'])->name('kunjungan.import');
        
        // Manajemen User
        Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);
    });
});

require __DIR__.'/auth.php';
