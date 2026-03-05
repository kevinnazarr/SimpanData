<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman penilaian dengan statistik dan daftar peserta
     */
    public function index(Request $request)
    {
        $query = Peserta::with(['penilaian', 'user'])
            ->whereIn('status', ['Aktif', 'Selesai']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('asal_sekolah_universitas', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }
        
        if ($request->filled('sekolah')) {
            $query->where('asal_sekolah_universitas', $request->sekolah);
        }

        if ($request->filled('status_penilaian')) {
            if ($request->status_penilaian === 'sudah') {
                $query->whereHas('penilaian');
            } else {
                $query->whereDoesntHave('penilaian');
            }
        }

        $statsQuery = clone $query;
        $totalPeserta = $statsQuery->count();
        $sudahDinilai = (clone $statsQuery)->whereHas('penilaian')->count();
        $belumDinilai = $totalPeserta - $sudahDinilai;
        $rataRataNilai = Penilaian::whereIn('peserta_id', (clone $statsQuery)->select('id'))->avg('nilai_akhir') ?? 0;

        $peserta = $query->orderBy('nama', 'asc')->paginate(12)->onEachSide(1);

        $nilaiTerbaik = Penilaian::with('peserta')
            ->orderBy('nilai_akhir', 'desc')
            ->first();

        $sekolahs = Peserta::select('asal_sekolah_universitas')
            ->distinct()
            ->orderBy('asal_sekolah_universitas')
            ->get();

        return view('admin.penilaian.index', compact(
            'peserta',
            'totalPeserta',
            'sudahDinilai',
            'belumDinilai',
            'rataRataNilai',
            'nilaiTerbaik',
            'sekolahs'
        ));
    }

    /**
     * Mendapatkan detail penilaian peserta (untuk modal)
     */
    public function show($id)
    {
        $peserta = Peserta::with(['penilaian.user', 'user'])->findOrFail($id);

        return response()->json([
            'peserta' => $peserta,
            'penilaian' => $peserta->penilaian
        ]);
    }

    /**
     * Menyimpan penilaian baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'kedisiplinan' => 'required|integer|min:1|max:100',
            'keterampilan' => 'required|integer|min:1|max:100',
            'kerjasama' => 'required|integer|min:1|max:100',
            'inisiatif' => 'required|integer|min:1|max:100',
            'komunikasi' => 'required|integer|min:1|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $existing = Penilaian::where('peserta_id', $request->peserta_id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah memiliki penilaian. Gunakan fitur update untuk mengubah nilai.'
            ], 422);
        }

        $nilaiAkhir = Penilaian::hitungNilaiAkhir(
            $request->kedisiplinan,
            $request->keterampilan,
            $request->kerjasama,
            $request->inisiatif,
            $request->komunikasi
        );

        $penilaian = Penilaian::create([
            'peserta_id' => $request->peserta_id,
            'user_id' => Auth::id(),
            'kedisiplinan' => $request->kedisiplinan,
            'keterampilan' => $request->keterampilan,
            'kerjasama' => $request->kerjasama,
            'inisiatif' => $request->inisiatif,
            'komunikasi' => $request->komunikasi,
            'nilai_akhir' => $nilaiAkhir,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil disimpan!',
            'data' => $penilaian->load('peserta')
        ]);
    }

    /**
     * Mengupdate penilaian yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);

        $request->validate([
            'kedisiplinan' => 'required|integer|min:1|max:100',
            'keterampilan' => 'required|integer|min:1|max:100',
            'kerjasama' => 'required|integer|min:1|max:100',
            'inisiatif' => 'required|integer|min:1|max:100',
            'komunikasi' => 'required|integer|min:1|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $nilaiAkhir = Penilaian::hitungNilaiAkhir(
            $request->kedisiplinan,
            $request->keterampilan,
            $request->kerjasama,
            $request->inisiatif,
            $request->komunikasi
        );

        $penilaian->update([
            'user_id' => Auth::id(),
            'kedisiplinan' => $request->kedisiplinan,
            'keterampilan' => $request->keterampilan,
            'kerjasama' => $request->kerjasama,
            'inisiatif' => $request->inisiatif,
            'komunikasi' => $request->komunikasi,
            'nilai_akhir' => $nilaiAkhir,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil diperbarui!',
            'data' => $penilaian->load('peserta')
        ]);
    }

    /**
     * Mendapatkan data peserta untuk grid (AJAX)
     */
    public function getPesertaGrid(Request $request)
    {
        $query = Peserta::with(['penilaian', 'user'])
            ->whereIn('status', ['Aktif', 'Selesai']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('asal_sekolah_universitas', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        if ($request->filled('sekolah')) {
            $query->where('asal_sekolah_universitas', $request->sekolah);
        }

        if ($request->filled('status_penilaian')) {
            if ($request->status_penilaian === 'sudah') {
                $query->whereHas('penilaian');
            } else {
                $query->whereDoesntHave('penilaian');
            }
        }

        $statsQuery = clone $query;
        $totalPeserta = $statsQuery->count();
        $sudahDinilai = (clone $statsQuery)->whereHas('penilaian')->count();
        $belumDinilai = $totalPeserta - $sudahDinilai;
        
        $rataRataNilai = Penilaian::whereIn('peserta_id', (clone $statsQuery)->select('id'))->avg('nilai_akhir') ?? 0;

        $peserta = $query->with(['penilaian', 'user'])->orderBy('nama', 'asc')->paginate(12)->onEachSide(1);

        return response()->json([
            'html' => view('admin.penilaian.partials.peserta-grid', compact('peserta'))->render(),
            'stats' => [
                'total' => $totalPeserta,
                'sudah' => $sudahDinilai,
                'belum' => $belumDinilai,
                'rata' => number_format($rataRataNilai, 1)
            ]
        ]);
    }
}
