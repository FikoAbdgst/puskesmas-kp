<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    // Halaman Form Pendaftaran
    public function create()
    {
        $polis = Poli::all(); // Mengambil data poli untuk dropdown
        return view('pasien.daftar', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'poli_id' => 'required|exists:polis,id',
            'keluhan' => 'required',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
        ]);

        $poli = Poli::findOrFail($request->poli_id);

        // Validasi kuota sesuai desain [cite: 9]
        $countBooking = Pendaftaran::where('poli_id', $request->poli_id)
            ->where('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->count();

        if ($countBooking >= $poli->kuota) {
            return back()->with('error', 'Kuota pendaftaran untuk poli ini sudah penuh.');
        }

        // Penomoran otomatis [cite: 11]
        $prefix = strtoupper(substr($poli->nama_poli, 0, 1));
        $nomorUrut = str_pad($countBooking + 1, 2, '0', STR_PAD_LEFT);
        $nomorAntrian = $prefix . '-' . $nomorUrut;

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'poli_id' => $request->poli_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'nomor_antrian' => $nomorAntrian,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu', // PASTIKAN STATUS INI
        ]);

        return redirect()->route('home')->with('success', 'Booking berhasil! Status: Menunggu verifikasi petugas.');
    }
    public function getRiwayatJson()
    {
        $riwayat = Pendaftaran::where('user_id', Auth::id())
            ->with('poli')
            ->latest()
            ->get();
        return response()->json($riwayat);
    }
}
