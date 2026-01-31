<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\DokterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/daftar-berobat', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/daftar-berobat', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/riwayat', [PendaftaranController::class, 'riwayat'])->name('pendaftaran.riwayat');

    Route::get('/jadwal-dokter', [HomeController::class, 'jadwalDokter'])->name('pasien.jadwal');
    Route::get('/info-poli', [HomeController::class, 'infoPoli'])->name('pasien.poli');

    Route::get('/riwayat', [PendaftaranController::class, 'riwayat'])->name('pendaftaran.riwayat');

    // API Routes
    Route::get('/api/riwayat-terbaru', [PendaftaranController::class, 'getRiwayatJson'])->name('pendaftaran.json');
    Route::get('/api/live-antrian/{poli_id}', [PendaftaranController::class, 'getLiveAntrianJson']);
});

// routes/web.php

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/pelayanan', [AdminController::class, 'pelayanan'])->name('admin.pelayanan');
    Route::post('/verifikasi/{id}', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::post('/periksa/{id}', [AdminController::class, 'prosesPeriksa'])->name('admin.periksa');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

    // Tambahkan route ini:
    Route::get('/api/pending-terbaru', [AdminController::class, 'getPendingJson'])->name('admin.pending.json');

    Route::resource('poli', PoliController::class);
    Route::resource('dokter', DokterController::class);
});
