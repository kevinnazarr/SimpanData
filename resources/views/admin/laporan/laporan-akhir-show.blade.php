@extends('layouts.app')

@section('title', 'Detail Laporan Akhir')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.laporan.akhir.index') }}"
                class="flex items-center justify-center w-10 h-10 transition-all bg-white border border-gray-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 shadow-soft">
                <i class='text-xl bx bx-arrow-back'></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Laporan Akhir</h1>
                <p class="text-sm font-medium text-slate-500">Verifikasi laporan akhir peserta secara detail.</p>
            </div>
        </div>
        <span class="px-4 py-2 text-sm font-bold rounded-full
            {{ $laporan->status == 'Disetujui' ? 'bg-green-100 text-green-800' : '' }}
            {{ $laporan->status == 'Dikirim' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $laporan->status == 'Revisi' ? 'bg-yellow-100 text-yellow-800' : '' }}">
            {{ $laporan->status }}
        </span>
    </div>

    <div class="p-6 border md:p-8 card shadow-soft bg-slate-50/50 border-slate-200">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="flex flex-col p-6 bg-white border border-t-4 border-indigo-500 shadow-sm rounded-2xl transition-all duration-200 hover:scale-[1.02] hover:bg-slate-50 cursor-default group">
                <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-slate-400">Informasi Peserta</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 transition-colors bg-white border shadow-sm rounded-2xl group-hover:shadow-md">
                        @if($laporan->peserta->foto && Storage::disk('public')->exists($laporan->peserta->foto))
                            <img src="{{ asset('storage/' . $laporan->peserta->foto) }}"
                                alt="{{ $laporan->peserta->nama }}"
                                class="object-cover shadow-sm w-16 h-16 rounded-xl">
                        @else
                            <div class="flex items-center justify-center text-2xl font-bold text-white uppercase shadow-lg w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 shadow-indigo-100">
                                {{ substr($laporan->peserta->nama, 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-xl font-bold truncate text-slate-900">{{ $laporan->peserta->nama }}</p>
                            <p class="mt-1 text-sm font-medium leading-tight truncate text-slate-500">{{ $laporan->peserta->asal_sekolah_universitas }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-3 transition-all duration-200 bg-white border border-slate-100 rounded-xl hover:bg-indigo-50/30 group/item">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-slate-400">Jurusan</label>
                            <p class="text-sm font-bold text-slate-700">{{ $laporan->peserta->jurusan }}</p>
                        </div>
                        <div class="p-3 transition-all duration-200 bg-white border border-slate-100 rounded-xl hover:bg-indigo-50/30 group/item">
                            <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-slate-400">Periode Magang</label>
                            <div class="flex items-center gap-2 text-sm font-bold text-slate-700">
                                <i class='text-indigo-400 bx bx-calendar'></i>
                                <span>{{ $laporan->peserta->tanggal_mulai->translatedFormat('d M Y') }} - {{ $laporan->peserta->tanggal_selesai->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col p-6 bg-white border border-t-4 border-indigo-500 shadow-sm rounded-2xl transition-all duration-200 hover:scale-[1.02] hover:bg-slate-50 cursor-default group">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-slate-400">Verifikasi Laporan</h3>
                @if($laporan->status !== 'Disetujui')
                    <div class="space-y-4">
                        <form id="approveForm" action="{{ route('admin.laporan.akhir.approve', $laporan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center justify-center w-full gap-2 py-3 font-bold text-white transition-all bg-emerald-500 rounded-xl hover:bg-emerald-600 hover:shadow-lg">
                                <i class='text-xl bx bx-check-circle'></i>
                                <span>Setujui Laporan</span>
                            </button>
                        </form>

                        <div x-data="{ showRevisi: {{ $laporan->status === 'Revisi' ? 'true' : 'false' }} }">
                            <button @click="showRevisi = !showRevisi"
                                class="flex items-center justify-center w-full gap-2 py-3 text-sm font-bold transition-all bg-white border-2 text-amber-600 border-amber-500 rounded-xl hover:bg-amber-50">
                                <i class='text-xl bx bx-error-circle'></i>
                                <span>{{ $laporan->status === 'Revisi' ? 'Update Catatan' : 'Minta Revisi' }}</span>
                            </button>

                            <div x-show="showRevisi" x-transition class="mt-4 space-y-3">
                                <form id="revisiForm" action="{{ route('admin.laporan.akhir.revisi', $laporan->id) }}" method="POST" onsubmit="return validateRevisi()">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="catatan_admin"
                                                id="catatan_admin"
                                                rows="3"
                                                class="w-full p-3 text-xs transition-all bg-white border rounded-xl border-slate-200 focus:ring-2 focus:ring-amber-400 focus:border-amber-400"
                                                placeholder="Tuliskan alasan revisi..."
                                                required>{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                                    <button type="submit"
                                        class="w-full py-2 text-xs font-bold text-white transition-all rounded-lg shadow-sm bg-amber-500 hover:bg-amber-600">
                                        Kirim Revisi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full py-4 text-center transition-colors border bg-emerald-50 rounded-xl border-emerald-100 group-hover:bg-white">
                        <div class="flex items-center justify-center w-10 h-10 mb-2 font-bold bg-white rounded-full shadow-sm text-emerald-500">
                            <i class='text-2xl bx bx-check-double'></i>
                        </div>
                        <p class="text-xs font-bold text-emerald-800">Laporan Telah Disetujui</p>
                        <p class="text-[10px] text-emerald-600 mt-1">Laporan bersifat final.</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-col p-6 bg-white border border-t-4 border-indigo-500 shadow-sm rounded-2xl transition-all duration-200 hover:scale-[1.02] hover:bg-slate-50 cursor-default group">
                <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-slate-400">Informasi Tambahan</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 transition-all duration-200 border-b cursor-default">
                        <p class="text-sm font-bold tracking-widest uppercase text-slate-400">ID Laporan</p>
                        <code class="px-3 py-1 text-base font-bold text-indigo-600 transition-colors rounded-lg bg-indigo-50 group-hover:bg-indigo-100">#FA-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</code>
                    </div>
                    <div class="p-3 transition-all duration-200 cursor-default">
                        <label class="block mb-1 text-sm font-bold tracking-widest uppercase text-slate-400">Judul Laporan</label>
                        <p class="text-lg font-bold leading-tight text-slate-800">{{ $laporan->judul }}</p>
                    </div>
                    <div class="p-3 transition-all duration-200 cursor-default">
                        <label class="block mb-1 text-sm font-bold tracking-widest uppercase text-slate-400">Terkirim</label>
                        <p class="text-base font-bold text-slate-700">{{ $laporan->created_at->translatedFormat('l, d F Y') }}</p>
                        <p class="text-sm font-medium text-slate-500 mt-1">{{ $laporan->created_at->translatedFormat('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 md:p-8 card shadow-soft">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 text-slate-600">
                    <i class='text-xl bx bx-file'></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Lampiran Laporan Akhir</h3>
                    <p class="text-xs font-medium text-slate-500">Pratinjau dokumen PDF secara lengkap.</p>
                </div>
            </div>

            @if($laporan->file_path && Storage::disk('public')->exists($laporan->file_path))
                <div class="flex items-center gap-2">
                    <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                        class="inline-flex items-center justify-center w-10 h-10 text-purple-600 transition-all border border-purple-100 rounded-lg bg-purple-50 hover:bg-purple-100"
                        title="Lihat Penuh">
                        <i class='text-lg bx bx-show'></i>
                    </a>
                    <button onclick="printPDF('{{ asset('storage/' . $laporan->file_path) }}')"
                        class="inline-flex items-center justify-center w-10 h-10 transition-all border rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 border-slate-200">
                        <i class='text-lg bx bx-printer'></i>
                    </button>
                    <a href="{{ asset('storage/' . $laporan->file_path) }}" download
                        class="inline-flex items-center justify-center w-10 h-10 text-indigo-600 transition-all border border-indigo-100 rounded-lg bg-indigo-50 hover:bg-indigo-100">
                        <i class='text-lg bx bx-download'></i>
                    </a>
                </div>
            @endif
        </div>

        @if($laporan->file_path && Storage::disk('public')->exists($laporan->file_path))
            <div class="space-y-6">
                <div class="overflow-hidden border-2 border-slate-100 rounded-2xl aspect-[4/5] sm:aspect-video lg:aspect-[21/9] bg-slate-900 shadow-inner">
                    <embed src="{{ asset('storage/' . $laporan->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                            type="application/pdf"
                            class="w-full h-full transition-opacity border-none opacity-90 hover:opacity-100">
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50">
                <i class='mb-3 text-5xl text-slate-200 bx bxs-file-blank'></i>
                <p class="font-bold text-slate-400">File tidak tersedia</p>
                <p class="text-xs text-slate-300">Peserta tidak melampirkan dokumen PDF.</p>
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
