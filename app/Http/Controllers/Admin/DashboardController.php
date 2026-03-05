<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Feedback;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        try {
            $totalPkl = Peserta::where('jenis_kegiatan', 'PKL')->count();
            $totalMagang = Peserta::where('jenis_kegiatan', 'Magang')->count();
            $aktif = Peserta::where('status', 'Aktif')->count();
            $selesai = Peserta::where('status', 'Selesai')->count();

            $filter = $request->get('filter', 'hari');
            $weekFilter = $request->get('week');

            $today = Carbon::today();
            $attendanceStats = Absensi::whereDate('waktu_absen', $today)
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

            $absensiData = match($filter) {
                'hari' => $this->getAggregatedTrendHariIni(),
                'minggu' => $this->getAggregatedTrendMingguan($weekFilter),
                'bulan' => $this->getAggregatedTrendBulanan(),
                default => $this->getAggregatedTrendHariIni(),
            };

            if ($request->ajax()) {
                if ($request->has('filter')) {
                    return response()->json($absensiData);
                }

                $asal = $request->get('asal_sekolah_universitas');
                $pesertaQuery = Peserta::select('id', 'nama', 'jenis_kegiatan', 'status', 'asal_sekolah_universitas')
                    ->orderBy('nama');
                
                if (!empty($asal)) {
                    $pesertaQuery->where('asal_sekolah_universitas', $asal);
                }
                
                $peserta = $pesertaQuery->paginate(10)->onEachSide(1)->withQueryString();

                return response()->json([
                    'rows' => view('admin.partials.peserta-rows', compact('peserta'))->render(),
                    'pagination' => $peserta->links()->toHtml(),
                ]);
            }

            $availableWeeks = $this->getAvailableWeeks();
            if (!$weekFilter && count($availableWeeks) > 0) {
                $weekFilter = end($availableWeeks)['value'];
            }

            $feedbacks = Feedback::select('id', 'peserta_id', 'pesan', 'created_at')
                ->with(['peserta:id,nama,foto'])
                ->where('pengirim', 'Peserta')
                ->latest()
                ->limit(20)
                ->get();

            $educationStats = $this->getEducationStats();

            return view('admin.dashboard', array_merge(
                compact(
                    'totalPkl', 'totalMagang', 'aktif', 'selesai', 
                    'feedbacks', 'attendanceBreakdown', 'absensiData', 
                    'filter', 'availableWeeks', 'weekFilter'
                ),
                $educationStats
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard', [
                'totalPkl' => 0, 'totalMagang' => 0, 'aktif' => 0, 'selesai' => 0,
                'feedbacks' => collect(), 'pesertaSekolah' => collect(), 'pesertaUniversitas' => collect(),
                'attendanceBreakdown' => ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0],
                'absensiData' => ['labels' => [], 'datasets' => []],
                'filter' => 'hari', 'availableWeeks' => [], 'weekFilter' => null
            ]);
        }
    }

    /**
     * Get education statistics split by school and university.
     */
    private function getEducationStats()
    {
        $educationData = Peserta::select('asal_sekolah_universitas', DB::raw('count(*) as total'))
            ->whereNotNull('asal_sekolah_universitas')
            ->where('asal_sekolah_universitas', '!=', '')
            ->groupBy('asal_sekolah_universitas')
            ->orderBy('total', 'desc')
            ->get();

        $uniKeywords = ['Universitas', 'University', 'Institut', 'Politeknik', 'STMIK', 'STIE', 'Sekolah Tinggi', 'Akademi', 'UNIV'];

        $pesertaUniversitas = $educationData->filter(function($item) use ($uniKeywords) {
            $name = strtoupper($item->asal_sekolah_universitas);
            foreach ($uniKeywords as $kw) {
                if (str_contains($name, strtoupper($kw))) return true;
            }
            return false;
        })->values();

        $pesertaSekolah = $educationData->reject(function($item) use ($uniKeywords) {
            $name = strtoupper($item->asal_sekolah_universitas);
            foreach ($uniKeywords as $kw) {
                if (str_contains($name, strtoupper($kw))) return true;
            }
            return false;
        })->values();

        return compact('pesertaSekolah', 'pesertaUniversitas');
    }

    private function getAggregatedTrendHariIni()
    {
        $labels = []; $masuk = []; $pulang = [];
        $today = Carbon::today()->toDateString();

        $absensi = Absensi::whereDate('waktu_absen', $today)
            ->where('status', 'Hadir')
            ->selectRaw("EXTRACT(HOUR FROM waktu_absen) as jam, jenis_absen, COUNT(*) as jumlah")
            ->groupBy('jam', 'jenis_absen')
            ->get();

        for ($i = 8; $i <= 19; $i++) {
            $labels[] = sprintf("%02d:00", $i);
            $m = $absensi->where('jam', $i)->where('jenis_absen', 'Masuk')->first();
            $p = $absensi->where('jam', $i)->where('jenis_absen', 'Pulang')->first();
            $masuk[] = (int)($m->jumlah ?? 0);
            $pulang[] = (int)($p->jumlah ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Absen Masuk', 'data' => $masuk],
                ['label' => 'Absen Pulang', 'data' => $pulang]
            ]
        ];
    }

    private function getAggregatedTrendMingguan($weekStartDate)
    {
        if (!$weekStartDate) return ['labels' => [], 'datasets' => []];

        $start = Carbon::parse($weekStartDate)->startOfWeek();
        $end = $start->copy()->endOfWeek();
        $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $masuk = []; $pulang = [];

        $absensi = Absensi::whereBetween('waktu_absen', [$start->startOfDay(), $end->endOfDay()])
            ->where('status', 'Hadir')
            ->selectRaw("EXTRACT(ISODOW FROM waktu_absen) as hari_ke, jenis_absen, COUNT(*) as jumlah")
            ->groupBy('hari_ke', 'jenis_absen')
            ->get();

        for ($i = 1; $i <= 7; $i++) {
            $m = $absensi->where('hari_ke', $i)->where('jenis_absen', 'Masuk')->first();
            $p = $absensi->where('hari_ke', $i)->where('jenis_absen', 'Pulang')->first();
            $masuk[] = (int)($m->jumlah ?? 0);
            $pulang[] = (int)($p->jumlah ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Absen Masuk', 'data' => $masuk],
                ['label' => 'Absen Pulang', 'data' => $pulang]
            ]
        ];
    }

    private function getAggregatedTrendBulanan()
    {
        $end = Carbon::now();
        $start = $end->copy()->subMonths(11);
        $period = \Carbon\CarbonPeriod::create($start->startOfMonth(), '1 month', $end->endOfMonth());
        $labels = []; $masuk = []; $pulang = [];

        $absensi = Absensi::whereBetween('waktu_absen', [$start->startOfMonth(), $end->endOfMonth()])
            ->where('status', 'Hadir')
            ->selectRaw("TO_CHAR(waktu_absen, 'YYYY-MM') as periode, jenis_absen, COUNT(*) as jumlah")
            ->groupBy('periode', 'jenis_absen')
            ->get();

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $m = $absensi->where('periode', $key)->where('jenis_absen', 'Masuk')->first();
            $p = $absensi->where('periode', $key)->where('jenis_absen', 'Pulang')->first();
            $masuk[] = (int)($m->jumlah ?? 0);
            $pulang[] = (int)($p->jumlah ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Absen Masuk', 'data' => $masuk],
                ['label' => 'Absen Pulang', 'data' => $pulang]
            ]
        ];
    }

    private function getAvailableWeeks()
    {
        $firstAbsensi = Absensi::orderBy('waktu_absen')->first();
        $start = $firstAbsensi ? Carbon::parse($firstAbsensi->waktu_absen) : Carbon::now()->subMonths(6);
        $end = Carbon::now();
        if ($start->diffInMonths($end) > 6) $start = $end->copy()->subMonths(6);
        $start = $start->copy()->startOfWeek();
        $period = \Carbon\CarbonPeriod::create($start, '1 week', $end);

        $availableWeeks = []; $weekCounter = 1;
        foreach($period as $date) {
            $weekEnd = $date->copy()->endOfWeek();
            $availableWeeks[] = [
                'value' => $date->format('Y-m-d'),
                'label' => "Minggu $weekCounter (" . $date->format('d M') . " - " . $weekEnd->format('d M') . ")"
            ];
            $weekCounter++;
        }
        return $availableWeeks;
    }
}
