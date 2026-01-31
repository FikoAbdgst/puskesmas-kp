<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function create()
    {
        $polis = Poli::all();
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

        // Menghitung jumlah pendaftar hari ini untuk menentukan nomor urut
        $countToday = Pendaftaran::where('poli_id', $request->poli_id)
            ->where('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->count();

        // Penomoran otomatis tanpa batasan kuota
        $prefix = strtoupper(substr($poli->nama_poli, 0, 1));
        $nomorUrut = str_pad($countToday + 1, 2, '0', STR_PAD_LEFT);
        $nomorAntrian = $prefix . '-' . $nomorUrut;

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'poli_id' => $request->poli_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'nomor_antrian' => $nomorAntrian,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('home')->with('success', 'Booking berhasil! Nomor Antrian Anda: ' . $nomorAntrian);
    }

    public function riwayat()
    {
        return view('pasien.riwayat');
    }

    public function getRiwayatJson()
    {
        $riwayat = Pendaftaran::where('user_id', Auth::id())
            ->with('poli') // Memastikan relasi poli dimuat
            ->latest()
            ->get()
            ->map(function ($item) {
                Carbon::setLocale('id');
                return [
                    'id' => $item->id,
                    'poli_id' => $item->poli_id,
                    'nama_poli' => $item->poli ? $item->poli->nama_poli : 'Poli Tidak Ditemukan',
                    'nomor_antrian' => $item->nomor_antrian,
                    'status' => $item->status,
                    'keluhan' => $item->keluhan,
                    'tanggal_kunjungan' => $item->tanggal_kunjungan,
                    'tanggal_formatted' => Carbon::parse($item->tanggal_kunjungan)->translatedFormat('l, d M Y'),
                ];
            });

        return response()->json($riwayat);
    }

    public function getLiveAntrianJson($poli_id)
    {
        $current = Pendaftaran::where('poli_id', $poli_id)
            ->where('tanggal_kunjungan', date('Y-m-d'))
            ->where('status', 'Dipanggil')
            ->first();

        return response()->json([
            'nomor_sekarang' => $current ? $current->nomor_antrian : '--'
        ]);
    }
}
