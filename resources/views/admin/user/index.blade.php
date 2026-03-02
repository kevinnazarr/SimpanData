@extends('layouts.app')

@section('title', 'Data Akun Peserta')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg" id="pageTitle">
                        @php
                            $title = 'Data Akun Peserta';
                            if (request('profile_status') === 'complete') {
                                $title = 'Akun dengan Profil Terisi';
                            } elseif (request('profile_status') === 'incomplete') {
                                $title = 'Akun dengan Profil Belum Terisi';
                            } elseif (request('asal_sekolah_universitas')) {
                                $title = 'Akun dari ' . request('asal_sekolah_universitas');
                            } elseif (request('search')) {
                                $title = 'Hasil Pencarian';
                            }
                        @endphp
                        {{ $title }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600" id="pageSubtitle">
                        @php
                            $subtitle = 'Kelola akun peserta PKL dan Magang';
                            if (request('profile_status') === 'complete') {
                                $subtitle = 'Menampilkan akun yang sudah melengkapi data profil';
                            } elseif (request('profile_status') === 'incomplete') {
                                $subtitle = 'Menampilkan akun yang belum melengkapi data profil';
                            } elseif (request('asal_sekolah_universitas')) {
                                $subtitle = 'Filter berdasarkan sekolah/universitas';
                            } elseif (request('search')) {
                                $subtitle = 'Pencarian: "' . request('search') . '"';
                            }
                        @endphp
                        {{ $subtitle }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div onclick="filterByTotal()"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-indigo-500 to-purple-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Total Akun Peserta
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalPeserta">
                                    {{ $totalPeserta }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Akun</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div onclick="filterByStatus('complete')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-emerald-500 to-teal-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Profil Terisi
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statProfileComplete">
                                    {{ $profileComplete }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Akun</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div onclick="filterByStatus('incomplete')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-amber-500 to-orange-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Profil Belum Terisi
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statProfileIncomplete">
                                    {{ $profileIncomplete }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Akun</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-x-circle"></i>
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
                                placeholder="Cari username, email, atau nama..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative">
                        <select id="filterProfileStatus"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Status Profil</option>
                            <option value="complete" {{ request('profile_status') == 'complete' ? 'selected' : '' }}>Profil Terisi</option>
                            <option value="incomplete" {{ request('profile_status') == 'incomplete' ? 'selected' : '' }}>Profil Belum Terisi</option>
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <div class="relative">
                        <select id="filterAsalSekolah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Sekolah/Universitas</option>
                            @foreach ($sekolahs as $item)
                                <option value="{{ $item->asal_sekolah_universitas }}"
                                    {{ request('asal_sekolah_universitas') == $item->asal_sekolah_universitas ? 'selected' : '' }}>
                                    {{ $item->asal_sekolah_universitas }}
                                </option>
                            @endforeach
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
        <div class="p-4 md:p-5" id="userGridContainer">
            @include('admin.user.partials.user-grid', ['users' => $users])
        </div>
    </div>
@endsection

@push('modals')
<div id="showModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75" onclick="closeShowModal(event)"></div>

<div id="showModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Detail Akun</h3>
                    <p class="mt-1 text-gray-600">Informasi lengkap akun peserta</p>
                </div>
                <button onclick="closeShowModal(event)"
                        class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <i class='text-2xl bx bx-x'></i>
                </button>
            </div>
            <div class="p-6">
                <div id="showModalContent">
                    <div class="py-12 text-center">
                        <i class="text-4xl text-indigo-600 bx bx-loader-alt bx-spin"></i>
                        <p class="mt-3 text-gray-600">Memuat data...</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                <button onclick="closeShowModal(event)"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('styles')
    @vite('resources/css/admin/grid-cards.css')
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.userConfig = {
        indexUrl: '{{ route('admin.user.index') }}',
        baseUrl: '{{ url('admin/user') }}'
    };
</script>
@vite('resources/js/admin/user.js')
@endsection
