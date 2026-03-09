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
            $search = mb_strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(asal_sekolah_universitas) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jurusan) LIKE ?', ["%{$search}%"]);
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

        $stats = (clone $query)->selectRaw("
                count(*) as total_peserta,
                count(case when id in (select peserta_id from penilaian) then 1 end) as sudah_dinilai
            ")->first();

        $totalPeserta = $stats->total_peserta;
        $sudahDinilai = $stats->sudah_dinilai;
        $belumDinilai = $totalPeserta - $sudahDinilai;
        
        $rataRataNilai = Penilaian::whereIn('peserta_id', (clone $query)->select('id'))->avg('nilai_akhir') ?? 0;

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
     * Menampilkan halaman form penilaian (create/edit)
     */
    public function form($peserta_id)
    {
        $peserta = Peserta::findOrFail($peserta_id);
        
        $kategoris = \App\Models\KategoriPenilaian::where('peserta_id', $peserta_id)
            ->orderBy('nama')
            ->get();
            
        $penilaian = Penilaian::with('details')->where('peserta_id', $peserta_id)->first();

        return view('admin.penilaian.form', compact('peserta', 'kategoris', 'penilaian'));
    }

    /**
     * Menyalin kriteria standar ke peserta yang belum memiliki kriteria
     */
    public function copyDefaultKriteria(Request $request, $peserta_id)
    {
        $peserta = Peserta::findOrFail($peserta_id);
        
        $defaults = [
            ['nama' => 'Kedisiplinan', 'deskripsi' => 'Ketepatan waktu dan kepatuhan aturan'],
            ['nama' => 'Keterampilan', 'deskripsi' => 'Kualitas teknis hasil pekerjaan'],
            ['nama' => 'Kerjasama', 'deskripsi' => 'Kemampuan bekerja dalam tim'],
            ['nama' => 'Inisiatif', 'deskripsi' => 'Keaktifan dalam memberikan ide/solusi'],
            ['nama' => 'Komunikasi', 'deskripsi' => 'Kemampuan menyampaikan informasi'],
        ];

        foreach ($defaults as $data) {
            \App\Models\KategoriPenilaian::firstOrCreate([
                'peserta_id' => $peserta_id,
                'nama' => $data['nama']
            ], [
                'deskripsi' => $data['deskripsi']
            ]);
        }

        return redirect()->back()->with('success', 'Kriteria standar berhasil diterapkan untuk peserta ini.');
    }

    /**
     * Menyimpan penilaian baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'kategori'   => 'required|array',
            'kategori.*' => 'required|integer|min:0|max:100',
            'catatan'    => 'nullable|string|max:1000',
        ]);

        $existing = Penilaian::where('peserta_id', $request->peserta_id)->first();
        if ($existing) {
            return redirect()->route('admin.penilaian.index')
                ->with('error', 'Peserta sudah memiliki penilaian. Gunakan fitur edit untuk mengubah nilai.');
        }

        $penilaian = Penilaian::create([
            'peserta_id'  => $request->peserta_id,
            'user_id'     => Auth::id(),
            'nilai_akhir' => 0,
            'catatan'     => $request->catatan,
        ]);

        foreach ($request->kategori as $kategori_id => $nilai) {
            \App\Models\PenilaianDetail::create([
                'penilaian_id'          => $penilaian->id,
                'kategori_penilaian_id' => $kategori_id,
                'nilai'                 => $nilai,
            ]);
        }

        $penilaian->hitungNilaiAkhirLagi();

        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian peserta berhasil disimpan!');
    }

    /**
     * Mengupdate penilaian yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);

        $request->validate([
            'kategori'   => 'required|array',
            'kategori.*' => 'required|integer|min:0|max:100',
            'catatan'    => 'nullable|string|max:1000',
        ]);

        $penilaian->update([
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        foreach ($request->kategori as $kategori_id => $nilai) {
            \App\Models\PenilaianDetail::updateOrCreate(
                [
                    'penilaian_id' => $penilaian->id, 
                    'kategori_penilaian_id' => $kategori_id
                ],
                ['nilai' => $nilai]
            );
        }

        $penilaian->hitungNilaiAkhirLagi();

        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian peserta berhasil diperbarui!');
    }

    /**
     * Mendapatkan data peserta untuk grid (AJAX)
     */
    public function getPesertaGrid(Request $request)
    {
        $query = Peserta::with(['penilaian', 'user'])
            ->whereIn('status', ['Aktif', 'Selesai']);

        if ($request->filled('search')) {
            $search = mb_strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(asal_sekolah_universitas) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(jurusan) LIKE ?', ["%{$search}%"]);
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

        $stats = (clone $query)->selectRaw("
                count(*) as total_peserta,
                count(case when id in (select peserta_id from penilaian) then 1 end) as sudah_dinilai
            ")->first();

        $totalPeserta = $stats->total_peserta;
        $sudahDinilai = $stats->sudah_dinilai;
        $belumDinilai = $totalPeserta - $sudahDinilai;
        
        $rataRataNilai = Penilaian::whereIn('peserta_id', (clone $query)->select('id'))->avg('nilai_akhir') ?? 0;

        $peserta = $query->with(['penilaian', 'user'])->orderBy('nama', 'asc')->paginate(12)->onEachSide(1);

        return response()->json([
            'html' => view('admin.penilaian.partials.peserta-grid', compact('peserta'))->render(),
            'stats' => [
                'total' => $totalPeserta,
                'sudah' => $sudahDinilai,
                'belum' => $belumDinilai,
                'rata' => round($rataRataNilai)
            ]
        ]);
    }
}
