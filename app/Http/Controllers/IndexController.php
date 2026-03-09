<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Absensi;
use App\Models\Laporan;
use App\Models\Feedback;
use App\Models\Partner;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        try {
            // Konsolidasi statistik dasar (Peserta & Laporan)
            $baseStats = \Illuminate\Support\Facades\DB::table('peserta')
                ->selectRaw("
                    count(*) as total_peserta,
                    count(case when status = 'Aktif' then 1 end) as peserta_aktif
                ")
                ->first();

            $totalPeserta = $baseStats->total_peserta;
            $pesertaAktif = $baseStats->peserta_aktif;

            $laporanStats = Laporan::selectRaw("
                    count(*) as total_laporan,
                    count(case when status = 'Dikirim' then 1 end) as laporan_masuk
                ")
                ->first();

            $totalLaporan = $laporanStats->total_laporan;
            $laporanMasuk = $laporanStats->laporan_masuk;

            $startDate = Carbon::now()->subDays(30);
            $totalAbsensiHadir = Absensi::where('status', 'Hadir')
                ->where('waktu_absen', '>=', $startDate)
                ->distinct()
                ->count('peserta_id');

            $tingkatKehadiran = $pesertaAktif > 0
                ? min(100, round(($totalAbsensiHadir / $pesertaAktif) * 100))
                : 0;

            $feedbackStats = Feedback::selectRaw("
                    count(case when dibaca = 1 then 1 end) as feedback_selesai,
                    avg(case when tampilkan = 1 and rating is not null then rating end) as average_rating,
                    count(case when tampilkan = 1 and rating is not null then 1 end) as total_reviews
                ")
                ->first();

            $feedbackSelesai = $feedbackStats->feedback_selesai;
            $averageRating = round($feedbackStats->average_rating ?? 0, 1);
            $totalReviews = $feedbackStats->total_reviews;

            $laporanProgress = $pesertaAktif > 0 ? round(($laporanMasuk / $pesertaAktif) * 100) : 0;
            $feedbackProgress = $pesertaAktif > 0 ? round(($feedbackSelesai / $pesertaAktif) * 100) : 0;

            $recentAbsensi = Absensi::with('peserta:id,nama')
                ->latest('waktu_absen')
                ->first();

            $recentLaporan = Laporan::with('peserta:id,nama')
                ->latest('tanggal_laporan')
                ->first();

            $feedbacks = Feedback::with('peserta:id,nama,jenis_kegiatan,foto')
                ->where('tampilkan', true)
                ->where('pengirim', 'Peserta')
                ->latest()
                ->limit(20)
                ->get();

            if ($feedbacks->count() > 0 && $feedbacks->count() < 10) {
                $original = $feedbacks->values();
                while ($feedbacks->count() < 10) {
                    $feedbacks = $feedbacks->concat($original->take(10 - $feedbacks->count()));
                }
            }

            $partners = Partner::latest()->get();

            if ($partners->count() > 0 && $partners->count() < 10) {
                $original = $partners->values();
                while ($partners->count() < 10) {
                    $partners = $partners->concat($original->take(10 - $partners->count()));
                }
            }

            return view('index', compact(
                'totalPeserta',
                'totalLaporan',
                'tingkatKehadiran',
                'pesertaAktif',
                'laporanMasuk',
                'feedbackSelesai',
                'laporanProgress',
                'feedbackProgress',
                'recentAbsensi',
                'recentLaporan',
                'feedbacks',
                'averageRating',
                'totalReviews',
                'partners'
            ));
        } catch (\Exception $e) {
            return view('index', [
                'totalPeserta' => 0,
                'totalLaporan' => 0,
                'tingkatKehadiran' => 0,
                'pesertaAktif' => 0,
                'laporanMasuk' => 0,
                'feedbackSelesai' => 0,
                'laporanProgress' => 0,
                'feedbackProgress' => 0,
                'recentAbsensi' => null,
                'recentLaporan' => null,
                'feedbacks' => collect(),
                'averageRating' => 0,
                'totalReviews' => 0,
                'partners' => collect(),
            ]);
        }
    }
}
