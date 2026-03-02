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

@push('modals')
{{-- Overlay bersama untuk kedua modal penilaian --}}
<div id="penilaianModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75"></div>

<div id="penilaianModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="modalTitle">Beri Penilaian</h3>
                        <p class="mt-1 text-gray-600" id="modalSubtitle">Masukkan nilai untuk peserta</p>
                    </div>
                    <button onclick="closePenilaianModal()"
                            class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                        <i class='text-2xl bx bx-x'></i>
                    </button>
                </div>

                <form id="penilaianForm">
                    <input type="hidden" id="pesertaId" name="peserta_id">
                    <input type="hidden" id="penilaianId" name="penilaian_id">

                    <div class="p-6 space-y-6">
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50">
                            <div id="pesertaAvatar" class="flex items-center justify-center w-14 h-14 text-xl font-bold text-white rounded-full bg-gradient-to-br from-indigo-500 to-purple-500">
                                A
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800" id="pesertaNama">Nama Peserta</h4>
                                <p class="text-sm text-gray-600" id="pesertaInfo">Sekolah - Jurusan</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700">
                                        <i class='mr-1 text-indigo-500 bx bx-time'></i>
                                        Kedisiplinan
                                    </label>
                                    <span class="text-lg font-bold text-indigo-600" id="kedisiplinanValue">75</span>
                                </div>
                                <input type="range" name="kedisiplinan" id="kedisiplinan" min="1" max="100" value="75"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-indigo-600"
                                    oninput="updateSliderValue('kedisiplinan')">
                                <div class="flex h-1 w-full gap-1 mt-2 mb-1">
                                    <div id="skat-kedisiplinan-1" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kedisiplinan-2" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kedisiplinan-3" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kedisiplinan-4" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" id="btn-kedisiplinan-25" onclick="setSliderValue('kedisiplinan', 25)" class="indicator-btn-kedisiplinan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-l-md border border-gray-100 transition-all">Kurang</button>
                                    <button type="button" id="btn-kedisiplinan-50" onclick="setSliderValue('kedisiplinan', 50)" class="indicator-btn-kedisiplinan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-100 transition-all">Cukup</button>
                                    <button type="button" id="btn-kedisiplinan-75" onclick="setSliderValue('kedisiplinan', 75)" class="indicator-btn-kedisiplinan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 transition-all">Baik</button>
                                    <button type="button" id="btn-kedisiplinan-100" onclick="setSliderValue('kedisiplinan', 100)" class="indicator-btn-kedisiplinan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-r-md border border-gray-100 transition-all">Sangat Baik</button>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Klik label di atas untuk mengisi nilai cepat</p>
                                <p class="text-xs text-gray-500 mt-1">Ketepatan waktu, kehadiran, dan kepatuhan terhadap aturan</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700">
                                        <i class='mr-1 text-purple-500 bx bx-code-alt'></i>
                                        Keterampilan
                                    </label>
                                    <span class="text-lg font-bold text-purple-600" id="keterampilanValue">75</span>
                                </div>
                                <input type="range" name="keterampilan" id="keterampilan" min="1" max="100" value="75"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-purple-600"
                                    oninput="updateSliderValue('keterampilan')">
                                <div class="flex h-1 w-full gap-1 mt-2 mb-1">
                                    <div id="skat-keterampilan-1" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-keterampilan-2" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-keterampilan-3" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-keterampilan-4" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" id="btn-keterampilan-25" onclick="setSliderValue('keterampilan', 25)" class="indicator-btn-keterampilan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-l-md border border-gray-100 transition-all">Kurang</button>
                                    <button type="button" id="btn-keterampilan-50" onclick="setSliderValue('keterampilan', 50)" class="indicator-btn-keterampilan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-100 transition-all">Cukup</button>
                                    <button type="button" id="btn-keterampilan-75" onclick="setSliderValue('keterampilan', 75)" class="indicator-btn-keterampilan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 transition-all">Baik</button>
                                    <button type="button" id="btn-keterampilan-100" onclick="setSliderValue('keterampilan', 100)" class="indicator-btn-keterampilan text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-r-md border border-gray-100 transition-all">Sangat Baik</button>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Klik label di atas untuk mengisi nilai cepat</p>
                                <p class="text-xs text-gray-500 mt-1">Kemampuan teknis dalam menyelesaikan tugas</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700">
                                        <i class='mr-1 text-emerald-500 bx bx-group'></i>
                                        Kerjasama
                                    </label>
                                    <span class="text-lg font-bold text-emerald-600" id="kerjasamaValue">75</span>
                                </div>
                                <input type="range" name="kerjasama" id="kerjasama" min="1" max="100" value="75"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-emerald-600"
                                    oninput="updateSliderValue('kerjasama')">
                                <div class="flex h-1 w-full gap-1 mt-2 mb-1">
                                    <div id="skat-kerjasama-1" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kerjasama-2" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kerjasama-3" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-kerjasama-4" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" id="btn-kerjasama-25" onclick="setSliderValue('kerjasama', 25)" class="indicator-btn-kerjasama text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-l-md border border-gray-100 transition-all">Kurang</button>
                                    <button type="button" id="btn-kerjasama-50" onclick="setSliderValue('kerjasama', 50)" class="indicator-btn-kerjasama text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-100 transition-all">Cukup</button>
                                    <button type="button" id="btn-kerjasama-75" onclick="setSliderValue('kerjasama', 75)" class="indicator-btn-kerjasama text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 transition-all">Baik</button>
                                    <button type="button" id="btn-kerjasama-100" onclick="setSliderValue('kerjasama', 100)" class="indicator-btn-kerjasama text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-r-md border border-gray-100 transition-all">Sangat Baik</button>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Klik label di atas untuk mengisi nilai cepat</p>
                                <p class="text-xs text-gray-500 mt-1">Kemampuan bekerja dalam tim dan berkolaborasi</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700">
                                        <i class='mr-1 text-amber-500 bx bx-bulb'></i>
                                        Inisiatif
                                    </label>
                                    <span class="text-lg font-bold text-amber-600" id="inisiatifValue">75</span>
                                </div>
                                <input type="range" name="inisiatif" id="inisiatif" min="1" max="100" value="75"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-amber-600"
                                    oninput="updateSliderValue('inisiatif')">
                                <div class="flex h-1 w-full gap-1 mt-2 mb-1">
                                    <div id="skat-inisiatif-1" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-inisiatif-2" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-inisiatif-3" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-inisiatif-4" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" id="btn-inisiatif-25" onclick="setSliderValue('inisiatif', 25)" class="indicator-btn-inisiatif text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-l-md border border-gray-100 transition-all">Kurang</button>
                                    <button type="button" id="btn-inisiatif-50" onclick="setSliderValue('inisiatif', 50)" class="indicator-btn-inisiatif text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-100 transition-all">Cukup</button>
                                    <button type="button" id="btn-inisiatif-75" onclick="setSliderValue('inisiatif', 75)" class="indicator-btn-inisiatif text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 transition-all">Baik</button>
                                    <button type="button" id="btn-inisiatif-100" onclick="setSliderValue('inisiatif', 100)" class="indicator-btn-inisiatif text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-r-md border border-gray-100 transition-all">Sangat Baik</button>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Klik label di atas untuk mengisi nilai cepat</p>
                                <p class="text-xs text-gray-500 mt-1">Keaktifan, kreativitas, dan proaktif dalam bekerja</p>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-700">
                                        <i class='mr-1 text-blue-500 bx bx-message-rounded-dots'></i>
                                        Komunikasi
                                    </label>
                                    <span class="text-lg font-bold text-blue-600" id="komunikasiValue">75</span>
                                </div>
                                <input type="range" name="komunikasi" id="komunikasi" min="1" max="100" value="75"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200 accent-blue-600"
                                    oninput="updateSliderValue('komunikasi')">
                                <div class="flex h-1 w-full gap-1 mt-2 mb-1">
                                    <div id="skat-komunikasi-1" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-komunikasi-2" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-komunikasi-3" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                    <div id="skat-komunikasi-4" class="h-full flex-1 rounded-full bg-gray-100 transition-all duration-300"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" id="btn-komunikasi-25" onclick="setSliderValue('komunikasi', 25)" class="indicator-btn-komunikasi text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-l-md border border-gray-100 transition-all">Kurang</button>
                                    <button type="button" id="btn-komunikasi-50" onclick="setSliderValue('komunikasi', 50)" class="indicator-btn-komunikasi text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 border border-gray-100 transition-all">Cukup</button>
                                    <button type="button" id="btn-komunikasi-75" onclick="setSliderValue('komunikasi', 75)" class="indicator-btn-komunikasi text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 transition-all">Baik</button>
                                    <button type="button" id="btn-komunikasi-100" onclick="setSliderValue('komunikasi', 100)" class="indicator-btn-komunikasi text-[10px] font-medium py-1.5 px-1 bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-r-md border border-gray-100 transition-all">Sangat Baik</button>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Klik label di atas untuk mengisi nilai cepat</p>
                                <p class="text-xs text-gray-500 mt-1">Kemampuan berkomunikasi dengan baik</p>
                            </div>
                        </div>

                        <div class="p-4 text-center rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50">
                            <p class="text-sm font-medium text-gray-600">Nilai Akhir</p>
                            <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600" id="nilaiAkhirPreview">
                                75
                            </p>
                            <p class="text-sm text-gray-500">Grade: <span class="font-semibold" id="gradePreview">B</span></p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">
                                <i class='mr-1 text-gray-500 bx bx-note'></i>
                                Catatan (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                placeholder="Tambahkan catatan atau feedback untuk peserta..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                        <button type="button" onclick="closePenilaianModal()"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                            <i class='bx bx-save'></i>
                            <span id="submitBtnText">Simpan Penilaian</span>
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Detail Penilaian</h3>
                        <p class="mt-1 text-gray-600" id="detailPesertaNama">Nama Peserta</p>
                    </div>
                    <button onclick="closeDetailModal()"
                            class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                        <i class='text-2xl bx bx-x'></i>
                    </button>
                </div>

                <div class="p-6" id="detailContent">
                </div>

                <div class="flex justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                    <button type="button" onclick="closeDetailModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Tutup
                    </button>
                    <button type="button" onclick="editFromDetail()"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                        <i class='bx bx-edit'></i>
                        <span>Edit Nilai</span>
                    </button>
                </div>
        </div>
    </div>
</div>
@endpush

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
