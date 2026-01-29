<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.poli.index', compact('polis'));
    }

    public function store(Request $request)
    {
        // 1. Simpan hasil validasi ke variabel
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        // 2. Gunakan $validatedData (otomatis membuang _token)
        Poli::create($validatedData);

        return back()->with('success', 'Data Poli berhasil ditambahkan');
    }

    public function update(Request $request, Poli $poli)
    {
        // 1. Simpan hasil validasi
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        // 2. Update menggunakan data valid
        $poli->update($validatedData);

        return back()->with('success', 'Data Poli berhasil diperbarui');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return back()->with('success', 'Data Poli berhasil dihapus');
    }
}
