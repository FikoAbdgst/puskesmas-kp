<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function jadwalDokter()
    {
        $dokters = \App\Models\Dokter::with('poli')->get();
        // UBAH DARI 'publik.jadwal' KE 'pasien.jadwal'
        return view('pasien.jadwal', compact('dokters'));
    }

    public function infoPoli()
    {
        $polis = \App\Models\Poli::all();
        // UBAH DARI 'publik.poli' KE 'pasien.poli'
        return view('pasien.poli', compact('polis'));
    }
}
