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
        // Ubah 'required' menjadi 'nullable' karena input kuota tidak ada di view
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'kuota'     => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string'
        ]);

        Poli::create($validatedData);

        return back()->with('success', 'Data Poli berhasil ditambahkan');
    }

    public function update(Request $request, Poli $poli)
    {
        // Sesuaikan juga fungsi update agar tidak error saat simpan perubahan
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'kuota'     => 'nullable|integer|min:1',
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
