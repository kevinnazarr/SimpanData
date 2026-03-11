<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        Peserta::syncArchive();
        $baseQuery = Peserta::with('user')->active()->latest();

        if ($request->filled('search')) {
            $search = mb_strtolower($request->search);
            $baseQuery->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(asal_sekolah_universitas) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jurusan) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->whereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(username) LIKE ?', ["%{$search}%"]);
                    });
            });
        }

        if ($request->filled('jenis_kegiatan')) {
            $baseQuery->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        if ($request->filled('asal_sekolah_universitas')) {
            $baseQuery->where('asal_sekolah_universitas', $request->asal_sekolah_universitas);
        }

        $peserta = (clone $baseQuery)->paginate(9)->onEachSide(1);

        $statsQuery = clone $baseQuery;
        $totalFiltered = (clone $statsQuery)->count();
        $totalPklFiltered = (clone $statsQuery)->where('jenis_kegiatan', 'PKL')->count();
        $totalMagangFiltered = (clone $statsQuery)->where('jenis_kegiatan', 'Magang')->count();
        $totalAktifFiltered = (clone $statsQuery)->where('status', 'Aktif')->count();
        $totalSelesaiFiltered = (clone $statsQuery)->where('status', 'Selesai')->count();

        if ($request->ajax()) {
            return response()->json([
                'grid' => view('admin.peserta.partials.peserta-grid', compact('peserta'))->render(),
                'stats' => [
                    'total' => $totalFiltered,
                    'pkl' => $totalPklFiltered,
                    'magang' => $totalMagangFiltered,
                    'aktif' => $totalAktifFiltered,
                    'selesai' => $totalSelesaiFiltered,
                ],
            ]);
        }

        $totalPeserta = $totalFiltered;
        $totalPkl = $totalPklFiltered;
        $totalMagang = $totalMagangFiltered;
        $aktif = $totalAktifFiltered;
        $selesai = $totalSelesaiFiltered;

        $sekolahs = Peserta::select('asal_sekolah_universitas')
            ->whereNotNull('asal_sekolah_universitas')
            ->where('asal_sekolah_universitas', '!=', '')
            ->distinct()
            ->orderBy('asal_sekolah_universitas')
            ->get();

        return view('admin.peserta.index', compact(
            'peserta',
            'totalPeserta',
            'totalPkl',
            'totalMagang',
            'aktif',
            'selesai',
            'sekolahs'
        ));
    }

    public function create()
    {
        if (request()->ajax()) {
            $html = view('admin.peserta.partials.modal-create')->render();
            return response()->json(['html' => $html]);
        }

        return redirect()->route('admin.peserta.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:user,username|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|string|min:8',
            'nama' => 'required|string|max:255',
            'asal_sekolah_universitas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tugas' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kegiatan' => 'required|in:PKL,Magang',
            'status' => 'required|in:Aktif,Selesai,Arsip',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nim_nis' => 'nullable|string|max:255',
            'tugas' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'id' => \App\Helpers\IdGenerator::generate('peserta', $validated['jenis_kegiatan']),
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'peserta',
                'photo_profile' => $request->hasFile('foto') ? $request->file('foto')->store('peserta/foto', 'public') : null
            ]);

            Peserta::create([
                'id' => $user->id,
                'nama' => $validated['nama'],
                'asal_sekolah_universitas' => $validated['asal_sekolah_universitas'],
                'jurusan' => $validated['jurusan'],
                'tugas' => $validated['tugas'] ?? null,
                'alamat' => $validated['alamat'],
                'no_telepon' => $validated['no_telepon'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'status' => $validated['status'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'nim_nis' => $validated['nim_nis'] ?? null,
                'tugas' => $validated['tugas'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Peserta store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data peserta.',
                'errors' => []
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $peserta = Peserta::with(['user', 'penilaian', 'laporanAkhir'])->findOrFail($id);
        
        $absensis = $peserta->absensis()->orderBy('waktu_absen', 'desc')->paginate(10, ['*'], 'absensi_page');
        $laporans = $peserta->laporans()->orderBy('tanggal_laporan', 'desc')->paginate(5, ['*'], 'laporan_page');
        $feedbacks = $peserta->feedbacks()->where('pengirim', 'Peserta')->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            if ($request->has('absensi_page')) {
                return response()->json([
                    'html' => view('admin.peserta.partials.absensi-rows', compact('absensis'))->render(),
                    'pagination' => $absensis->appends(['laporan_page' => $request->laporan_page])->links()->toHtml()
                ]);
            }
            if ($request->has('laporan_page')) {
                return response()->json([
                    'html' => view('admin.peserta.partials.laporan-rows', compact('laporans'))->render(),
                    'pagination' => $laporans->appends(['absensi_page' => $request->absensi_page])->links()->toHtml()
                ]);
            }
        }

        return view('admin.peserta.show', compact('peserta', 'absensis', 'laporans', 'feedbacks'));
    }

    public function edit($id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);

        if (request()->ajax()) {
            $html = view('admin.peserta.partials.modal-edit', compact('peserta'))->render();
            return response()->json(['html' => $html]);
        }

        return redirect()->route('admin.peserta.index');
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|unique:user,username,' . $peserta->id,
            'email' => 'required|email|unique:user,email,' . $peserta->id,
            'password' => 'nullable|string|min:8',
            'nama' => 'required|string|max:255',
            'asal_sekolah_universitas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tugas' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kegiatan' => 'required|in:PKL,Magang',
            'status' => 'required|in:Aktif,Selesai,Arsip',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nim_nis' => 'nullable|string|max:255',
            'tugas' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email']
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $peserta->user->update($userData);

            if ($request->hasFile('foto')) {
                $peserta->user->updateProfilePhoto($request->file('foto'), 'peserta/foto');
            }

            $peserta->update([
                'nama' => $validated['nama'],
                'asal_sekolah_universitas' => $validated['asal_sekolah_universitas'],
                'jurusan' => $validated['jurusan'],
                'tugas' => $validated['tugas'] ?? null,
                'alamat' => $validated['alamat'],
                'no_telepon' => $validated['no_telepon'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'status' => $validated['status'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'nim_nis' => $validated['nim_nis'] ?? null,
                'tugas' => $validated['tugas'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Peserta update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data peserta.',
                'errors' => []
            ], 500);
        }
    }

    public function destroy($id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($peserta->laporans as $laporan) {
                if ($laporan->file_path && Storage::disk('public')->exists($laporan->file_path)) {
                    Storage::disk('public')->delete($laporan->file_path);
                }
                $laporan->delete();
            }

            $peserta->absensis()->delete();

            $peserta->feedbacks()->delete();
            
            if ($peserta->penilaian) {
                $peserta->penilaian->delete();
            }

            if ($peserta->arsip) {
                $peserta->arsip->delete();
            }

            $peserta->user->deleteProfilePhoto();

            $peserta->delete();
            $peserta->user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printIdCard($id)
    {
        $peserta = Peserta::findOrFail($id);

        if (request()->has('download')) {
            $pdf = Pdf::loadView('admin.peserta.print-id-card', [
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
}
