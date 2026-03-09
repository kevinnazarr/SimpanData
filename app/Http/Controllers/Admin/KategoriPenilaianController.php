<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPenilaian;
use Illuminate\Http\Request;

class KategoriPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriPenilaian::orderBy('nama')->paginate(10);
        return view('admin.kategori-penilaian.index', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'nama' => 'required|string|max:100', // Unik per peserta diatur di level logika jika perlu
            'deskripsi' => 'nullable|string|max:255',
        ]);

        KategoriPenilaian::create([
            'peserta_id' => $request->peserta_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->back()
            ->with('success', 'Kriteria Penilaian berhasil ditambahkan untuk peserta ini.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPenilaian $kategori_penilaian)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategori_penilaian->update($request->only(['nama', 'deskripsi']));

        return redirect()->back()
            ->with('success', 'Kriteria Penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPenilaian $kategori_penilaian)
    {
        $kategori_penilaian->delete();

        return redirect()->back()
            ->with('success', 'Kategori Penilaian berhasil dihapus.');
    }
}
