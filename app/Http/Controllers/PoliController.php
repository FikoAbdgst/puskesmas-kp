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
        // 1. Tambahkan validasi kuota
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'kuota'     => 'required|integer|min:1', // Tambahkan ini
            'deskripsi' => 'nullable|string'
        ]);

        // 2. Data kuota sekarang akan ikut tersimpan secara otomatis
        Poli::create($validatedData);

        return back()->with('success', 'Data Poli berhasil ditambahkan');
    }

    public function update(Request $request, Poli $poli)
    {
        // Lakukan hal yang sama pada fungsi update
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'kuota'     => 'required|integer|min:1', // Tambahkan ini
            'deskripsi' => 'nullable|string'
        ]);

        $poli->update($validatedData);

        return back()->with('success', 'Data Poli berhasil diperbarui');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return back()->with('success', 'Data Poli berhasil dihapus');
    }
}
