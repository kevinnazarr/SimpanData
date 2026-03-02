@extends('layouts.app')

@section('title', 'Arsip Peserta')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">Arsip Peserta</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Peserta yang periode kegiatannya telah berakhir. Data akan dihapus otomatis setelah <strong>1 bulan</strong> diarsipkan.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 text-white">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                        <i class='text-2xl bx bx-archive'></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Total Diarsipkan</p>
                        <h3 class="text-3xl font-bold">{{ $totalArsip }}</h3>
                        <p class="text-xs text-white/60">Peserta</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-white">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                        <i class='text-2xl bx bx-error-circle'></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Akan Dihapus (≤7 hari)</p>
                        <h3 class="text-3xl font-bold">{{ $akanDihapus }}</h3>
                        <p class="text-xs text-white/60">Peserta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 card">
        <div class="p-4 md:p-5">
            <div class="flex flex-col gap-3 md:flex-row">
                <div class="flex-1 relative">
                    <i class='absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2 bx bx-search'></i>
                    <input type="text" id="searchInput" placeholder="Cari nama, sekolah..."
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>
                <div class="relative">
                    <select id="filterJenis"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 appearance-none bg-white pr-10">
                        <option value="">Semua Jenis</option>
                        <option value="PKL"   {{ request('jenis_kegiatan') == 'PKL'   ? 'selected' : '' }}>PKL</option>
                        <option value="Magang" {{ request('jenis_kegiatan') == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                </div>
                <button onclick="resetFilter()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class='bx bx-refresh'></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div class="card" id="arsipTableCard">
        @include('admin.arsip.partials.arsip-table', ['pesertaArsip' => $pesertaArsip])
    </div>
@endsection

@push('styles')
    @vite('resources/css/admin/arsip.css')
@endpush

@section('scripts')
<script>
    window.arsipConfig = {
        csrfToken : '{{ csrf_token() }}',
        arsipUrl  : '{{ route('admin.arsip.index') }}',
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite('resources/js/admin/arsip.js')
@endsection

