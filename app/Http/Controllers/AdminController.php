<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php

    public function index()
    {
        $isOpen = Setting::where('key', 'is_open')->first()->value ?? '0';
        $today = date('Y-m-d');

        $stats = [
            'total_pasien' => User::where('role', '!=', 'admin')->count(),
            'total_poli'   => Poli::count(),
            'total_dokter' => Dokter::count(),
            'pending'      => Pendaftaran::where('status', 'Menunggu')->where('tanggal_kunjungan', $today)->count(),
            'verified'     => Pendaftaran::where('status', 'Terverifikasi')->where('tanggal_kunjungan', $today)->count(),
            'done'         => Pendaftaran::where('status', 'Selesai')->where('tanggal_kunjungan', $today)->count(),
        ];

        $pending_today = Pendaftaran::where('status', 'Menunggu')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])->latest()->get();

        $pending_others = Pendaftaran::whereIn('status', ['Menunggu', 'Terverifikasi'])
            ->where('tanggal_kunjungan', '>', $today)
            ->with(['poli', 'user'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'pending_today', 'pending_others', 'isOpen'));
    }

    public function pelayanan()
    {
        $today = date('Y-m-d');
        $polis = Poli::all();

        // Pasien yang sedang diperiksa (Status: Dipanggil) - Maks 1 per Poli
        $sedang_diperiksa = Pendaftaran::where('status', 'Dipanggil')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])->get();

        // Antrean yang sudah diverifikasi dan menunggu dipanggil (Status: Terverifikasi)
        $antrean_menunggu = Pendaftaran::where('status', 'Terverifikasi')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.pelayanan', compact('sedang_diperiksa', 'antrean_menunggu', 'polis'));
    }

    public function verifikasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $isOpen = Setting::where('key', 'is_open')->first()->value ?? '0';
        $today = date('Y-m-d');

        if ($pendaftaran->tanggal_kunjungan == $today) {
            $isPoliBusy = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
                ->where('tanggal_kunjungan', $today)
                ->where('status', 'Dipanggil')
                ->exists();

            // Jika buka dan poli kosong, langsung panggil. Jika tidak, masuk antrean terverifikasi.
            if ($isOpen == '1' && !$isPoliBusy) {
                $pendaftaran->status = 'Dipanggil';
            } else {
                $pendaftaran->status = 'Terverifikasi';
            }
        } else {
            // Untuk booking hari lain, status berubah jadi Terverifikasi dan menunggu hari H
            $pendaftaran->status = 'Terverifikasi';
        }

        $pendaftaran->save();
        return back()->with('success', 'Pendaftaran berhasil diverifikasi.');
    }

    public function tolak($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Anda bisa menghapus datanya atau mengubah statusnya menjadi 'Ditolak'
        $pendaftaran->update(['status' => 'Ditolak']);

        return back()->with('success', 'Pendaftaran telah ditolak.');
    }
    public function prosesPeriksa(Request $request, $id)
    {
        $request->validate(['catatan_medis' => 'required']);
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'Selesai',
            'catatan_medis' => $request->catatan_medis
        ]);

        // OTOMATIS panggil antrean berikutnya di poli yang sama jika Puskesmas Buka
        $isOpen = Setting::where('key', 'is_open')->first()->value;
        if ($isOpen == '1') {
            $next = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
                ->where('tanggal_kunjungan', date('Y-m-d'))
                ->where('status', 'Terverifikasi')
                ->orderBy('id', 'asc')
                ->first();

            if ($next) {
                $next->update(['status' => 'Dipanggil']);
            }
        }

        return back()->with('success', 'Pemeriksaan selesai. Antrean berikutnya otomatis dipanggil.');
    }

    public function toggleOpen()
    {
        $setting = Setting::where('key', 'is_open')->first();
        $today = date('Y-m-d');

        // 1. Cek apakah status saat ini sedang 'BUKA' (1) dan ingin ditutup
        if ($setting->value == '1') {
            // 2. Hitung apakah masih ada pasien yang sedang diperiksa (status 'Dipanggil')
            $masihAdaPasien = Pendaftaran::where('tanggal_kunjungan', $today)
                ->where('status', 'Dipanggil')
                ->exists();

            if ($masihAdaPasien) {
                // Jika masih ada data di pelayanan dokter, kembalikan pesan error
                return back()->with('error', 'Gagal menutup! Masih ada pasien di meja pemeriksaan. Selesaikan semua pelayanan terlebih dahulu.');
            }
        }

        // 3. Jika tidak ada pasien atau sedang ingin membuka, eksekusi toggle
        $newState = ($setting->value == '1') ? '0' : '1';
        $setting->update(['value' => $newState]);

        if ($newState == '1') {
            $this->panggilAntreanPertamaSemuaPoli();
        }

        return back()->with('success', 'Status operasional berhasil diubah.');
    }

    private function panggilAntreanPertamaSemuaPoli()
    {
        $polis = Poli::all();
        $today = date('Y-m-d');

        foreach ($polis as $poli) {
            $isAnyBusy = Pendaftaran::where('poli_id', $poli->id)
                ->where('tanggal_kunjungan', $today)
                ->where('status', 'Dipanggil')
                ->exists();

            if (!$isAnyBusy) {
                $next = Pendaftaran::where('poli_id', $poli->id)
                    ->where('tanggal_kunjungan', $today)
                    ->where('status', 'Terverifikasi')
                    ->orderBy('id', 'asc')
                    ->first();

                if ($next) {
                    $next->update(['status' => 'Dipanggil']);
                }
            }
        }
    }
}
