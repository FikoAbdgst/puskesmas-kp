<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('poli')->get();
        $polis = Poli::all(); // Untuk dropdown di form tambah/edit
        return view('admin.dokter.index', compact('dokters', 'polis'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_dokter' => 'required|string',
            'poli_id' => 'required|exists:polis,id',
            'jadwal_praktek' => 'required|string'
        ]);

        // Gunakan validatedData
        Dokter::create($validatedData);
        return back()->with('success', 'Data Dokter berhasil ditambahkan');
    }

    public function update(Request $request, Dokter $dokter)
    {
        $validatedData = $request->validate([
            'nama_dokter' => 'required|string',
            'poli_id' => 'required|exists:polis,id',
            'jadwal_praktek' => 'required|string'
        ]);

        // Gunakan validatedData
        $dokter->update($validatedData);
        return back()->with('success', 'Data Dokter berhasil diperbarui');
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->delete();
        return back()->with('success', 'Data Dokter berhasil dihapus');
    }
    public function jadwalPublik()
    {
        // Mengambil data dokter beserta nama polinya
        $dokters = Dokter::with('poli')->get();
        return view('pasien.jadwal', compact('dokters'));
    }
}
