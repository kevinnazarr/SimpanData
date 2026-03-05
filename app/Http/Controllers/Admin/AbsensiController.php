<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $allDates  = $request->boolean('all_dates');
        $tanggal   = $allDates ? null : ($request->tanggal ?? Carbon::today()->toDateString());
        $pesertaId = $request->peserta_id;
        $jenis     = $request->jenis_absen;
        $status    = $request->status;
        $sekolah   = $request->asal_sekolah_universitas;

        $baseQuery = Absensi::with('peserta');

        if ($tanggal) {
            $baseQuery->whereDate('waktu_absen', $tanggal);
        }

        if ($pesertaId) {
            $baseQuery->where('peserta_id', $pesertaId);
        }

        if ($jenis) {
            $baseQuery->where('jenis_absen', $jenis);
        }

        if ($status) {
            $baseQuery->where('status', $status);
        }

        if ($sekolah) {
            $baseQuery->whereHas('peserta', function ($q) use ($sekolah) {
                $q->where('asal_sekolah_universitas', $sekolah);
            });
        }

        if ($request->export === 'excel') {
            $data = (clone $baseQuery)
                ->orderBy('waktu_absen', 'desc')
                ->get();

            return $this->exportExcel($data, $tanggal ?? Carbon::today()->toDateString());
        }

        $absensis = (clone $baseQuery)
            ->orderBy('waktu_absen', 'desc')
            ->paginate(10)->onEachSide(1)
            ->withQueryString();

        $hadirMasuk = (clone $baseQuery)
            ->where('status', 'Hadir')
            ->where('jenis_absen', 'Masuk')
            ->count();

        $hadirPulang = (clone $baseQuery)
            ->where('status', 'Hadir')
            ->where('jenis_absen', 'Pulang')
            ->count();

        $izin = (clone $baseQuery)
            ->where('status', 'Izin')
            ->count();

        $sakit = (clone $baseQuery)
            ->where('status', 'Sakit')
            ->count();

        $wfo = (clone $baseQuery)
            ->where('status', 'Hadir')
            ->where('jenis_absen', 'Masuk')
            ->where('mode_kerja', 'WFO')
            ->count();

        $wfa = (clone $baseQuery)
            ->where('status', 'Hadir')
            ->where('jenis_absen', 'Masuk')
            ->where('mode_kerja', 'WFA')
            ->count();

        $sekolahs = Peserta::select('asal_sekolah_universitas')
            ->whereNotNull('asal_sekolah_universitas')
            ->distinct()
            ->orderBy('asal_sekolah_universitas')
            ->get();

        return view('admin.absensi.index', compact(
            'sekolahs',
            'sekolah',
            'absensis',
            'hadirMasuk',
            'hadirPulang',
            'izin',
            'sakit',
            'wfo',
            'wfa',
            'tanggal',
            'allDates',
            'pesertaId',
            'jenis',
            'status'
        ))->with([
            'pesertas' => Peserta::orderBy('nama')->get()
        ]);
    }

    private function exportExcel($data, $tanggal): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Nama Peserta',
            'Asal Sekolah',
            'Jenis Absen',
            'Status',
            'Mode Kerja',
            'Waktu Absen'
        ];

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($data as $absen) {
            $sheet->setCellValue("A{$row}", $absen->peserta->nama);
            $sheet->setCellValue("B{$row}", $absen->peserta->asal_sekolah_universitas);
            $sheet->setCellValue("C{$row}", $absen->jenis_absen);
            $sheet->setCellValue("D{$row}", $absen->status);
            $sheet->setCellValue("E{$row}", $absen->mode_kerja);
            $sheet->setCellValue("F{$row}", $absen->waktu_absen);
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="absensi_' . $tanggal . '.xlsx"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
