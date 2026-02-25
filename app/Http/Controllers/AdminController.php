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
            'pending'      => Pendaftaran::where('status', 'Menunggu Verifikasi')->count(),
            'verified'     => Pendaftaran::where('status', 'Terverifikasi')->where('tanggal_kunjungan', $today)->count(),
            'done'         => Pendaftaran::where('status', 'Selesai')->where('tanggal_kunjungan', $today)->count(),
        ];

        // 1. Pending HARI INI (Prioritas Utama)
        $pending_today = Pendaftaran::where('status', 'Menunggu Verifikasi')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])
            ->orderBy('created_at', 'asc') // Urutkan jam daftar
            ->get();

        // 2. Pending HARI LAIN (Booking Masa Depan)
        $pending_future = Pendaftaran::where('status', 'Menunggu Verifikasi')
            ->where('tanggal_kunjungan', '>', $today)
            ->with(['poli', 'user'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->get();

        // 3. Terverifikasi (Monitoring)
        // Gabung saja tapi urutkan dari hari ini ke depan
        $verified_all = Pendaftaran::where('status', 'Terverifikasi')
            ->where('tanggal_kunjungan', '>=', $today)
            ->with(['poli', 'user'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'pending_today', 'pending_future', 'verified_all', 'isOpen'));
    }

    public function pelayanan()
    {
        $today = date('Y-m-d');
        $polis = Poli::all();

        // Pasien yang sedang diperiksa (Status: Dipanggil)
        $sedang_diperiksa = Pendaftaran::where('status', 'Dipanggil')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])->get();

        // Antrean yang SUDAH diverifikasi admin (Status: Terverifikasi) dan siap dipanggil
        $antrean_menunggu = Pendaftaran::where('status', 'Terverifikasi')
            ->where('tanggal_kunjungan', $today)
            ->with(['poli', 'user'])
            ->orderBy('id', 'asc') // Urutkan berdasarkan ID/Urutan verifikasi
            ->get();

        return view('admin.pelayanan', compact('sedang_diperiksa', 'antrean_menunggu', 'polis'));
    }

    public function verifikasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Cek jika sudah diverifikasi sebelumnya agar nomor tidak double
        if ($pendaftaran->status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Data ini sudah diproses sebelumnya.');
        }

        // --- LOGIKA PEMBUATAN NOMOR ANTRIAN ---
        $poli = Poli::find($pendaftaran->poli_id);
        $prefix = strtoupper(substr($poli->nama_poli, 0, 1));

        $lastRegistration = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
            ->where('tanggal_kunjungan', $pendaftaran->tanggal_kunjungan)
            ->whereNotNull('nomor_antrian')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRegistration) {
            $lastNumber = (int) explode('-', $lastRegistration->nomor_antrian)[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorAntrian = $prefix . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        // ----------------------------------------------------------------------------------

        // Update Data Default ke Terverifikasi
        $pendaftaran->nomor_antrian = $nomorAntrian;
        $pendaftaran->status = 'Terverifikasi';
        $pendaftaran->save();

        // --- TAMBAHAN LOGIKA AUTO-PANGGIL ---
        // Cek apakah puskesmas BUKA dan pasien mendaftar untuk HARI INI
        $isOpen = Setting::where('key', 'is_open')->first()->value ?? '0';
        $today = date('Y-m-d');

        if ($isOpen == '1' && $pendaftaran->tanggal_kunjungan == $today) {
            // Cek apakah dokter di poli tersebut sedang kosong (tidak ada pasien 'Dipanggil')
            $sedangDiperiksa = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
                ->where('tanggal_kunjungan', $today)
                ->where('status', 'Dipanggil')
                ->exists();

            // Jika dokter kosong, langsung ubah status pasien pertama ini menjadi 'Dipanggil'
            if (!$sedangDiperiksa) {
                $pendaftaran->status = 'Dipanggil';
                $pendaftaran->save();
            }
        }

        return back()->with('success', 'Pendaftaran diverifikasi. Nomor Antrian Dibuat: ' . $nomorAntrian);
    }

    public function tolak($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => 'Ditolak']);

        return back()->with('success', 'Pendaftaran telah ditolak.');
    }

    public function prosesPeriksa(Request $request, $id)
    {
        $request->validate(['catatan_medis' => 'required']);
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Cari dokter yang bertugas di poli tempat pasien mendaftar
        $dokter = Dokter::where('poli_id', $pendaftaran->poli_id)->first();

        // Update status, catatan medis, dan masukkan ID dokternya
        $pendaftaran->update([
            'status' => 'Selesai',
            'catatan_medis' => $request->catatan_medis,
            'dokter_id' => $dokter ? $dokter->id : null // Tambahkan baris ini
        ]);

        // Auto call next patient logic (tetap sama)
        $isOpen = Setting::where('key', 'is_open')->first()->value ?? '0';
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

        return back()->with('success', 'Pemeriksaan selesai.');
    }

    public function toggleOpen()
    {
        // Logika toggle open tetap sama
        $setting = Setting::where('key', 'is_open')->first();
        $today = date('Y-m-d');

        if ($setting->value == '1') {
            $masihAdaPasien = Pendaftaran::where('tanggal_kunjungan', $today)
                ->where('status', 'Dipanggil')
                ->exists();

            if ($masihAdaPasien) {
                return back()->with('error', 'Gagal menutup! Masih ada pasien diperiksa.');
            }
        }

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
                // Ambil pasien Terverifikasi pertama untuk dipanggil
                $next = Pendaftaran::where('poli_id', $poli->id)
                    ->where('tanggal_kunjungan', $today)
                    ->where('status', 'Terverifikasi')
                    ->orderBy('nomor_antrian', 'asc')
                    ->first();

                if ($next) {
                    $next->update(['status' => 'Dipanggil']);
                }
            }
        }
    }
    public function laporan(\Illuminate\Http\Request $request)
    {
        // 1. Ambil filter bulan & tahun dari Request (Default ke bulan & tahun saat ini)
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Query data berdasarkan bulan dan tahun yang dipilih
        $laporan = \App\Models\Pendaftaran::where('status', 'Selesai')
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->with(['user', 'poli', 'dokter'])
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();

        // 3. Validasi apakah tombol cetak boleh diaktifkan
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');
        $bulanPilih = (int) $bulan;
        $tahunPilih = (int) $tahun;

        $bisaDicetak = false;

        // Logika: Hanya bisa cetak jika tahun kurang dari tahun ini,
        // ATAU tahunnya sama tapi bulannya kurang dari bulan ini.
        if ($tahunPilih < $currentYear) {
            $bisaDicetak = true;
        } elseif ($tahunPilih == $currentYear && $bulanPilih < $currentMonth) {
            $bisaDicetak = true;
        }

        return view('admin.laporan', compact('laporan', 'bulan', 'tahun', 'bisaDicetak'));
    }
}
