<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Dokter;

class AdminController extends Controller
{
    // Dashboard Admin & List Pendaftaran Masuk (Pending)
    public function index()
    {
        $pending = Pendaftaran::where('status', 'pending')->with('user', 'poli')->get();

        // Statistik Sederhana
        $stats = [
            'pending' => $pending->count(),
            'verified' => Pendaftaran::where('status', 'verified')->count(),
            'done' => Pendaftaran::where('status', 'done')->count(),
        ];

        return view('admin.dashboard', compact('pending', 'stats'));
    }

    // Proses Verifikasi (Ubah status pending -> verified)
    public function verifikasi($id)
    {
        $data = Pendaftaran::find($id);
        $data->status = 'verified';
        $data->save();

        return back()->with('success', 'Data Pasien Valid. Masuk antrean pemeriksaan.');
    }

    // Halaman Pemeriksaan Dokter (List Pasien Verified)
    public function pelayanan()
    {
        // Hanya ambil pasien yang sudah diverifikasi
        $antrean = Pendaftaran::where('status', 'verified')->with('user', 'poli')->get();
        return view('admin.pelayanan', compact('antrean'));
    }

    // Proses Simpan Hasil Pemeriksaan (Ubah status verified -> done)
    public function prosesPeriksa(Request $request, $id)
    {
        $data = Pendaftaran::find($id);
        $data->update([
            'status' => 'done',
            'catatan_medis' => $request->catatan_medis,
            'resep_obat' => $request->resep_obat,
        ]);

        return back()->with('success', 'Pemeriksaan Selesai. Data tersimpan.');
    }

    // Halaman Laporan
    public function laporan()
    {
        $laporan = Pendaftaran::where('status', 'done')->with('user', 'poli')->get();
        return view('admin.laporan', compact('laporan'));
    }

    public function getPendingJson()
    {
        $pending = Pendaftaran::where('status', 'pending')
            ->with('user', 'poli')
            ->latest() // Mengambil data terbaru di atas
            ->get();

        $stats = [
            'pending' => $pending->count(),
            'verified' => Pendaftaran::where('status', 'verified')->count(),
            'done' => Pendaftaran::where('status', 'done')->count(),
        ];

        return response()->json([
            'data' => $pending,
            'stats' => $stats
        ]);
    }
}
