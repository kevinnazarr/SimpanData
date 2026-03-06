<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = $user->peserta;
        return view('peserta.profil', compact('user', 'peserta'));
    }

    public function printIdCard()
    {
        $peserta = Auth::user()->peserta;
        if (!$peserta) {
            return redirect()->route('peserta.profil')->with('error', 'Data diri belum lengkap.');
        }
        return view('admin.peserta.print-id-card', compact('peserta'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'asal_sekolah_universitas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:PKL,Magang',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'nama', 
            'asal_sekolah_universitas', 
            'jurusan', 
            'jenis_kegiatan', 
            'tanggal_mulai', 
            'tanggal_selesai', 
            'no_telepon', 
            'alamat'
        ]);
        
        if ($request->hasFile('foto')) {
            $peserta = $user->peserta;
            if ($peserta && $peserta->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($peserta->foto);
            }
            $path = $request->file('foto')->store('peserta', 'public');
            $data['foto'] = $path;
        }

        \App\Models\Peserta::updateOrCreate(
            ['id' => $user->id],
            $data
        );

        return redirect()->route('peserta.profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
