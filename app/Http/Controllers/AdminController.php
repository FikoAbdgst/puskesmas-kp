<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Dokter;

class AdminController extends Controller
{
    public function index()
    {
        // Ubah 'pending' menjadi 'Menunggu'
        $pending = Pendaftaran::where('status', 'Menunggu')->with('user', 'poli')->get();

        $stats = [
            'pending' => $pending->count(),
            'verified' => Pendaftaran::where('status', 'Diterima')->count(), // 'verified' -> 'Diterima'
            'done' => Pendaftaran::where('status', 'Selesai')->count(),     // 'done' -> 'Selesai'
        ];

        return view('admin.dashboard', compact('pending', 'stats'));
    }

    public function verifikasi($id)
    {
        $data = Pendaftaran::findOrFail($id);
        $data->status = 'Diterima'; // Ubah menjadi 'Diterima' (Booking valid dan aktif)
        $data->save();

        return back()->with('success', 'Pendaftaran Diterima. Pasien masuk antrean pemeriksaan.');
    }

    public function pelayanan()
    {
        // Ubah 'verified' menjadi 'Diterima'
        $antrean = Pendaftaran::where('status', 'Diterima')->with('user', 'poli')->get();
        return view('admin.pelayanan', compact('antrean'));
    }

    public function prosesPeriksa(Request $request, $id)
    {
        $data = Pendaftaran::findOrFail($id);
        $data->update([
            'status' => 'Selesai', // Ubah menjadi 'Selesai'
            'catatan_medis' => $request->catatan_medis,
            'resep_obat' => $request->resep_obat,
        ]);

        return back()->with('success', 'Pemeriksaan Selesai.');
    }

    public function getPendingJson()
    {
        // Sesuaikan juga untuk API Dashboard
        $pending = Pendaftaran::where('status', 'Menunggu')->with('user', 'poli')->latest()->get();

        $stats = [
            'pending' => $pending->count(),
            'verified' => Pendaftaran::where('status', 'Diterima')->count(),
            'done' => Pendaftaran::where('status', 'Selesai')->count(),
        ];

        return response()->json(['data' => $pending, 'stats' => $stats]);
    }

    // Halaman Laporan
    public function laporan()
    {
        $laporan = Pendaftaran::where('status', 'done')->with('user', 'poli')->get();
        return view('admin.laporan', compact('laporan'));
    }
}
