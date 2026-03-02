<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    /**
     * Tampilkan daftar peserta yang sedang diarsipkan.
     * Lengkap dengan sisa hari sebelum dihapus otomatis.
     */
    public function index(Request $request)
    {
        $query = Peserta::with(['arsip', 'user'])
            ->where('status', 'Arsip')
            ->latest();

        if ($request->filled('search')) {
            $search = mb_strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(asal_sekolah_universitas) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(jenis_kegiatan) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        $pesertaArsip = $query->paginate(12)->withQueryString();

        $cutoffDate = Carbon::today()->subMonth();
        $pesertaArsip->each(function ($peserta) use ($cutoffDate) {
            $diarsipkan = $peserta->arsip?->diarsipkan_pada
                ? Carbon::parse($peserta->arsip->diarsipkan_pada)
                : null;

            if ($diarsipkan) {
                $hapusPada = $diarsipkan->copy()->addMonth();
                $peserta->sisa_hari = max(0, Carbon::today()->diffInDays($hapusPada, false));
                $peserta->tanggal_hapus = $hapusPada->toDateString();
            } else {
                $peserta->sisa_hari  = null;
                $peserta->tanggal_hapus = null;
            }
        });

        $totalArsip      = Peserta::where('status', 'Arsip')->count();
        $akanDihapus     = Arsip::where('diarsipkan_pada', '<=', Carbon::today()->subMonth()->addWeek())
                                ->count();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.arsip.partials.arsip-table', compact('pesertaArsip'))->render(),
            ]);
        }

        return view('admin.arsip.index', compact('pesertaArsip', 'totalArsip', 'akanDihapus'));
    }

    /**
     * Pulihkan peserta arsip kembali ke status Selesai.
     */
    public function pulihkan($id)
    {
        $peserta = Peserta::where('status', 'Arsip')->findOrFail($id);

        DB::beginTransaction();
        try {
            $peserta->update(['status' => 'Selesai']);

            if ($peserta->arsip) {
                $peserta->arsip->delete();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Peserta {$peserta->nama} berhasil dipulihkan ke status Selesai.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal pulihkan arsip', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memulihkan data.',
            ], 500);
        }
    }

    /**
     * Hapus permanen peserta dari arsip sekarang juga (tidak tunggu 1 bulan).
     */
    public function destroy($id)
    {
        $peserta = Peserta::with(['arsip', 'laporans', 'absensis', 'feedbacks', 'penilaian'])
                          ->where('status', 'Arsip')
                          ->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($peserta->laporans as $laporan) {
                if ($laporan->file_path && Storage::disk('private')->exists($laporan->file_path)) {
                    Storage::disk('private')->delete($laporan->file_path);
                }
            }

            if ($peserta->foto && Storage::disk('public')->exists($peserta->foto)) {
                Storage::disk('public')->delete($peserta->foto);
            }

            $peserta->absensis()->delete();
            $peserta->laporans()->delete();
            $peserta->feedbacks()->delete();
            if ($peserta->penilaian) $peserta->penilaian->delete();
            $peserta->laporanAkhir()->delete();
            if ($peserta->arsip) $peserta->arsip->delete();

            $userId = $peserta->user_id;
            $nama   = $peserta->nama;
            $peserta->delete();

            User::where('id', $userId)->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Data {$nama} berhasil dihapus permanen.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal hapus arsip manual', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
            ], 500);
        }
    }
}
