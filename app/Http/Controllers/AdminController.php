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

    public function verifikasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $isOpen = Setting::where('key', 'is_open')->first()->value;

        // Jika puskesmas buka DAN tidak ada yang sedang dipanggil di poli tersebut
        $isPoliBusy = Pendaftaran::where('poli_id', $pendaftaran->poli_id)
            ->where('tanggal_kunjungan', date('Y-m-d'))
            ->where('status', 'Dipanggil')
            ->exists();

        if ($isOpen == '1' && !$isPoliBusy && $pendaftaran->tanggal_kunjungan == date('Y-m-d')) {
            $pendaftaran->status = 'Dipanggil';
        } else {
            // Jika tutup atau sudah ada pasien di dalam, status jadi Terverifikasi (menunggu giliran)
            $pendaftaran->status = 'Terverifikasi';
        }

        $pendaftaran->save();
        return back()->with('success', 'Pasien berhasil diverifikasi.');
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
                ->orderBy('id', 'asc')
                ->first();

            if ($nextQueue) {
                $nextQueue->update(['status' => 'Dipanggil']);
            }
        }

        return back()->with('success', 'Pemeriksaan selesai.');
    }
}
