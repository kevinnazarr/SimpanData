<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $sekolah = $request->asal_sekolah_universitas;
        $tanggal = $request->tanggal ?? null;

        $query = Laporan::with('peserta');

        $baseStatsQuery = Laporan::query();
        if ($tanggal) {
            $baseStatsQuery->whereDate('tanggal_laporan', $tanggal);
        }
        if ($sekolah) {
            $baseStatsQuery->whereHas('peserta', function ($q) use ($sekolah) {
                $q->where('asal_sekolah_universitas', $sekolah);
            });
        }
        if ($status) {
            $baseStatsQuery->where('status', $status);
        }

        $totalReports = (clone $baseStatsQuery)->count();
        $pendingReports = (clone $baseStatsQuery)->where('status', 'Dikirim')->count();
        $approvedReports = (clone $baseStatsQuery)->where('status', 'Disetujui')->count();
        $revisedReports = (clone $baseStatsQuery)->where('status', 'Revisi')->count();

        if ($status) {
            $query->where('status', $status);
        }

        if ($sekolah) {
            $query->whereHas('peserta', function ($q) use ($sekolah) {
                $q->where('asal_sekolah_universitas', $sekolah);
            });
        }

        if ($tanggal) {
            $query->whereDate('tanggal_laporan', $tanggal);
        }

        $laporans = $query->orderBy('tanggal_laporan', 'desc')
            ->paginate(10)
            ->withQueryString();

        $sekolahs = Peserta::select('asal_sekolah_universitas')
            ->whereNotNull('asal_sekolah_universitas')
            ->distinct()
            ->orderBy('asal_sekolah_universitas')
            ->get();

        return view('admin.laporan.Laporan-harian', compact(
            'laporans',
            'sekolahs',
            'status',
            'sekolah',
            'tanggal',
            'totalReports',
            'pendingReports',
            'approvedReports',
            'revisedReports'
        ));
    }

    public function show($id)
    {
        $laporan = Laporan::with('peserta')->findOrFail($id);
        return view('admin.laporan.laporan-harian-show', compact('laporan'));
    }

    public function approve($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->status !== 'Dikirim' && $laporan->status !== 'Revisi') {
            return redirect()->back()->with('error', 'Hanya laporan dengan status "Dikirim" atau "Revisi" yang dapat disetujui.');
        }

        $laporan->update([
            'status' => 'Disetujui',
            'catatan_admin' => null // Clear notes if approved
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui.');
    }

    public function revisi(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ], [
            'catatan_admin.required' => 'Catatan revisi harus diisi.',
        ]);

        $laporan = Laporan::findOrFail($id);

        if ($laporan->status === 'Disetujui') {
            return redirect()->back()->with('error', 'Laporan yang sudah disetujui tidak dapat direvisi.');
        }

        $laporan->update([
            'status' => 'Revisi',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Laporan ditandai untuk revisi dengan catatan.');
    }

    public function laporanAkhirIndex(Request $request)
    {
        $status = $request->status;
        $sekolah = $request->asal_sekolah_universitas;
        $tanggal = $request->tanggal;

        $query = LaporanAkhir::with('peserta');

        $baseStatsQuery = LaporanAkhir::query();
        if ($tanggal) {
            $baseStatsQuery->whereDate('updated_at', $tanggal);
        }
        if ($sekolah) {
            $baseStatsQuery->whereHas('peserta', function ($q) use ($sekolah) {
                $q->where('asal_sekolah_universitas', $sekolah);
            });
        }
        if ($status) {
            $baseStatsQuery->where('status', $status);
        }

        $totalReports = (clone $baseStatsQuery)->count();
        $pendingReports = (clone $baseStatsQuery)->where('status', 'Dikirim')->count();
        $approvedReports = (clone $baseStatsQuery)->where('status', 'Disetujui')->count();
        $revisedReports = (clone $baseStatsQuery)->where('status', 'Revisi')->count();

        if ($status) {
            $query->where('status', $status);
        }

        if ($sekolah) {
            $query->whereHas('peserta', function ($q) use ($sekolah) {
                $q->where('asal_sekolah_universitas', $sekolah);
            });
        }

        if ($tanggal) {
            $query->whereDate('updated_at', $tanggal);
        }

        $laporans = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $sekolahs = Peserta::select('asal_sekolah_universitas')
            ->whereNotNull('asal_sekolah_universitas')
            ->distinct()
            ->orderBy('asal_sekolah_universitas')
            ->get();

        return view('admin.laporan.laporan-akhir', compact(
            'laporans', 
            'sekolahs', 
            'status', 
            'sekolah',
            'tanggal',
            'totalReports',
            'pendingReports',
            'approvedReports',
            'revisedReports'
        ));
    }

    public function laporanAkhirShow($id)
    {
        $laporan = LaporanAkhir::with('peserta')->findOrFail($id);
        return view('admin.laporan.laporan-akhir-show', compact('laporan'));
    }

    public function laporanAkhirApprove($id)
    {
        $laporan = LaporanAkhir::findOrFail($id);
        
        if ($laporan->status !== 'Dikirim' && $laporan->status !== 'Revisi') {
            return redirect()->back()->with('error', 'Hanya laporan dengan status "Dikirim" atau "Revisi" yang dapat disetujui.');
        }

        $laporan->update([
            'status' => 'Disetujui',
            'catatan_admin' => null
        ]);

        return redirect()->route('admin.laporan.akhir.index')->with('success', 'Laporan akhir berhasil disetujui.');
    }

    public function laporanAkhirRevisi(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $laporan = LaporanAkhir::findOrFail($id);

        if ($laporan->status !== 'Dikirim' && $laporan->status !== 'Revisi') {
            return redirect()->back()->with('error', 'Hanya laporan dengan status "Dikirim" atau "Revisi" yang dapat direvisi.');
        }

        $laporan->update([
            'status' => 'Revisi',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.laporan.akhir.index')->with('success', 'Catatan revisi berhasil dikirim ke peserta.');
    }
}
