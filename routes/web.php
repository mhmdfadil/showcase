<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriTagController;
use App\Http\Controllers\KategoriTagMController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KaryaController;
use App\Http\Controllers\KaryaMController;
use App\Http\Controllers\PublikKaryaController;
use App\Http\Controllers\PublikKaryaMController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard');
        // Kategori Routes
        Route::get('/kategori-tag', [KategoriTagController::class, 'index'])->name('kategori-tag.index');
        Route::get('/kategori/create', [KategoriTagController::class, 'createKategori'])->name('kategori.create');
        Route::post('/kategori/store', [KategoriTagController::class, 'storeKategori'])->name('kategori.store');
        Route::get('/kategori/{id}/edit', [KategoriTagController::class, 'editKategori'])->name('kategori.edit');
        Route::put('/kategori/{id}/update', [KategoriTagController::class, 'updateKategori'])->name('kategori.update');
        Route::delete('/kategori/{id}/destroy', [KategoriTagController::class, 'destroyKategori'])->name('kategori.destroy');

        // Tag Routes
        Route::get('/tag/create', [KategoriTagController::class, 'createTag'])->name('tag.create');
        Route::post('/tag/store', [KategoriTagController::class, 'storeTag'])->name('tag.store');
        Route::get('/tag/{id}/edit', [KategoriTagController::class, 'editTag'])->name('tag.edit');
        Route::put('/tag/{id}/update', [KategoriTagController::class, 'updateTag'])->name('tag.update');
        Route::delete('/tag/{id}/destroy', [KategoriTagController::class, 'destroyTag'])->name('tag.destroy');

        Route::get('/pengguna/', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/pengguna/', [UserController::class, 'store'])->name('users.store');
        Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::put('/pengguna/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');

        // Public Karya Routes
        Route::prefix('karya')->group(function () {
            Route::get('/', [PublikKaryaController::class, 'index'])->name('akarya.index');
            Route::get('/draft', [PublikKaryaController::class, 'draft'])->name('akarya.draft');
            Route::get('/publish', [PublikKaryaController::class, 'publish'])->name('akarya.publish');
            Route::get('/kategori/{kategori}', [PublikKaryaController::class, 'byKategori'])->name('akarya.byKategori');
            Route::get('/kategori/{kategori}/{tahun}', [PublikKaryaController::class, 'byKategoriAndYear'])->name('akarya.byKategoriAndYear');
            Route::get('/{karya:slug}', [PublikKaryaController::class, 'show'])->name('akarya.show');
            Route::post('/{karya}/rate', [PublikKaryaController::class, 'rateKarya'])->name('akarya.rate');
                        Route::post('/karya/{karya}/komentar', [PublikKaryaController::class, 'storeComment'])->name('akarya.comment');
            Route::get('/komentar/{komentar}/balasan', [PublikKaryaController::class, 'getReplies'])->name('akomentar.balasan');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/statistik', [LaporanController::class, 'statistik'])->name('laporan.statistik');
            Route::get('/data', [LaporanController::class, 'data'])->name('laporan.data');
            Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');
            Route::get('/export/csv', [LaporanController::class, 'exportCSV'])->name('laporan.export.csv');
        });

        Route::get('/unggah-karya', [KaryaController::class, 'create'])->name('karya.create');
        Route::post('/unggah-karya', [KaryaController::class, 'store'])->name('karya.store');
        Route::get('/validasi-karya', [KaryaController::class, 'validasiIndex'])->name('admin.karya.index');
        Route::get('/validasi-karya/{karya:slug}', [KaryaController::class, 'show'])->name('karya.show');
        Route::put('/validasi-karya/{karya}/status', [KaryaController::class, 'updateStatus'])->name('karya.update-status');
    });
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Mahasiswa 
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexMhs'])->name('dashboard');
        Route::get('/kategori-tag', [KategoriTagMController::class, 'index'])->name('kategori-tag.index');

        Route::get('/unggah-karya', [KaryaMController::class, 'create'])->name('karya.create');
        Route::post('/unggah-karya', [KaryaMController::class, 'store'])->name('karya.store');
        Route::get('/karya-saya', [KaryaMController::class, 'karyaIndex'])->name('mahasiswa.karya.index');
        Route::get('/karya-saya/{karya:slug}', [KaryaMController::class, 'show'])->name('karya.show');

                // Public Karya Routes
        Route::prefix('karya')->group(function () {
            Route::get('/publish', [PublikKaryaMController::class, 'publish'])->name('akarya.publish');
            Route::get('/kategori/{kategori}', [PublikKaryaMController::class, 'byKategori'])->name('akarya.byKategori');
            Route::get('/kategori/{kategori}/{tahun}', [PublikKaryaMController::class, 'byKategoriAndYear'])->name('akarya.byKategoriAndYear');
            Route::get('/{karya:slug}', [PublikKaryaMController::class, 'show'])->name('akarya.show');
            Route::post('/{karya}/rate', [PublikKaryaMController::class, 'rateKarya'])->name('akarya.rate');
                        Route::post('/karya/{karya}/komentar', [PublikKaryaMController::class, 'storeComment'])->name('akarya.comment');
            Route::get('/komentar/{komentar}/balasan', [PublikKaryaMController::class, 'getReplies'])->name('akomentar.balasan');
        });
    });
});