@extends('layouts.app')

@section('title', 'Laporan Akhir Peserta')

@section('content')
    <div class="space-y-6">
        <div class="mb-4 md:mb-6 card">
            <div
                class="flex flex-col gap-4 p-4 border-b border-gray-200 md:p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">Statistik Laporan Akhir</h2>
                    <p class="text-sm text-gray-600">Pantau progres verifikasi laporan akhir peserta.</p>
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
                <form action="{{ route('admin.laporan.akhir.index') }}" method="GET"
                    class="grid grid-cols-1 gap-4 lg:grid-cols-[1fr_auto]">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Tanggal Upload</label>
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
                        <a href="{{ route('admin.laporan.akhir.index') }}"
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
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Judul Laporan</th>
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
                                <td class="max-w-xs px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800 line-clamp-1">{{ $laporan->judul }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 italic">Diunggah {{ $laporan->updated_at->diffForHumans() }}</div>
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
                                    <a href="{{ route('admin.laporan.akhir.show', $laporan->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                                        <i class='text-sm bx bx-show'></i>
                                        <span>Detail</span>
                                        <i class='text-sm bx bx-chevron-right transition-transform duration-200 group-hover:translate-x-0.5'></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class='mb-2 text-4xl text-gray-300 bx bx-file'></i>
                                        <p>Tidak ada laporan akhir ditemukan.</p>
                                        <p class="text-xs text-gray-400">Coba ubah filter asal sekolah atau status.</p>
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
@endsection
