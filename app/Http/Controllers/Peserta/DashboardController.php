<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $peserta = $user->peserta;
        $filter = $request->get('filter', 'hari');
        $weekFilter = $request->get('week');

        $totalHadir = 0;
        $totalLaporan = 0;
        $absensiHariIni = null;
        $progress = 0;
        $performanceScore = 0;
        $recentActivities = collect();
        $absensiData = [
            'labels' => [],
            'data' => []
        ];
        $availableWeeks = [];
        $totalDays = 0;
        $passedDays = 0;

        if ($peserta) {
            $totalHadir = \App\Models\Absensi::where('peserta_id', $peserta->id)
                ->where('status', 'Hadir')
                ->where('jenis_absen', 'Masuk')
                ->count();

            $totalLaporan = \App\Models\Laporan::where('peserta_id', $peserta->id)->count() + 
                            \App\Models\LaporanAkhir::where('peserta_id', $peserta->id)->count();

            $absensiHariIni = \App\Models\Absensi::where('peserta_id', $peserta->id)
                ->whereDate('waktu_absen', \Carbon\Carbon::today())
                ->first();

            $start = $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai) : \Carbon\Carbon::now()->subMonths(6);
            $end = $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai) : \Carbon\Carbon::now();

            if (!$peserta->tanggal_mulai && $start->diffInMonths($end) > 6) {
                $start = $end->copy()->subMonths(6);
            }
            $start = $start->copy()->startOfWeek();
            $period = \Carbon\CarbonPeriod::create($start, '1 week', $end);

            $weekCounter = 1;
            foreach($period as $date) {
                $weekEnd = $date->copy()->endOfWeek();
                $availableWeeks[] = [
                    'value' => $date->format('Y-m-d'),
                    'label' => "Minggu $weekCounter (" . $date->format('d M') . " - " . $weekEnd->format('d M') . ")"
                ];
                $weekCounter++;
            }

            if (!$weekFilter && count($availableWeeks) > 0) {
                $latestWeek = end($availableWeeks)['value'];
                $weekFilter = $latestWeek;
            }


            if ($peserta->tanggal_mulai && $peserta->tanggal_selesai) {
                $startPkl = $peserta->tanggal_mulai;
                $endPkl = $peserta->tanggal_selesai;

                $totalDays = $startPkl->diffInDays($endPkl) + 1;

                $passedDays = \App\Models\Absensi::where('peserta_id', $peserta->id)
                    ->where('status', 'Hadir')
                    ->where('jenis_absen', 'Masuk')
                    ->distinct()
                    ->count(DB::raw('DATE(waktu_absen)'));

                $progress = $totalDays > 0 ? round(($passedDays / $totalDays) * 100) : 0;
            }

            $absensiData = match($filter) {
                'hari' => $this->getAbsensiDataHariIni($peserta->id),
                'minggu' => $this->getAbsensiDataMingguan($peserta, $weekFilter),
                'bulan' => $this->getAbsensiDataBulanan($peserta),
                default => $this->getAbsensiDataHariIni($peserta->id),
            };

            $recentActivities = \App\Models\Absensi::where('peserta_id', $peserta->id)
                ->latest('waktu_absen')
                ->limit(5)
                ->get();

            if ($peserta->tanggal_mulai) {
                $start = \Carbon\Carbon::parse($peserta->tanggal_mulai);
                $end = $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai) : \Carbon\Carbon::now();
                $refDate = \Carbon\Carbon::now()->min($end);
                $totalExpectedDays = $start->diffInDays($refDate) + 1;
                $attendanceRate = $totalExpectedDays > 0 ? ($totalHadir / $totalExpectedDays) : 0;
            } else {
                $attendanceRate = 0;
            }

            $reportRate = $totalHadir > 0 ? ($totalLaporan / $totalHadir) : 0;
            $reportRate = min($reportRate, 1);

            $performanceScore = round((($attendanceRate * 0.5) + ($reportRate * 0.5)) * 100);
            $performanceScore = min($performanceScore, 100);

            $attendanceStats = \App\Models\Absensi::where('peserta_id', $peserta->id)
                ->where(function($query) {
                    $query->where('status', '!=', 'Hadir')
                          ->orWhere('jenis_absen', 'Masuk');
                })
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $attendanceBreakdown = [
                'Hadir' => $attendanceStats['Hadir'] ?? 0,
                'Izin' => $attendanceStats['Izin'] ?? 0,
                'Sakit' => $attendanceStats['Sakit'] ?? 0,
                'Alpha' => $attendanceStats['Alpha'] ?? 0,
            ];
        } else {
            $absensiData = match($filter) {
                'hari' => ['labels' => ['08:00','10:00','12:00','14:00','16:00','18:00'], 'data' => [0,0,0,0,0,0]],
                'minggu' => ['labels' => ['Sen','Sel','Rab','Kam', 'Jum', 'Sab', 'Min'], 'data' => [0,0,0,0,0,0,0]],
                'bulan' => ['labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'], 'data' => [0,0,0,0,0,0,0,0,0,0,0,0]],
                default => ['labels' => ['08:00','10:00','12:00','14:00','16:00','18:00'], 'data' => [0,0,0,0,0,0]],
            };
            $attendanceBreakdown = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
        }

        if ($request->ajax()) {
            return response()->json($absensiData);
        }

        return view('peserta.dashboard', compact(
            'user', 'peserta', 'totalHadir', 'totalLaporan', 'absensiHariIni',
            'progress', 'absensiData', 'performanceScore', 'recentActivities', 'filter', 'availableWeeks', 'weekFilter',
            'passedDays', 'totalDays', 'attendanceBreakdown'
        ));
    }

    private function getAbsensiDataHariIni($pesertaId)
    {
        $absensi = \App\Models\Absensi::selectRaw("EXTRACT(HOUR FROM waktu_absen) as jam, COUNT(*) as jumlah")
            ->where('peserta_id', $pesertaId)
            ->whereDate('waktu_absen', \Carbon\Carbon::now()->toDateString())
            ->where('status', 'Hadir')
            ->groupBy('jam')
            ->get()
            ->pluck('jumlah', 'jam');

        $labels = []; $data = [];
        for ($i = 8; $i <= 19; $i++) {
            $labels[] = sprintf("%02d:00", $i);
            $val = $absensi->get($i) ?? $absensi->get("$i") ?? 0;
            $data[] = (int)$val;
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getAbsensiDataMingguan($peserta, $weekStartDate)
    {
        if (!$weekStartDate) {
            return ['labels' => [], 'data' => []];
        }

        $start = \Carbon\Carbon::parse($weekStartDate)->startOfWeek(); // Ensure Monday
        $end = $start->copy()->endOfWeek();

        $absensi = \App\Models\Absensi::selectRaw("EXTRACT(ISODOW FROM waktu_absen) as hari_ke, COUNT(*) as jumlah")
            ->where('peserta_id', $peserta->id)
            ->whereBetween('waktu_absen', [$start->startOfDay(), $end->endOfDay()])
            ->where('status', 'Hadir')
            ->groupBy('hari_ke')
            ->get()
            ->pluck('jumlah', 'hari_ke');

        $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $data = [];

        for ($i = 1; $i <= 7; $i++) {
            $val = $absensi->get($i) ?? 0;
            $data[] = (int)$val;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getAbsensiDataBulanan($peserta)
    {
        $start = $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai) : \Carbon\Carbon::now()->subMonths(6);
        $end = $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai) : \Carbon\Carbon::now();

        if ($start->diffInMonths($end) > 12) {
             $start = $end->copy()->subMonths(12);
        }

        // PostgreSQL compatible query
        $absensi = \App\Models\Absensi::selectRaw("TO_CHAR(waktu_absen, 'YYYY-MM') as periode, COUNT(*) as jumlah")
            ->where('peserta_id', $peserta->id)
            ->whereBetween('waktu_absen', [$start->startOfMonth(), $end->endOfMonth()])
            ->where('status', 'Hadir')
            ->groupBy('periode')
            ->get()
            ->pluck('jumlah', 'periode');

        $labels = [];
        $data = [];

        $period = \Carbon\CarbonPeriod::create($start->startOfMonth(), '1 month', $end->endOfMonth());

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $val = $absensi->get($key) ?? 0;
            $data[] = (int)$val;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
