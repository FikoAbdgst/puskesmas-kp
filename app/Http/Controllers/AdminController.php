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

        // Memisahkan Booking Hari Ini dan Hari Lain
        $pending_today = Pendaftaran::where('status', 'Menunggu')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])->latest()->get();

        $pending_others = Pendaftaran::where('status', 'Menunggu')
            ->where('tanggal_kunjungan', '>', $today)
            ->with(['poli', 'user'])->latest()->get();

        return view('admin.dashboard', compact('stats', 'pending_today', 'pending_others', 'isOpen'));
    }

    // --- PERBAIKAN: Method pelayanan ditambahkan di sini ---
    public function pelayanan()
    {
        // Mengambil data untuk halaman /admin/pelayanan
        $pending = Pendaftaran::where('status', 'Menunggu')
            ->with(['poli', 'user'])
            ->orderBy('tanggal_kunjungan', 'asc') // Urutkan berdasarkan tanggal kunjungan terdekat
            ->get();

        $periksa = Pendaftaran::where('status', 'Dipanggil')
            ->with(['poli', 'user'])
            ->get();

        return view('admin.pelayanan', compact('pending', 'periksa'));
    }

    public function toggleOpen()
    {
        $setting = Setting::where('key', 'is_open')->first();
        $newState = ($setting->value == '1') ? '0' : '1';
        $setting->update(['value' => $newState]);

        // Jika baru dibuka, otomatis panggil urutan pertama di setiap poli yang sudah terverifikasi
        if ($newState == '1') {
            $this->panggilAntreanPertamaSemuaPoli();
        }

        return back()->with('success', 'Status operasional berhasil diubah.');
    }

    private function panggilAntreanPertamaSemuaPoli()
    {
        $polis = Poli::all();
        foreach ($polis as $poli) {
            $isAnyBusy = Pendaftaran::where('poli_id', $poli->id)
                ->where('tanggal_kunjungan', date('Y-m-d'))
                ->where('status', 'Dipanggil')
                ->exists();

            if (!$isAnyBusy) {
                $next = Pendaftaran::where('poli_id', $poli->id)
                    ->where('tanggal_kunjungan', date('Y-m-d'))
                    ->where('status', 'Terverifikasi')
                    ->orderBy('id', 'asc')
                    ->first();

                if ($next) {
                    $next->update(['status' => 'Dipanggil']);
                }
            }
        }
    }

    // --- UPDATE: Verifikasi dengan Generate Nomor Antrian ---
    public function verifikasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // 1. Generate Nomor Antrian jika belum ada
        if ($pendaftaran->nomor_antrian == null) {
            // Hitung jumlah pasien yang SUDAH diverifikasi (punya nomor) pada hari & poli tersebut
            $count = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
                ->where('tanggal_kunjungan', $pendaftaran->tanggal_kunjungan)
                ->whereNotNull('nomor_antrian')
                ->count();

            $poli = Poli::find($pendaftaran->poli_id);
            // Ambil huruf depan nama poli (misal: Umum -> U)
            $prefix = strtoupper(substr($poli->nama_poli, 0, 1));
            // Format nomor: U-01, U-02, dst.
            $nomorAntrian = $prefix . '-' . str_pad($count + 1, 2, '0', STR_PAD_LEFT);

            $pendaftaran->nomor_antrian = $nomorAntrian;
        }

        // 2. Tentukan Status (Dipanggil atau Terverifikasi)
        $isOpen = Setting::where('key', 'is_open')->first()->value;

        // Cek apakah poli sedang sibuk hari ini
        $isPoliBusy = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
            ->where('tanggal_kunjungan', date('Y-m-d'))
            ->where('status', 'Dipanggil')
            ->exists();

        // Jika Buka DAN Poli Kosong DAN Tanggal Kunjungan adalah Hari Ini -> Langsung Panggil
        if ($isOpen == '1' && !$isPoliBusy && $pendaftaran->tanggal_kunjungan == date('Y-m-d')) {
            $pendaftaran->status = 'Dipanggil';
        } else {
            // Selain itu masuk antrian (Terverifikasi)
            $pendaftaran->status = 'Terverifikasi';
        }

        $pendaftaran->save();
        return back()->with('success', 'Pasien diverifikasi. No Antrian: ' . $pendaftaran->nomor_antrian);
    }

    // --- TAMBAHAN: Method Tolak ---
    public function tolak($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'Ditolak'
        ]);

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }

    public function prosesPeriksa(Request $request, $id)
    {
        $request->validate(['catatan_medis' => 'required']);
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'Selesai',
            'catatan_medis' => $request->catatan_medis
        ]);

        // Panggil antrean berikutnya HANYA jika puskesmas masih buka
        $isOpen = Setting::where('key', 'is_open')->first()->value;
        if ($isOpen == '1') {
            $nextQueue = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
                ->where('tanggal_kunjungan', date('Y-m-d'))
                ->where('status', 'Terverifikasi')
                ->orderBy('id', 'asc') // Panggil berdasarkan ID (siapa yang daftar duluan yang valid)
                ->first();

            if ($nextQueue) {
                $nextQueue->update(['status' => 'Dipanggil']);
            }
        }

        return back()->with('success', 'Pemeriksaan selesai.');
    }
}
