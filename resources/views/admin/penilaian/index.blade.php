@extends('layouts.app')

@section('title', 'Penilaian Peserta')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                        Penilaian Peserta
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola penilaian peserta PKL dan Magang
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-indigo-500 to-purple-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Total Peserta
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalPeserta">
                                    {{ $totalPeserta }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta aktif & selesai</p>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20">
                                    <i class="text-2xl text-white bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-emerald-500 to-teal-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Sudah Dinilai
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statSudahDinilai">
                                    {{ $sudahDinilai }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20">
                                    <i class="text-2xl text-white bx bx-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-amber-500 to-orange-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Belum Dinilai
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statBelumDinilai">
                                    {{ $belumDinilai }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20">
                                    <i class="text-2xl text-white bx bx-time-five"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden transition-all duration-300 border rounded-lg shadow-sm bg-gradient-to-br from-blue-500 to-indigo-500 hover:shadow-xl hover:-translate-y-1 group border-white/10">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Rata-rata Nilai
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statRataRata">
                                    {{ number_format($rataRataNilai, 1) }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Dari 100</p>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20">
                                    <i class="text-2xl text-white bx bx-star"></i>
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
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1">
                    <div class="relative">
                        <i class='absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2 bx bx-search'></i>
                        <input type="text"
                                id="searchInput"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, sekolah, atau jurusan..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative">
                        <select id="filterJenisKegiatan"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Jenis</option>
                            <option value="PKL" {{ request('jenis_kegiatan') == 'PKL' ? 'selected' : '' }}>PKL</option>
                            <option value="Magang" {{ request('jenis_kegiatan') == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <div class="relative">
                        <select id="filterSekolah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10 min-w-[200px]">
                            <option value="">Semua Sekolah/Universitas</option>
                            @foreach($sekolahs as $s)
                                <option value="{{ $s->asal_sekolah_universitas }}" {{ request('sekolah') == $s->asal_sekolah_universitas ? 'selected' : '' }}>
                                    {{ $s->asal_sekolah_universitas }}
                                </option>
                            @endforeach
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <div class="relative">
                        <select id="filterStatusPenilaian"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Status</option>
                            <option value="sudah" {{ request('status_penilaian') == 'sudah' ? 'selected' : '' }}>Sudah Dinilai</option>
                            <option value="belum" {{ request('status_penilaian') == 'belum' ? 'selected' : '' }}>Belum Dinilai</option>
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <button onclick="resetFilters()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 whitespace-nowrap">
                        <i class='bx bx-refresh'></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-4 md:p-5" id="pesertaGridContainer">
            @include('admin.penilaian.partials.peserta-grid', ['peserta' => $peserta])
        </div>
    </div>
@endsection

@push('styles')
@vite('resources/css/admin/penilaian.css')
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.penilaianConfig = {
        indexUrl: '{{ route('admin.penilaian.index') }}',
        storeUrl: '{{ route('admin.penilaian.store') }}',
        pesertaGridUrl: '{{ route('admin.penilaian.peserta-grid') }}',
        showUrl: '{{ url('admin/penilaian') }}',
        updateUrl: '{{ url('admin/penilaian') }}',
        csrfToken: '{{ csrf_token() }}'
    };
</script>
@vite('resources/js/admin/penilaian.js')
@endsection
