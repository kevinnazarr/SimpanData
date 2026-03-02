@extends('layouts.app')

@section('title', 'Detail Laporan Harian')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center justify-center w-10 h-10 transition-all bg-white border border-gray-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 shadow-soft">
                <i class='text-xl bx bx-arrow-back'></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Detail Laporan</h2>
                <p class="text-sm text-slate-500">Verifikasi laporan harian peserta secara detail.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @php
                $statusClasses = [
                    'Draft' => 'bg-gray-100 text-gray-600',
                    'Dikirim' => 'bg-blue-100 text-blue-600 border-blue-200',
                    'Disetujui' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                    'Revisi' => 'bg-amber-100 text-amber-600 border-amber-200',
                ];
            @endphp
            <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $statusClasses[$laporan->status] ?? 'bg-gray-100 text-gray-600' }}">
                Status: {{ $laporan->status }}
            </span>
        </div>
    </div>

    {{-- Layout Split: Utama (2/3) vs Sidebar (1/3) --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-stretch">
        {{-- Left: Deskripsi Kegiatan (2/3) --}}
        <div class="lg:col-span-2">
            <div class="p-8 bg-white border border-gray-100 shadow-soft rounded-2xl h-full flex flex-col">
                <div class="mb-6">
                    <h1 class="text-2xl font-black text-slate-900 leading-tight">{{ $laporan->judul }}</h1>
                </div>

                <div class="flex-1 flex flex-col min-h-0">
                    <h4 class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-3">Deskripsi Kegiatan</h4>
                    {{-- Fixed height container to match sidebar and allow scroll --}}
                    <div class="flex-1 p-6 text-sm leading-relaxed whitespace-pre-wrap rounded-xl bg-slate-50 border border-slate-100 text-slate-700 overflow-y-auto custom-scrollbar h-[500px] lg:h-auto lg:max-h-[500px]">
{{ $laporan->deskripsi }}</div>
                </div>
            </div>
        </div>

        {{-- Right: Sidebar (1/3) --}}
        <div class="lg:col-span-1 space-y-6 flex flex-col">
            <div class="p-6 bg-white border border-gray-100 shadow-soft rounded-2xl">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-slate-400">Informasi Peserta</h3>
                <div class="flex items-center gap-4">
                    @if($laporan->peserta->foto && Storage::disk('public')->exists($laporan->peserta->foto))
                        <img src="{{ asset('storage/' . $laporan->peserta->foto) }}"
                            alt="{{ $laporan->peserta->nama }}"
                            class="w-12 h-12 object-cover rounded-2xl shadow-soft">
                    @else
                        <div class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-indigo-200 shadow-lg">
                            {{ strtoupper(substr($laporan->peserta->nama, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-bold leading-tight text-slate-800">{{ $laporan->peserta->nama }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $laporan->peserta->asal_sekolah_universitas }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-100 shadow-soft rounded-2xl">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-slate-400">Tindakan Admin</h3>

                @if($laporan->status !== 'Disetujui')
                    <div class="space-y-4">
                        <form id="approveForm" action="{{ route('admin.laporan.harian.approve', $laporan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center justify-center w-full gap-2 py-3 font-bold text-white transition-all bg-emerald-500 rounded-xl hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-100">
                                <i class='text-xl bx bx-check-circle'></i>
                                <span>Setujui Laporan</span>
                            </button>
                        </form>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-slate-100"></span></div>
                            <div class="relative flex justify-center text-xs uppercase"><span class="px-2 bg-white text-slate-400">Atau</span></div>
                        </div>

                        <div x-data="{ showRevisi: {{ $laporan->status === 'Revisi' ? 'true' : 'false' }} }">
                            <button @click="showRevisi = !showRevisi"
                                class="flex items-center justify-center w-full gap-2 py-3 font-bold transition-all border-2 text-amber-600 border-amber-500 rounded-xl hover:bg-amber-50">
                                <i class='text-xl bx bx-error-circle'></i>
                                <span>{{ $laporan->status === 'Revisi' ? 'Update Catatan Revisi' : 'Minta Revisi' }}</span>
                            </button>

                            <div x-show="showRevisi" x-transition class="mt-4 space-y-3">
                                <form id="revisiForm" action="{{ route('admin.laporan.harian.revisi', $laporan->id) }}" method="POST" onsubmit="return validateRevisi()">
                                    @csrf
                                    @method('PATCH')
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1 block">Catatan Revisi</label>
                                    <textarea name="catatan_admin"
                                                id="catatan_admin"
                                                rows="4"
                                                class="w-full p-3 text-sm border-2 rounded-xl border-slate-100 focus:border-amber-400 focus:ring-0"
                                                placeholder="Tuliskan alasan revisi secara mendetail agar peserta mengerti..."
                                                required>{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                                    @error('catatan_admin')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                    <button type="submit"
                                        class="w-full py-2.5 mt-2 bg-amber-500 text-white rounded-lg text-sm font-bold hover:bg-amber-600 transition-all">
                                        {{ $laporan->status === 'Revisi' ? 'Update Revisi' : 'Kirim Revisi' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <div class="flex items-center justify-center w-12 h-12 mb-3 text-emerald-500 bg-emerald-50 rounded-full">
                            <i class='text-3xl bx bx-check-double'></i>
                        </div>
                        <p class="text-sm font-bold text-slate-800">Laporan Telah Disetujui</p>
                        <p class="text-xs text-slate-500">Laporan ini bersifat final dan tidak memerlukan tindakan lebih lanjut.</p>
                    </div>
                @endif
            </div>

            <div class="p-6 bg-white border border-gray-100 shadow-soft rounded-2xl mt-auto">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-slate-400">Rincian Laporan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tanggal Posting</p>
                        <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">ID Laporan</p>
                        <code class="px-2 py-0.5 mt-1 text-[10px] font-bold text-indigo-600 bg-indigo-50 rounded">#{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Foot: Full Width PDF Preview (Tetap Full Width) --}}
    <div class="p-8 bg-white border border-gray-100 shadow-soft rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-xs font-bold tracking-widest uppercase text-slate-400">Lampiran Dokumen (PDF)</h3>
            
            @if($laporan->file_path && Storage::disk('public')->exists($laporan->file_path))
                <div class="flex items-center gap-3">
                    <a href="{{ asset('storage/' . $laporan->file_path) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                        <i class='bx bx-download'></i>
                        <span>Unduh Dokumen</span>
                    </a>
                    <button type="button" 
                            onclick="printPDF('{{ asset('storage/' . $laporan->file_path) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
                        <i class='bx bx-printer'></i>
                        <span>Cetak</span>
                    </button>
                </div>
            @endif
        </div>

        @if($laporan->file_path && Storage::disk('public')->exists($laporan->file_path))
            <div class="overflow-hidden border-2 border-slate-100 rounded-2xl aspect-[4/5] sm:aspect-video lg:aspect-[21/9] bg-slate-900">
                <embed src="{{ asset('storage/' . $laporan->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                        type="application/pdf"
                        id="pdfEmbed"
                        class="w-full h-full border-none">
            </div>
            <p class="mt-4 text-xs text-slate-500"><i class='bx bx-info-circle mr-1'></i>Gunakan fitur browser untuk melihat dokumen dalam ukuran penuh jika diperlukan.</p>
        @else
            <div class="flex flex-col items-center justify-center p-12 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50">
                <i class='text-5xl text-slate-200 bx bxs-file-blank mb-3'></i>
                <p class="font-bold text-slate-400">File tidak tersedia</p>
                <p class="text-xs text-slate-300">Peserta mungkin tidak melampirkan dokumen.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function printPDF(url) {
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        iframe.contentWindow.onload = function() {
            iframe.contentWindow.print();
        };
    }

    function validateRevisi() {
        const catatan = document.getElementById('catatan_admin').value.trim();
        if (!catatan) {
            Swal.fire({
                icon: 'error',
                title: 'Catatan Wajib Diisi!',
                text: 'Berikan feedback agar peserta tahu apa yang harus diperbaiki.',
                confirmButtonColor: '#F59E0B'
            });
            return false;
        }
        return true;
    }
</script>
@endsection
