<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;   // Pastikan di-import
use App\Http\Controllers\DokterController; // Pastikan di-import
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Login/Register bawaan Laravel
Auth::routes();

// 3. Route untuk User yang SUDAH LOGIN (Pasien)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Fitur Pendaftaran
    Route::get('/daftar-berobat', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/daftar-berobat', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/riwayat', [PendaftaranController::class, 'riwayat'])->name('pendaftaran.riwayat');



    Route::get('/jadwal-dokter', [HomeController::class, 'jadwalDokter'])->name('pasien.jadwal');
    Route::get('/info-poli', [HomeController::class, 'infoPoli'])->name('pasien.poli');

    Route::get('/api/riwayat-terbaru', [PendaftaranController::class, 'getRiwayatJson'])->name('pendaftaran.json');
});

// 4. Route untuk ADMIN (Petugas)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/pelayanan', [AdminController::class, 'pelayanan'])->name('admin.pelayanan');
    Route::post('/verifikasi/{id}', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::post('/periksa/{id}', [AdminController::class, 'prosesPeriksa'])->name('admin.periksa');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

    // CRUD Poli & Dokter
    Route::resource('poli', PoliController::class);
    Route::resource('dokter', DokterController::class);

    Route::get('/api/pending-terbaru', [AdminController::class, 'getPendingJson'])->name('admin.pending.json');
});
