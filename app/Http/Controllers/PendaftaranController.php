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

    // Proses Simpan Pendaftaran
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'poli_id' => 'required',
            'keluhan' => 'required',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
        ]);

        // Update NIK user jika belum ada
        if (!Auth::user()->nik) {
            Auth::user()->update(['nik' => $request->nik]);
        }

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'poli_id' => $request->poli_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'keluhan' => $request->keluhan,
            'status' => 'pending', // Default status menunggu verifikasi
        ]);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil dikirim! Silakan tunggu verifikasi petugas.');
    }
}
