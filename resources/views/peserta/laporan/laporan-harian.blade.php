@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4 p-4 text-blue-800 border-l-4 border-blue-500 shadow-sm rounded-r-xl bg-blue-50 animate-fade-in">
            <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 rounded-lg bg-blue-100/50">
                <i class='bx bx-info-circle'></i>
            </div>
            <div>
                <p class="text-base font-bold">Informasi Penting</p>
                <p class="text-sm opacity-90">Laporan harian ini bersifat <strong>tidak wajib</strong> dan hanya bertujuan untuk membantu Anda dalam menyusun laporan akhir secara bertahap.</p>
            </div>
        </div>

        <div class="flex flex-col justify-between gap-4 p-6 md:flex-row md:items-center card shadow-soft animate-fade-in">
            <div class="flex items-center space-x-4">
                <div
                    class="flex items-center justify-center w-12 h-12 text-2xl text-purple-600 shadow-inner rounded-xl bg-purple-50">
                    <i class='bx bx-file-blank'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ isset($todayReport) && $todayReport->status == 'Revisi' ? 'Edit Laporan' : 'Laporan Harian' }}
                    </h1>
                    <p class="text-sm font-medium text-slate-500">
                        @if(isset($todayReport) && $todayReport->tanggal_laporan != date('Y-m-d'))
                            Memperbaiki laporan untuk tanggal {{ \Carbon\Carbon::parse($todayReport->tanggal_laporan)->translatedFormat('l, j F Y') }}
                        @else
                            Laporkan kegiatan yang Anda lakukan hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="px-4 py-2 border rounded-xl animate-fade-in {{ isset($todayReport) ? 'border-green-100 bg-green-50' : 'border-orange-100 bg-orange-50' }}"
                style="animation-delay: 200ms">
                <p
                    class="text-xs font-bold tracking-tighter uppercase {{ isset($todayReport) ? 'text-green-600' : 'text-orange-600' }}">
                    Status Laporan</p>
                <p class="text-sm font-extrabold {{ isset($todayReport) ? 'text-green-900' : 'text-orange-900' }}">
                    @if (isset($todayReport))
                        @if($todayReport->tanggal_laporan != date('Y-m-d'))
                            {{ $todayReport->status }} ({{ \Carbon\Carbon::parse($todayReport->tanggal_laporan)->format('d/m/Y') }})
                        @else
                            {{ $todayReport->status }}
                        @endif
                    @else
                        Belum Lapor
                    @endif
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-r-lg shadow-sm animate-fade-in"
                role="alert">
                <div class="flex items-center">
                    <i class='mr-2 text-xl bx bx-check-circle'></i>
                    <p class="font-bold">Berhasil!</p>
                </div>
                <p class="mt-1 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded-r-lg shadow-sm animate-fade-in"
                role="alert">
                <div class="flex items-center">
                    <i class='mr-2 text-xl bx bx-error-circle'></i>
                    <p class="font-bold">Terdapat Kesalahan!</p>
                </div>
                <ul class="pl-5 mt-1 text-sm list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (auth()->user()->peserta && auth()->user()->peserta->laporans->where('status', 'Revisi')->count() > 0)
            @foreach (auth()->user()->peserta->laporans->where('status', 'Revisi') as $rev)
                @if (\Carbon\Carbon::parse($rev->tanggal_laporan)->isToday())
                    <div
                        class="flex items-center justify-between p-4 mb-6 border-l-4 rounded-r-lg shadow-sm text-amber-800 bg-amber-50 border-amber-500 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class='text-2xl bx bx-revision'></i>
                            <div>
                                <p class="font-bold">Laporan Hari Ini Perlu Revisi!</p>
                                <p class="text-sm">Silakan periksa catatan revisi dan perbaiki laporan Anda.</p>
                            </div>
                        </div>
                        <button type="button"
                            onclick="showRevisionModal('{{ str_replace(["\r", "\n"], ['\r', '\n'], addslashes($rev->catatan_admin ?? '')) }}')"
                            class="px-4 py-2 text-xs font-bold transition rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 whitespace-nowrap">
                            Lihat Pesan dari Admin
                        </button>
                    </div>
                @endif
            @endforeach
        @endif

        @if(isset($todayReport) && in_array($todayReport->status, ['Dikirim', 'Disetujui']))
            <div class="p-8 text-center border-2 border-dashed rounded-xl border-slate-200 bg-slate-50 animate-fade-in-up">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="flex items-center justify-center w-20 h-20 mb-2 rounded-full {{ $todayReport->status == 'Disetujui' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                        <i class='text-4xl bx {{ $todayReport->status == 'Disetujui' ? 'bx-check-circle' : 'bx-time-five' }}'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">
                            {{ $todayReport->status == 'Disetujui' ? 'Laporan Hari Ini Disetujui' : 'Laporan Hari Ini Sudah Dikirim' }}
                        </h3>
                        <p class="mt-2 text-slate-500">
                            {{ $todayReport->status == 'Disetujui' ? 'Laporan Anda telah disetujui oleh pembimbing.' : 'Terima kasih telah mengirimkan laporan. Menunggu review dari pembimbing.' }}
                        </p>
                    </div>
                    <a href="{{ route('peserta.laporan.show', $todayReport->id) }}"
                        class="px-6 py-3 mt-4 text-sm font-bold text-white transition-all duration-200 rounded-lg shadow-lg {{ $todayReport->status == 'Disetujui' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <div class="flex items-center gap-2">
                            <i class='text-lg bx bx-show'></i>
                            <span>Lihat Laporan Saya</span>
                        </div>
                    </a>
                </div>
            </div>
        @else
        <form
            action="{{ isset($todayReport) ? route('peserta.laporan.update', $todayReport->id) : route('peserta.laporan.store') }}"
            method="POST" enctype="multipart/form-data" id="report-form">
            @csrf
            @if (isset($todayReport))
                @method('PUT')
            @endif
            <input type="hidden" name="status" id="status-field" value="Dikirim">
            <input type="hidden" name="tanggal_laporan" value="{{ $todayReport->tanggal_laporan ?? date('Y-m-d') }}">

            <div class="p-6 card shadow-soft md:p-8 animate-fade-in-up" style="animation-delay: 100ms">
                <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                    <div class="flex items-center justify-center w-10 h-10 text-xl text-purple-600 rounded-lg bg-purple-50">
                        <i class='bx bx-edit'></i>
                    </div>
                    <h4 class="text-lg font-bold uppercase text-slate-800">Form Laporan Harian</h4>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block mb-3 text-xs font-bold tracking-widest uppercase text-slate-500">
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul"
                            value="{{ old('judul', $todayReport->judul ?? '') }}"
                            class="w-full px-4 py-3 transition-colors border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 {{ $errors->has('judul') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Contoh: Membuat Fitur Login Sistem" required>
                        @error('judul')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi"
                            class="block mb-3 text-xs font-bold tracking-widest uppercase text-slate-500">
                            Deskripsi Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="8"
                            class="w-full px-4 py-3 transition-colors border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Jelaskan secara detail kegiatan yang Anda lakukan hari ini..."
                            required>{{ old('deskripsi', $todayReport->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file" class="block mb-3 text-xs font-bold tracking-widest uppercase text-slate-500">
                            Lampiran File (Opsional)
                        </label>
                        <div class="relative">
                            <input type="file" id="file" name="file"
                                accept="application/pdf"
                                class="w-full px-4 py-3 transition-colors border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 {{ $errors->has('file') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (isset($todayReport) && $todayReport->file_path)
                            <div class="flex items-center gap-2 p-3 mt-3 border rounded-lg bg-slate-50 border-slate-200">
                                <i class='text-lg bx bx-file text-slate-600'></i>
                                <a href="{{ Storage::url($todayReport->file_path) }}" target="_blank"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                    File saat ini: {{ basename($todayReport->file_path) }}
                                </a>
                            </div>
                        @endif
                        <p class="mt-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Format: PDF | Maksimal 5MB</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                        @if (isset($todayReport))
                            <a href="{{ route('peserta.laporan.index') }}"
                                class="px-6 py-3 text-sm font-bold transition-all duration-200 border-2 rounded-xl text-slate-700 border-slate-300 hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                <div class="flex items-center gap-2">
                                    <i class='text-base bx bx-x'></i>
                                    <span>Batal</span>
                                </div>
                            </a>
                        @endif
                        <button type="submit" name="status" value="Dikirim"
                            class="px-8 py-3 text-sm font-extrabold text-white transition-all duration-200 bg-purple-600 rounded-xl hover:bg-purple-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <div class="flex items-center gap-2">
                                <i class='text-lg bx bx-send'></i>
                                <span>{{ isset($todayReport) ? 'Update & Kirim' : 'Kirim Laporan' }}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @endif

        <div id="history-section" class="overflow-hidden card shadow-soft animate-fade-in-up" style="animation-delay: 200ms">
            <div class="flex flex-col gap-4 p-6 border-b border-slate-100 bg-slate-50/50 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 text-xl text-green-600 rounded-lg bg-green-50">
                        <i class='bx bx-history'></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800">History Laporan</h4>
                    <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 border border-green-200 rounded-full">
                        {{ $approvedHistory->total() }} Laporan
                    </span>
                </div>

                <form action="{{ route('peserta.laporan.index') }}" method="GET" class="relative w-full md:w-64 history-search-form">
                    <input type="text" name="search" id="history-search-input" value="{{ request('search') }}"
                        placeholder="Cari laporan..."
                        autocomplete="off"
                        class="w-full py-2 pl-10 pr-4 text-sm transition-all border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white/80">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class='text-lg bx bx-search'></i>
                    </div>
                    <div id="search-spinner" class="absolute inset-y-0 right-0 items-center hidden pr-3 text-slate-400">
                        <i class='bx bx-loader-alt animate-spin'></i>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('peserta.laporan.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 history-clear-btn text-slate-400 hover:text-slate-600">
                            <i class='text-lg bx bx-x'></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-600 border-b bg-gray-50/50">
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">No</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Tanggal</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Judul Laporan</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px] text-center">Status</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($approvedHistory as $index => $history)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + $approvedHistory->firstItem() }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($history->tanggal_laporan)->translatedFormat('d F Y') }}</span>
                                        <span class="text-[10px] text-gray-500 font-medium uppercase">{{ \Carbon\Carbon::parse($history->tanggal_laporan)->translatedFormat('l') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-700 line-clamp-1">{{ $history->judul }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700">
                                        {{ $history->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('peserta.laporan.show', $history->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                                        <i class='bx bx-show'></i>
                                        <span>Detail</span>
                                        <i class='bx bx-chevron-right transition-transform duration-200 group-hover:translate-x-0.5'></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300">
                                            <i class='text-3xl bx bx-file-blank'></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-400">Belum ada history laporan yang disetujui</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($approvedHistory->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $approvedHistory->appends(['history_page' => $approvedHistory->currentPage()])->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite('resources/js/peserta/laporan.js')
@endsection

@push('modals')
<div id="revisionModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-fade-in">
    <div class="w-full max-w-lg overflow-hidden bg-white shadow-2xl rounded-2xl animate-fade-in-up">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-50 text-amber-600">
                    <i class='text-xl bx bx-message-rounded-dots'></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Pesan dari Admin</h3>
            </div>
            <button onclick="closeRevisionModal()" class="transition-colors text-slate-400 hover:text-slate-600">
                <i class='text-2xl bx bx-x'></i>
            </button>
        </div>
        <div class="p-8">
            <div class="p-4 border rounded-xl bg-slate-50 border-slate-100">
                <p id="revisionNoteContent" class="leading-relaxed whitespace-pre-wrap text-slate-700"></p>
            </div>
        </div>
        <div class="flex justify-end p-6 bg-slate-50">
            <button onclick="closeRevisionModal()"
                class="px-6 py-2 text-sm font-bold text-white transition-all bg-purple-600 rounded-lg hover:bg-purple-700">
                Tutup
            </button>
        </div>
    </div>
</div>
@endpush
