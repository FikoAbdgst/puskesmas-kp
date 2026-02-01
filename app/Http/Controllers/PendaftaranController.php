<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Setting;
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
            'poli_id' => 'required|exists:polis,id',
            'keluhan' => 'required',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
        ]);


        Pendaftaran::create([
            'user_id' => Auth::id(),
            'poli_id' => $request->poli_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'nomor_antrian' => null, // Set null, nanti diisi admin saat verifikasi
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu',
        ]);

        // Pesan sukses diubah, tidak lagi menampilkan nomor antrian
        return redirect()->route('home')->with('success', 'Berhasil mendaftar. Mohon tunggu verifikasi admin untuk mendapatkan Nomor Antrian.');
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
