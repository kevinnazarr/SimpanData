@extends('layouts.app')

@section('title', 'Laporan Harian Peserta')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4 p-4 border-l-4 rounded-r-xl bg-blue-50 border-blue-500 animate-fade-in text-blue-800 shadow-sm">
            <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 rounded-lg bg-blue-100/50">
                <i class='bx bx-info-circle'></i>
            </div>
            <div>
                <p class="text-base font-bold">Informasi</p>
                <p class="text-sm opacity-90">Laporan harian ini bersifat <strong>tidak wajib</strong> dan hanya bertujuan untuk membantu peserta dalam menyusun laporan akhir.</p>
            </div>
        </div>

        <div class="mb-4 md:mb-6 card">
            <div
                class="flex flex-col gap-4 p-4 border-b border-gray-200 md:p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">Statistik Laporan Harian</h2>
                    <p class="text-sm text-gray-600">Pantau progres verifikasi laporan harian peserta.</p>
                </div>
                <div class="flex gap-2">
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-indigo-500 to-purple-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Total
                                        Laporan</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $totalReports }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Keseluruhan</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class='text-2xl text-white bx bx-clipboard'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-blue-500 to-indigo-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Dikirim</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $pendingReports }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Perlu Review</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class='text-2xl text-white bx bx-send'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-emerald-500 to-teal-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Disetujui
                                    </p>
                                    <h3 class="text-3xl font-bold text-white">{{ $approvedReports }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Laporan Valid</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class='text-2xl text-white bx bx-check-double'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-amber-500 to-orange-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Revisi</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $revisedReports }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Perlu Perbaikan</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class='text-2xl text-white bx bx-revision'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6 card">
            <div class="p-4 md:p-5">
                <form action="{{ route('admin.laporan.index') }}" method="GET"
                    class="grid grid-cols-1 gap-4 lg:grid-cols-[1fr_auto]">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Asal
                                Sekolah</label>
                            <div class="relative">
                                <select name="asal_sekolah_universitas" onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10 shadow-sm">
                                    <option value="">Semua Sekolah</option>
                                    @foreach ($sekolahs as $sh)
                                        <option value="{{ $sh->asal_sekolah_universitas }}"
                                            {{ request('asal_sekolah_universitas') == $sh->asal_sekolah_universitas ? 'selected' : '' }}>
                                            {{ $sh->asal_sekolah_universitas }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Status</label>
                            <div class="relative">
                                <select name="status" onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10 shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="Dikirim" {{ request('status') == 'Dikirim' ? 'selected' : '' }}>Dikirim
                                    </option>
                                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                    <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi
                                    </option>
                                </select>
                                <i
                                    class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch lg:justify-end">
                        <a href="{{ route('admin.laporan.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-colors duration-200 whitespace-nowrap shadow-sm">
                            <i class='bx bx-reset'></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 border-l-4 rounded-lg shadow-sm bg-emerald-50 border-emerald-500 animate-fade-in">
                <div class="flex items-center gap-3 text-emerald-700">
                    <i class='text-xl bx bx-check-circle'></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-600 border-b bg-gray-50/50">
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">No</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Nama Peserta</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Tanggal</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Laporan</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px] text-center">Status</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($laporans as $index => $laporan)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + $laporans->firstItem() }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-3">
                                        @if($laporan->peserta->foto && Storage::disk('public')->exists($laporan->peserta->foto))
                                            <img src="{{ asset('storage/' . $laporan->peserta->foto) }}"
                                                alt="{{ $laporan->peserta->nama }}"
                                                class="flex-shrink-0 object-cover w-8 h-8 rounded-lg shadow-soft">
                                        @else
                                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 font-bold text-white rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 shadow-soft text-[10px]">
                                                {{ strtoupper(substr($laporan->peserta->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold leading-tight text-gray-900">{{ $laporan->peserta->nama }}</div>
                                            <div class="text-[10px] text-gray-500 mt-0.5 italic line-clamp-1">{{ $laporan->peserta->asal_sekolah_universitas }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y') }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->diffForHumans() }}</div>
                                </td>
                                <td class="max-w-xs px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800 line-clamp-1">{{ $laporan->judul }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5 line-clamp-1">{{ Str::limit($laporan->deskripsi, 50) }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusClasses = [
                                            'Draft'     => 'bg-gray-100 text-gray-600',
                                            'Dikirim'   => 'bg-blue-100 text-blue-700',
                                            'Disetujui' => 'bg-emerald-100 text-emerald-700',
                                            'Revisi'    => 'bg-amber-100 text-amber-700',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusClasses[$laporan->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $laporan->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.laporan.harian.show', $laporan->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                                        <i class='text-sm bx bx-show'></i>
                                        <span>Detail</span>
                                        <i class='text-sm bx bx-chevron-right transition-transform duration-200 group-hover:translate-x-0.5'></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class='mb-2 text-4xl text-gray-300 bx bx-clipboard'></i>
                                        <p>Tidak ada laporan ditemukan.</p>
                                        <p class="text-xs text-gray-400">Coba ubah filter atau rentang tanggal Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($laporans->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 md:px-5">
                    {{ $laporans->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="detailModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeDetailModal()">
            </div>

            <div
                class="relative w-full max-w-2xl overflow-hidden transition-all transform bg-white shadow-2xl rounded-2xl animate-fade-in-up">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-white bg-indigo-600 shadow-lg rounded-xl shadow-indigo-200">
                            <i class='text-xl bx bx-file'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Detail Laporan Harian</h3>
                            <p class="text-xs font-medium text-gray-500" id="modalStatusBadge"></p>
                        </div>
                    </div>
                    <button onclick="closeDetailModal()"
                        class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-600">
                        <i class='text-2xl bx bx-x'></i>
                    </button>
                </div>

                <div class="px-8 space-y-6 py-7">
                    <div class="flex items-center gap-4 p-4 border rounded-xl bg-slate-50 border-slate-100">
                        <div id="modalAvatar"
                            class="flex items-center justify-center w-12 h-12 text-lg font-bold text-indigo-700 bg-indigo-100 rounded-xl shadow-soft">
                        </div>
                        <div>
                            <p id="modalPeserta" class="text-base font-bold leading-tight text-gray-900"></p>
                            <p id="modalSekolah" class="text-xs text-gray-500 font-medium mt-0.5"></p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Tanggal Laporan</p>
                            <p id="modalTanggal" class="mt-1 text-sm font-bold text-indigo-600"></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Judul
                                Kegiatan</label>
                            <p id="modalJudul" class="text-base font-bold text-gray-900 mt-1.5 leading-relaxed"></p>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Deskripsi
                                Detail</label>
                            <div id="modalDeskripsi"
                                class="p-5 mt-2 overflow-y-auto text-sm leading-relaxed text-gray-700 whitespace-pre-wrap bg-white border border-gray-100 shadow-inner rounded-xl max-h-60 no-scrollbar shadow-gray-50">
                            </div>
                        </div>
                    </div>

                    <div id="modalFileContainer" class="hidden pt-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Dokumen
                            Lampiran</label>
                        <div class="mt-2">
                            <a id="modalFileLink" target="_blank"
                                class="inline-flex items-center gap-3 px-5 py-3 text-sm font-bold text-indigo-600 transition-all duration-300 bg-white border-2 shadow-sm border-indigo-50 rounded-xl hover:bg-indigo-50 hover:border-indigo-100 group">
                                <i class='text-xl transition-transform bx bxs-file-pdf group-hover:scale-110'></i>
                                <span>Buka Dokumen Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-wrap items-center justify-between gap-4 px-8 py-5 border-t border-gray-100 bg-gray-50">
                    <div id="modalActionButtons" class="flex gap-2">
                    </div>
                    <button onclick="closeDetailModal()"
                        class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-100 transition-all duration-200 shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/admin/laporan.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
