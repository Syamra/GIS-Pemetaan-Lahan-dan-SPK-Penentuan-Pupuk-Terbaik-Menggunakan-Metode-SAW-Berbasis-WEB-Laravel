<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemetaanController;
use App\Http\Controllers\PemilikLahanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SAWController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CekRole;

// ==========================================
// 1. AKTOR: MASYARAKAT (Landing Page)
// ==========================================
// Halaman utama langsung menampilkan Peta Publik
Route::get('/', [PemetaanController::class, 'landing'])->name('landing');

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// RUTE TERKUNCI (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Dashboard (Bisa diakses Admin & Pimpinan)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ==========================================
    // AKTOR: PIMPINAN & ADMIN (Lihat & Cetak Laporan)
    // ==========================================
    Route::middleware([CekRole::class . ':admin,pimpinan'])->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak-saw/{id_lahan}', [LaporanController::class, 'cetakSaw'])->name('laporan.cetak_saw');

        // Pemetaan
        Route::get('/pemetaan', [PemetaanController::class, 'index'])->name('pemetaan.index');
        Route::post('/pemetaan', [PemetaanController::class, 'store'])->name('pemetaan.store');
        Route::delete('/pemetaan/{id}', [PemetaanController::class, 'destroy'])->name('pemetaan.destroy');
    });

    // ==========================================
    // AKTOR: ADMIN SAJA (Hak Akses Penuh CRUD)
    // ==========================================
    Route::middleware([CekRole::class . ':admin'])->group(function () {  

        Route::resource('pemilik', PemilikLahanController::class)->parameters(['pemilik' => 'id_pemilik']);

        // Master Data
        Route::resource('kriteria', KriteriaController::class);
        Route::resource('alternatif', AlternatifController::class);

        // Penilaian & SAW
        Route::get('/penilaian/{id_lahan}', [SAWController::class, 'formPenilaian'])->name('penilaian.form');
        Route::post('/penilaian/{id_lahan}', [SAWController::class, 'simpanPenilaian'])->name('penilaian.simpan');
        Route::post('/saw/hitung/{id_lahan}', [SAWController::class, 'hitung'])->name('saw.hitung');
    });
});