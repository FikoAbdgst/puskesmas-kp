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

    // app/Http/Controllers/PendaftaranController.php

    public function store(Request $request)
    {
        $request->validate([
            'poli_id' => 'required|exists:polis,id',
            'keluhan' => 'required',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
        ]);

        $tanggalKunjungan = $request->tanggal_kunjungan;
        $today = date('Y-m-d');

        // Cek operasional jika daftar untuk hari ini
        if ($tanggalKunjungan == $today) {
            $isOpen = Setting::where('key', 'is_open')->first()->value ?? '0';
            if ($isOpen == '0') {
                return back()->with('error', 'Maaf, pendaftaran untuk hari ini sudah ditutup.');
            }
        }

        // AMBIL PREFIX DARI NAMA POLI (Contoh: "Poli Umum" -> "U")
        $poli = Poli::find($request->poli_id);
        $prefix = strtoupper(substr($poli->nama_poli, 0, 1));

        // CARI ANTRIAN TERAKHIR DI POLI TERSEBUT PADA TANGGAL TERSEBUT
        $lastRegistration = Pendaftaran::where('poli_id', $request->poli_id)
            ->where('tanggal_kunjungan', $tanggalKunjungan)
            ->orderBy('id', 'desc') // Menggunakan ID untuk akurasi urutan pendaftaran
            ->first();

        if ($lastRegistration) {
            // Ambil angka dari nomor antrian terakhir (Contoh: "U-05" -> 5)
            // explode('-') memecah string berdasarkan tanda pisah
            $lastNumber = (int) explode('-', $lastRegistration->nomor_antrian)[1];
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika tidak ada pendaftar sebelumnya di tanggal & poli tersebut, mulai dari 1
            $nextNumber = 1;
        }

        $nomorAntrian = $prefix . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'poli_id' => $request->poli_id,
            'tanggal_kunjungan' => $tanggalKunjungan,
            'nomor_antrian' => $nomorAntrian,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('home')->with('success', 'Berhasil mendaftar. Nomor Antrian Anda: ' . $nomorAntrian);
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
