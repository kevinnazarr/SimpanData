@extends('layouts.app')

@section('title', 'Laporan Akhir')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 p-6 md:flex-row md:items-center card shadow-soft animate-fade-in">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 text-2xl text-purple-600 shadow-inner rounded-xl bg-purple-50">
                    <i class='bx bx-file'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Laporan Akhir</h1>
                    <p class="text-sm font-medium text-slate-500">Kirimkan laporan akhir kegiatan PKL/Magang Anda.</p>
                </div>
            </div>

            @if($laporanAkhir)
                <div class="px-4 py-2 border rounded-xl animate-fade-in {{ $laporanAkhir->status == 'Disetujui' ? 'border-green-100 bg-green-50' : ($laporanAkhir->status == 'Revisi' ? 'border-yellow-100 bg-yellow-50' : 'border-blue-100 bg-blue-50') }}">
                    <p class="text-xs font-bold tracking-tighter uppercase {{ $laporanAkhir->status == 'Disetujui' ? 'text-green-600' : ($laporanAkhir->status == 'Revisi' ? 'text-yellow-600' : 'text-blue-600') }}">
                        Status Laporan Akhir
                    </p>
                    <p class="text-sm font-extrabold {{ $laporanAkhir->status == 'Disetujui' ? 'text-green-900' : ($laporanAkhir->status == 'Revisi' ? 'text-yellow-900' : 'text-blue-900') }}">
                        {{ $laporanAkhir->status }}
                    </p>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                <div class="flex items-center">
                    <i class='mr-2 text-xl bx bx-check-circle'></i>
                    <p class="font-bold">Berhasil!</p>
                </div>
                <p class="mt-1 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded-r-lg shadow-sm animate-fade-in" role="alert">
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

        @if($laporanAkhir && $laporanAkhir->status == 'Revisi')
            <div class="flex items-center justify-between p-4 mb-6 border-l-4 rounded-r-lg shadow-sm text-amber-800 bg-amber-50 border-amber-500 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100/50 text-amber-600">
                        <i class='text-2xl bx bx-revision'></i>
                    </div>
                    <div>
                        <p class="font-bold">Laporan Akhir Perlu Revisi!</p>
                        <p class="text-sm">Silakan periksa catatan revisi dan perbaiki laporan Anda.</p>
                    </div>
                </div>
                <button type="button"
                    onclick="showRevisionModal('{{ str_replace(["\r", "\n"], ['\r', '\n'], addslashes($laporanAkhir->catatan_admin ?? '')) }}')"
                    class="px-4 py-2 text-xs font-bold transition border rounded-lg shadow-sm bg-amber-100 text-amber-800 hover:bg-amber-200 whitespace-nowrap border-amber-200">
                    Lihat Pesan dari Admin
                </button>
            </div>
        @endif

        @if($laporanAkhir && in_array($laporanAkhir->status, ['Dikirim', 'Disetujui']))
            <div class="p-8 text-center border-2 border-dashed rounded-xl animate-fade-in-up
                {{ $laporanAkhir->status == 'Disetujui' ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-slate-50' }}">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="flex items-center justify-center w-20 h-20 mb-2 rounded-full
                        {{ $laporanAkhir->status == 'Disetujui' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                        <i class='text-4xl bx {{ $laporanAkhir->status == 'Disetujui' ? 'bx-check-double' : 'bx-time-five' }}'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">
                            {{ $laporanAkhir->status == 'Disetujui' ? 'Laporan Akhir Terverifikasi' : 'Laporan Akhir Sudah Dikirim' }}
                        </h3>
                        <p class="mt-2 text-slate-500">
                            {{ $laporanAkhir->status == 'Disetujui'
                                ? 'Selamat! Laporan akhir Anda telah disetujui oleh admin.'
                                : 'Terima kasih telah mengirimkan laporan akhir. Menunggu review dari admin.' }}
                        </p>
                    </div>
                    <a href="{{ route('peserta.laporan.akhir.show', $laporanAkhir->id) }}"
                        class="px-6 py-3 mt-4 text-sm font-bold text-white transition-all duration-200 rounded-lg shadow-lg
                            {{ $laporanAkhir->status == 'Disetujui'
                                ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                                : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' }}
                            focus:outline-none focus:ring-2 focus:ring-offset-2 inline-flex items-center gap-2">
                        <i class='text-lg bx bx-show'></i>
                        <span>Lihat Laporan Saya</span>
                    </a>
                </div>
            </div>
        @else
            <form action="{{ $laporanAkhir ? route('peserta.laporan.akhir.update', $laporanAkhir->id) : route('peserta.laporan.akhir.store') }}"
                method="POST" enctype="multipart/form-data" id="report-form" class="space-y-6">
                @csrf
                <input type="hidden" name="status" id="status-field" value="Dikirim">
                @if($laporanAkhir)
                    @method('PUT')
                @endif

                <div class="p-6 card shadow-soft md:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                        <div class="flex items-center justify-center w-10 h-10 text-xl text-purple-600 rounded-lg bg-purple-50">
                            <i class='bx bx-cloud-upload'></i>
                        </div>
                        <h4 class="text-lg font-bold uppercase text-slate-800">Unggah Laporan Akhir</h4>
                    </div>

                    <div class="space-y-6">
                        <div id="drop-zone"
                            class="relative flex flex-col items-center justify-center p-12 transition-all duration-300 border-2 border-dashed cursor-pointer rounded-2xl group border-slate-200 hover:border-purple-400 hover:bg-purple-50/30">
                            <input type="file" id="file" name="file" accept="application/pdf" class="absolute inset-0 opacity-0 cursor-pointer" required>

                            <div class="flex flex-col items-center space-y-4 text-center" id="upload-placeholder">
                                <div class="flex items-center justify-center w-20 h-20 text-4xl text-purple-500 transition-transform duration-300 bg-purple-100 rounded-full group-hover:scale-110">
                                    <i class='bx bxs-file-pdf'></i>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-slate-700">Darag & Drop</p>
                                    <p class="text-sm text-slate-500">atau klik untuk memilih file dari komputer Anda</p>
                                </div>
                                <div class="px-4 py-1.5 text-[10px] font-bold tracking-widest text-purple-600 uppercase bg-purple-100 rounded-full">
                                    PDF (Maks. 10MB)
                                </div>
                            </div>

                            <div class="flex-col items-center hidden w-full space-y-4" id="file-preview">
                                <div class="relative w-full overflow-hidden border border-slate-200 rounded-xl bg-slate-50 aspect-[4/3] md:aspect-video">
                                    <iframe id="pdf-viewer" class="w-full h-full rounded-lg" frameborder="0"></iframe>
                                    <div class="absolute top-4 right-4 animate-fade-in">
                                        <button type="button" onclick="resetFileSelection(event)"
                                            class="flex items-center justify-center w-10 h-10 text-white transition-all bg-red-500 rounded-full shadow-lg hover:bg-red-600 hover:scale-110">
                                            <i class='text-xl bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center w-full gap-4 p-4 bg-white border rounded-xl border-slate-100">
                                    <div class="flex items-center justify-center w-12 h-12 text-2xl text-red-500 rounded-lg bg-red-50">
                                        <i class='bx bxs-file-pdf'></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold truncate text-slate-900" id="file-name"></p>
                                        <p class="text-xs text-slate-500" id="file-size"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 pt-6 border-t border-slate-100 sm:flex-row sm:justify-end">
                            <button type="submit" name="status" value="Dikirim" id="submit-btn"
                                class="flex items-center justify-center gap-2 px-8 py-3 text-sm font-extrabold text-white transition-all duration-200 bg-purple-600 rounded-xl hover:bg-purple-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class='text-lg bx bx-send'></i>
                                <span>{{ $laporanAkhir ? 'Update & Kirim' : 'Kirim Laporan' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                const dropZone = document.getElementById('drop-zone');
                const fileInput = document.getElementById('file');
                const placeholder = document.getElementById('upload-placeholder');
                const preview = document.getElementById('file-preview');
                const pdfViewer = document.getElementById('pdf-viewer');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const submitBtn = document.getElementById('submit-btn');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.add('border-purple-500', 'bg-purple-50');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.remove('border-purple-500', 'bg-purple-50');
                    }, false);
                });

                dropZone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;
                    handleFiles(files);
                }, false);

                fileInput.addEventListener('change', (e) => {
                    handleFiles(e.target.files);
                });

                function handleFiles(files) {
                    if (files.length > 0) {
                        const file = files[0];
                        if (file.type === 'application/pdf') {
                            if (file.size <= 10 * 1024 * 1024) {
                                const url = URL.createObjectURL(file);
                                pdfViewer.src = url;
                                fileName.textContent = file.name;
                                fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

                                placeholder.classList.add('hidden');
                                preview.classList.remove('hidden');
                                submitBtn.disabled = false;
                            } else {
                                alert('Ukuran file maksimal 10MB');
                                resetFileSelection();
                            }
                        } else {
                            alert('Hanya file PDF yang diperbolehkan');
                            resetFileSelection();
                        }
                    }
                }

                function resetFileSelection(e) {
                    if (e) e.stopPropagation();
                    fileInput.value = '';
                    pdfViewer.src = '';
                    placeholder.classList.remove('hidden');
                    preview.classList.add('hidden');
                    submitBtn.disabled = {{ $laporanAkhir ? 'false' : 'true' }};
                }

                @if(!$laporanAkhir)
                    submitBtn.disabled = true;
                @endif
            </script>
        @endif

        {{-- History Section (Sync with daily report style) --}}
        <div id="history-section" class="overflow-hidden card shadow-soft animate-fade-in-up" style="animation-delay: 200ms">
            <div class="flex flex-col gap-4 p-6 border-b border-slate-100 bg-slate-50/50 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 text-xl text-purple-600 rounded-lg bg-purple-50">
                        <i class='bx bx-history'></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800">Riwayat Laporan Akhir</h4>
                    <span class="px-3 py-1 text-xs font-bold text-purple-700 bg-purple-100 border border-purple-200 rounded-full">
                        {{ $historyLaporanAkhir->total() }} Laporan
                    </span>
                </div>

                <form action="{{ route('peserta.laporan.akhir') }}" method="GET" class="relative w-full md:w-64 history-search-form">
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
                        <a href="{{ route('peserta.laporan.akhir') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 history-clear-btn text-slate-400 hover:text-slate-600">
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
                            @forelse ($historyLaporanAkhir as $index => $history)
                                <tr class="transition-colors hover:bg-gray-50/50">
                                    <td class="px-4 py-3 text-gray-500">{{ $index + $historyLaporanAkhir->firstItem() }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900">{{ $history->created_at->translatedFormat('d F Y') }}</span>
                                            <span class="text-[10px] text-gray-500 font-medium uppercase">{{ $history->created_at->translatedFormat('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-700 line-clamp-1">{{ $history->judul }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $statusClasses = [
                                                'Dikirim'   => 'bg-blue-100 text-blue-700',
                                                'Disetujui' => 'bg-emerald-100 text-emerald-700',
                                                'Revisi'    => 'bg-amber-100 text-amber-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium {{ $statusClasses[$history->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $history->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('peserta.laporan.akhir.show', $history->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                                            <i class='bx bx-show'></i>
                                            <span>Detail</span>
                                            <i class='bx bx-chevron-right transition-transform duration-200 group-hover:translate-x-0.5'></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <i class='mb-2 text-4xl text-slate-200 bx bx-file-blank'></i>
                                            <p class="text-sm">Belum ada riwayat laporan akhir.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($historyLaporanAkhir->hasPages())
                    <div class="px-4 py-4 border-t border-gray-100 bg-gray-50/30">
                        {{ $historyLaporanAkhir->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
