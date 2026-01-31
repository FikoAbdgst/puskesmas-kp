<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Statistik lengkap untuk Dashboard
        $stats = [
            'total_pasien' => User::where('role', '!=', 'admin')->count(),
            'total_poli'   => Poli::count(),
            'total_dokter' => Dokter::count(),
            // Sesuaikan key dengan view: 'pending', 'verified', dan 'done'
            'pending'      => Pendaftaran::where('status', 'Menunggu')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
            'verified'     => Pendaftaran::where('status', 'Terverifikasi')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
            'done'         => Pendaftaran::where('status', 'Selesai')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
        ];

        // Mengambil data untuk tabel pendaftaran baru di dashboard
        $pending = Pendaftaran::where('status', 'Menunggu')
            ->with(['poli', 'user'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'pending'));
    }

    /**
     * Menampilkan Halaman Pelayanan (Verifikasi & Meja Dokter)
     */
    public function pelayanan()
    {
        $pending = Pendaftaran::where('status', 'Menunggu')
            ->with(['poli', 'user'])
            ->latest()
            ->get();

        $periksa = Pendaftaran::where('status', 'Dipanggil')
            ->with(['poli', 'user'])
            ->get();

        return view('admin.pelayanan', compact('pending', 'periksa'));
    }

    /**
     * Proses Verifikasi Pendaftaran
     */
    public function verifikasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $isPoliBusy = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
            ->where('tanggal_kunjungan', date('Y-m-d'))
            ->where('status', 'Dipanggil')
            ->exists();

        // Jika poli kosong langsung masuk meja dokter, jika isi masuk antrean terverifikasi
        $pendaftaran->status = $isPoliBusy ? 'Terverifikasi' : 'Dipanggil';
        $pendaftaran->save();

        return back()->with('success', 'Pasien berhasil diverifikasi.');
    }

    /**
     * Selesai Periksa & Panggil Antrean Berikutnya
     */
    public function prosesPeriksa(Request $request, $id)
    {
        $request->validate([
            'catatan_medis' => 'required'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->status = 'Selesai';
        $pendaftaran->catatan_medis = $request->catatan_medis;
        $pendaftaran->save();

        // Cari pasien berikutnya yang statusnya 'Terverifikasi' di poli yang sama
        $nextQueue = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
            ->where('tanggal_kunjungan', date('Y-m-d'))
            ->where('status', 'Terverifikasi')
            ->orderBy('id', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->status = 'Dipanggil';
            $nextQueue->save();
        }

        return back()->with('success', 'Pemeriksaan selesai. Antrean berikutnya dipanggil.');
    }

    public function laporan()
    {
        $laporan = Pendaftaran::with(['poli', 'user'])->latest()->get();
        return view('admin.laporan', compact('laporan'));
    }
    // app/Http/Controllers/AdminController.php

    public function getPendingJson()
    {
        $stats = [
            'pending'  => Pendaftaran::where('status', 'Menunggu')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
            'verified' => Pendaftaran::where('status', 'Terverifikasi')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
            'done'     => Pendaftaran::where('status', 'Selesai')->where('tanggal_kunjungan', date('Y-m-d'))->count(),
        ];

        $pending = Pendaftaran::where('status', 'Menunggu')
            ->with(['poli', 'user'])
            ->latest()
            ->get();

        return response()->json([
            'stats' => $stats,
            'data'  => $pending
        ]);
    }
}
