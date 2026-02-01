<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. DATA RIWAYAT (Tetap ada, tapi di bawah)
        $riwayat = Pendaftaran::where('user_id', Auth::id())
            ->with('poli')
            ->latest()
            ->get();

        // 2. DATA LIVE ANTREAN (Untuk Tampilan Atas)
        // Ambil semua poli, lalu cek siapa yang sedang 'Dipanggil' hari ini
        $live_antrian = Poli::all()->map(function ($poli) {
            $current = Pendaftaran::where('poli_id', $poli->id)
                ->where('tanggal_kunjungan', date('Y-m-d'))
                ->where('status', 'Dipanggil') // Hanya yang statusnya Dipanggil
                ->first();

            $poli->nomor_sekarang = $current ? $current->nomor_antrian : '--';
            return $poli;
        });

        return view('home', compact('riwayat', 'live_antrian'));
    }
}
