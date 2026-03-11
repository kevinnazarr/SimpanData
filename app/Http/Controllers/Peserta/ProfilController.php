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

        if (request()->has('download')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.peserta.print-id-card', [
                'peserta' => $peserta,
                'isPdf' => true
            ])->setPaper([0, 0, 153.07, 242.65], 'portrait');
            
            return $pdf->download('ID_Card_' . str_replace(' ', '_', $peserta->nama) . '.pdf');
        }

        return view('admin.peserta.print-id-card', [
            'peserta' => $peserta,
            'isPdf' => false
        ]);
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
            'nim_nis' => 'nullable|string|max:255',
            'tugas' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
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
            'nim_nis',
            'tugas',
            'latitude',
            'longitude',
            'no_telepon', 
            'alamat'
        ]);
        
        if ($request->hasFile('foto')) {
            $user->updateProfilePhoto($request->file('foto'), 'peserta/foto');
        }

        \App\Models\Peserta::updateOrCreate(
            ['id' => $user->id],
            $data
        );

        return redirect()->route('peserta.profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
