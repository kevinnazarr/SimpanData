@extends('layouts.app')

@section('title', 'Detail Laporan Akhir')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Laporan Akhir</h1>
            <p class="mt-1 text-sm text-slate-600">Informasi lengkap laporan akhir Anda</p>
        </div>
        <a href="{{ route('peserta.laporan.akhir') }}"
            class="px-4 py-2 text-sm font-bold transition-all duration-200 border-2 rounded-lg text-slate-700 border-slate-300 hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
            <div class="flex items-center gap-2">
                <i class='text-base bx bx-arrow-back'></i>
                <span>Kembali</span>
            </div>
        </a>
    </div>

    <div class="p-6 card shadow-soft md:p-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $laporan->judul }}</h2>
                <p class="mt-1 text-sm text-slate-500">
                    <i class='bx bx-calendar'></i>
                    Terkirim pada {{ $laporan->created_at->format('l, d F Y') }}
                </p>
            </div>
            <span class="px-4 py-2 text-sm font-bold rounded-full
                {{ $laporan->status == 'Disetujui' ? 'bg-green-100 text-green-800' : '' }}
                {{ $laporan->status == 'Dikirim' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $laporan->status == 'Revisi' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                {{ $laporan->status }}
            </span>
        </div>

        <div class="space-y-6">
            @if($laporan->status == 'Revisi' && $laporan->catatan_admin)
                <div class="p-4 border-l-4 border-yellow-500 rounded-lg shadow-sm bg-yellow-50">
                    <div class="flex items-center gap-2 mb-2 text-yellow-700">
                        <i class='text-xl bx bx-info-circle'></i>
                        <h5 class="font-bold">Catatan Revisi dari Admin:</h5>
                    </div>
                    <div class="p-3 bg-white border border-yellow-100 rounded-lg text-slate-700">
                        {!! nl2br(e($laporan->catatan_admin)) !!}
                    </div>
                </div>
            @endif

            <div>
                <h3 class="mb-3 text-sm font-bold tracking-widest uppercase text-slate-700">Ringkasan Kegiatan</h3>
                <div class="p-4 border rounded-lg bg-slate-50 border-slate-200">
                    <p class="text-sm leading-relaxed whitespace-pre-wrap text-slate-700">{{ $laporan->deskripsi }}</p>
                </div>
            </div>

            @if($laporan->file_path)
                <div>
                    <h3 class="mb-3 text-sm font-bold tracking-widest uppercase text-slate-700">Lampiran File</h3>
                    <div class="flex items-center gap-3 p-4 border rounded-lg bg-slate-50 border-slate-200">
                        <div class="flex items-center justify-center w-12 h-12 text-2xl rounded-lg text-slate-600 bg-slate-100">
                            <i class='bx bx-file'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">{{ basename($laporan->file_path) }}</p>
                            <p class="text-xs text-slate-500">
                                Ukuran: {{ number_format(Storage::disk('public')->size($laporan->file_path) / 1024, 2) }} KB
                            </p>
                        </div>
                        <a href="{{ Storage::url($laporan->file_path) }}"
                            target="_blank"
                            class="px-4 py-2 text-sm font-bold text-white transition-all duration-200 bg-purple-600 rounded-lg hover:bg-purple-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i class='bx bx-show'></i> Lihat PDF
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 border rounded-lg bg-slate-50 border-slate-200">
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Dibuat Pada</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $laporan->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="p-4 border rounded-lg bg-slate-50 border-slate-200">
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Terakhir Diupdate</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $laporan->updated_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
