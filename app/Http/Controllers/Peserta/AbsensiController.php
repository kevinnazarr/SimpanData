<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = Peserta::where('id', $user->id)->firstOrFail();

        $todayAttendance = Absensi::where('peserta_id', $peserta->id)
            ->whereDate('waktu_absen', Carbon::today())
            ->get();

        $hasMasuk = $todayAttendance->where('jenis_absen', 'Masuk')->where('status', 'Hadir')->isNotEmpty();
        $hasPulang = $todayAttendance->where('jenis_absen', 'Pulang')->isNotEmpty();
        $isIzinSakit = $todayAttendance->whereIn('status', ['Izin', 'Sakit'])->isNotEmpty();

        return view('peserta.absensi', compact(
            'peserta',
            'todayAttendance',
            'hasMasuk',
            'hasPulang',
            'isIzinSakit'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:checkin,checkout',
            'latitude' => 'required',
            'longitude' => 'required',
            'mode_kerja' => 'nullable|in:WFO,WFA',
            'status' => 'required|in:Hadir,Izin,Sakit',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($request->type === 'checkin' && $request->status === 'Hadir' && !$request->mode_kerja) {
            return redirect()->back()
                ->withErrors(['mode_kerja' => 'Mode kerja wajib diisi untuk status Hadir.'])
                ->withInput();
        }

        $user = Auth::user();
        $peserta = Peserta::where('id', $user->id)->firstOrFail();

        $jenisAbsen = $request->type == 'checkin' ? 'Masuk' : 'Pulang';

        $existingAbsensi = Absensi::where('peserta_id', $peserta->id)
            ->where('jenis_absen', $jenisAbsen)
            ->whereDate('waktu_absen', Carbon::today())
            ->first();

        if ($existingAbsensi) {
            return redirect()->route('peserta.absensi')
                ->with('error', 'Anda sudah melakukan absensi ' . strtolower($jenisAbsen) . ' hari ini.');
        }

        $modeKerja = $request->mode_kerja;
        $status = $request->status;

        if ($jenisAbsen == 'Pulang') {
            $checkinToday = Absensi::where('peserta_id', $peserta->id)
                ->where('jenis_absen', 'Masuk')
                ->whereDate('waktu_absen', Carbon::today())
                ->first();

            if (!$checkinToday) {
                return redirect()->route('peserta.absensi')
                    ->with('error', 'Anda harus melakukan absensi masuk terlebih dahulu sebelum absensi pulang.');
            }

            $modeKerja = $checkinToday->mode_kerja;
            $status = 'Hadir';
        }

        $absensi = new Absensi();
        $absensi->peserta_id = $peserta->id;
        $absensi->jenis_absen = $jenisAbsen;
        $absensi->waktu_absen = Carbon::now();
        $absensi->mode_kerja = $modeKerja;
        $absensi->status = $status;
        $absensi->wa_pengirim = $request->notes;
        $absensi->latitude = $request->latitude;
        $absensi->longitude = $request->longitude;
        $absensi->save();

        return redirect()->route('peserta.absensi')
            ->with('success', 'Absensi ' . strtolower($jenisAbsen) . ' berhasil dicatat.');
    }

    public function history()
    {
        $user = Auth::user();
        $peserta = Peserta::where('id', $user->id)->firstOrFail();

        $absensiHistory = Absensi::where('peserta_id', $peserta->id)
            ->orderBy('waktu_absen', 'desc')
            ->paginate(20);

        return view('peserta.absensi.history', compact('absensiHistory', 'peserta'));
    }
}