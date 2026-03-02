<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index($editId = null)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.dashboard')
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        if ($editId) {
            $todayReport = Laporan::where('id', $editId)
                ->where('peserta_id', $peserta->id)
                ->firstOrFail();

            if ($todayReport->status == 'Disetujui') {
                return redirect()->route('peserta.laporan.index')
                    ->with('error', 'Laporan yang sudah disetujui tidak dapat diedit.');
            }
        } else {
            $todayReport = Laporan::where('peserta_id', $peserta->id)
                ->where('tanggal_laporan', date('Y-m-d'))
                ->first();
        }

        $recentReports = Laporan::where('peserta_id', $peserta->id)
            ->where('tanggal_laporan', '!=', date('Y-m-d'))
            ->orderBy('tanggal_laporan', 'desc')
            ->paginate(5, ['*'], 'daily_page');

        $pendingRevisions = Laporan::where('peserta_id', $peserta->id)
            ->where('status', 'Revisi')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $allReports = Laporan::where('peserta_id', $peserta->id)
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $search = request('search');

        $approvedHistory = Laporan::where('peserta_id', $peserta->id)
            ->where('status', 'Disetujui')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal_laporan', 'desc')
            ->paginate(5, ['*'], 'history_page')
            ->appends(request()->query());

        $stats = [
            'total'     => $allReports->count(),
            'dikirim'   => $allReports->where('status', 'Dikirim')->count(),
            'disetujui' => $allReports->where('status', 'Disetujui')->count(),
            'revisi'    => $allReports->where('status', 'Revisi')->count(),
        ];

        return view('peserta.laporan.laporan-harian', compact('user', 'peserta', 'todayReport', 'recentReports', 'pendingRevisions', 'allReports', 'stats', 'approvedHistory'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->back()
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        if ($request->hasFile('file') && !$request->file('file')->isValid()) {
            return back()->with('error', 'Upload gagal. File melebihi batas server 10MB.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:5120',
            'tanggal_laporan' => 'required|date',
        ], [
            'judul.required' => 'Judul laporan harus diisi.',
            'judul.max' => 'Judul laporan maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi kegiatan harus diisi.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.mimetypes' => 'File harus berupa dokumen PDF yang valid.',
            'file.max' => 'Ukuran file maksimal 5MB.',
            'tanggal_laporan.required' => 'Tanggal laporan harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $existingReport = Laporan::where('peserta_id', $peserta->id)
            ->where('tanggal_laporan', $request->tanggal_laporan)
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->with('error', 'Anda sudah membuat laporan untuk tanggal ini. Silakan edit laporan yang sudah ada.')
                ->withInput();
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('laporan', 'public');
        }

        Laporan::create([
            'peserta_id' => $peserta->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'tanggal_laporan' => $request->tanggal_laporan,
            'status' => 'Dikirim',
        ]);

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan berhasil dikirim untuk review.');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.dashboard')
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporan = Laporan::where('id', $id)
            ->where('peserta_id', $peserta->id)
            ->firstOrFail();

        return view('peserta.laporan.laporan-harian-show', compact('user', 'peserta', 'laporan'));
    }

    public function edit(string $id)
    {
        return $this->index($id);
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->back()
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporan = Laporan::where('id', $id)
            ->where('peserta_id', $peserta->id)
            ->firstOrFail();

        if ($laporan->status == 'Disetujui') {
            return redirect()->route('peserta.laporan.show', $id)
                ->with('error', 'Laporan yang sudah disetujui tidak dapat diedit.');
        }
        if ($request->hasFile('file') && !$request->file('file')->isValid()) {
            return back()->with('error', 'Upload gagal. File melebihi batas server 10MB.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:5120', // 5MB
        ], [
            'judul.required' => 'Judul laporan harus diisi.',
            'judul.max' => 'Judul laporan maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi kegiatan harus diisi.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.mimetypes' => 'File harus berupa dokumen PDF yang valid.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $filePath = $laporan->file_path;
        if ($request->hasFile('file')) {
            if ($laporan->file_path && Storage::disk('public')->exists($laporan->file_path)) {
                Storage::disk('public')->delete($laporan->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('laporan', 'public');
        }

        $laporan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'status' => 'Dikirim',
        ]);

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->back()
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporan = Laporan::where('id', $id)
            ->where('peserta_id', $peserta->id)
            ->firstOrFail();

        // Prevent deletion of approved/sent reports
        if ($laporan->status == 'Disetujui') {
            return redirect()->back()
                ->with('error', 'Laporan yang sudah disetujui tidak dapat dihapus.');
        }

        if ($laporan->file_path && Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        $laporan->delete();

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function laporanAkhir()
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.dashboard')
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporanAkhir = LaporanAkhir::where('peserta_id', $peserta->id)
            ->latest()
            ->first();

        $search = request('search');

        $historyLaporanAkhir = LaporanAkhir::where('peserta_id', $peserta->id)
            ->when($search, function($query, $search) {
                return $query->where('judul', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5, ['*'], 'final_page')
            ->appends(request()->query());

        $allLaporanAkhir = LaporanAkhir::where('peserta_id', $peserta->id)
            ->latest()
            ->get();

        $statsAkhir = [
            'total'     => $allLaporanAkhir->count(),
            'dikirim'   => $allLaporanAkhir->where('status', 'Dikirim')->count(),
            'disetujui' => $allLaporanAkhir->where('status', 'Disetujui')->count(),
            'revisi'    => $allLaporanAkhir->where('status', 'Revisi')->count(),
        ];

        return view('peserta.laporan.laporan-akhir', compact('user', 'peserta', 'laporanAkhir', 'historyLaporanAkhir', 'allLaporanAkhir', 'statsAkhir'));
    }

    public function laporanAkhirStore(Request $request)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'Data peserta tidak ditemukan.');
        }

        if ($request->hasFile('file') && !$request->file('file')->isValid()) {
            return back()->with('error', 'Upload gagal. File melebihi batas server 10MB.');
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:10240',
        ], [
            'file.required' => 'File laporan akhir wajib diunggah.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.mimetypes' => 'File harus berupa dokumen PDF yang valid.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('laporan_akhir', 'public');
        }

        LaporanAkhir::create([
            'peserta_id' => $peserta->id,
            'judul' => 'Laporan Akhir ' . $peserta->nama,
            'deskripsi' => 'Laporan Akhir PKL/Magang oleh ' . $peserta->nama,
            'file_path' => $filePath,
            'status' => 'Dikirim',
        ]);

        return redirect()->route('peserta.laporan.akhir')
            ->with('success', 'Laporan akhir berhasil disimpan.');
    }

    public function laporanAkhirUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporanAkhir = LaporanAkhir::where('id', $id)
            ->where('peserta_id', $peserta->id)
            ->firstOrFail();

        if ($laporanAkhir->status == 'Disetujui') {
            return redirect()->back()->with('error', 'Laporan yang sudah disetujui tidak dapat diperbarui.');
        }

        if ($request->hasFile('file') && !$request->file('file')->isValid()) {
            return back()->with('error', 'Upload gagal. File melebihi batas server 10MB.');
        }

        $validator = Validator::make($request->all(), [
            'file' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:10240',
        ], [
            'file.mimes' => 'File harus berformat PDF.',
            'file.mimetypes' => 'File harus berupa dokumen PDF yang valid.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filePath = $laporanAkhir->file_path;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('laporan_akhir', 'public');
        }

        $laporanAkhir->update([
            'judul' => 'Laporan Akhir ' . $peserta->nama,
            'deskripsi' => 'Laporan Akhir PKL/Magang oleh ' . $peserta->nama,
            'file_path' => $filePath,
            'status' => 'Dikirim',
            'catatan_admin' => null,
        ]);

        return redirect()->route('peserta.laporan.akhir')
            ->with('success', 'Laporan akhir berhasil diperbarui.');
    }

    public function laporanAkhirShow($id)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.dashboard')
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        $laporan = LaporanAkhir::where('id', $id)
            ->where('peserta_id', $peserta->id)
            ->firstOrFail();

        return view('peserta.laporan.laporan-akhir-show', compact('user', 'peserta', 'laporan'));
    }
}
