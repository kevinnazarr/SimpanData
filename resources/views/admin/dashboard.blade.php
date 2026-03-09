@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-4 md:mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                Statistik Peserta
            </h2>
        </div>

        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="overflow-hidden rounded-lg shadow-sm bg-gradient-to-br from-indigo-500 to-purple-500 group transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">PKL</p>
                                <h3 class="text-3xl font-bold text-white">{{ $totalPkl }}</h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20 group-hover:bg-white/30 transition-colors duration-300">
                                    <i class='text-2xl text-white bx bx-book'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg shadow-sm bg-gradient-to-br from-blue-500 to-indigo-500 group transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Magang</p>
                                <h3 class="text-3xl font-bold text-white">{{ $totalMagang }}</h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20 group-hover:bg-white/30 transition-colors duration-300">
                                    <i class='text-2xl text-white bx bx-briefcase-alt'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg shadow-sm bg-gradient-to-br from-emerald-500 to-teal-500 group transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Aktif</p>
                                <h3 class="text-3xl font-bold text-white">{{ $aktif }}</h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20 group-hover:bg-white/30 transition-colors duration-300">
                                    <i class='text-2xl text-white bx bx-time-five'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg shadow-sm bg-gradient-to-br from-amber-500 to-orange-500 group transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Selesai</p>
                                <h3 class="text-3xl font-bold text-white">{{ $selesai }}</h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white/20 group-hover:bg-white/30 transition-colors duration-300">
                                    <i class='text-2xl text-white bx bx-check-double'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-12">
        <div class="p-6 lg:col-span-8 card md:p-8 shadow-soft hover:shadow-md transition-shadow duration-300 flex flex-col">
            <div class="flex flex-col justify-between gap-4 mb-8 md:flex-row md:items-center">
                <div>
                    <h4 class="text-lg font-bold tracking-tight uppercase text-slate-800">Analisa Kehadiran</h4>
                    <p class="text-xs font-medium text-slate-400 whitespace-nowrap">Tren kehadiran masuk dan pulang harian</p>
                </div>

                <div class="flex flex-col gap-2 md:flex-row md:items-center">
                    <select id="weekSelector"
                        class="hidden w-full px-3 py-2 text-xs font-bold bg-white border border-gray-200 rounded-xl text-slate-600 focus:outline-none focus:border-primary md:w-auto md:py-1.5">
                        @foreach ($availableWeeks as $wk)
                            <option value="{{ $wk['value'] }}" {{ $weekFilter == $wk['value'] ? 'selected' : '' }}>
                                {{ $wk['label'] }}</option>
                        @endforeach
                    </select>
                    <div class="flex items-center gap-1 p-1 overflow-x-auto border border-gray-200 bg-gray-50 rounded-xl no-scrollbar md:overflow-visible"
                        id="chartFilter">
                        <button data-filter="hari"
                            class="filter-btn shrink-0 px-4 py-1.5 text-xs font-bold uppercase rounded-lg transition-all {{ $filter == 'hari' ? 'bg-white text-primary shadow-sm border border-gray-100' : 'text-slate-500 hover:text-slate-700' }}">Hari</button>
                        <button data-filter="minggu"
                            class="filter-btn shrink-0 px-4 py-1.5 text-xs font-bold uppercase rounded-lg transition-all {{ $filter == 'minggu' ? 'bg-white text-primary shadow-sm border border-gray-100' : 'text-slate-500 hover:text-slate-700' }}">Minggu</button>
                        <button data-filter="bulan"
                            class="filter-btn shrink-0 px-4 py-1.5 text-xs font-bold uppercase rounded-lg transition-all {{ $filter == 'bulan' ? 'bg-white text-primary shadow-sm border border-gray-100' : 'text-slate-500 hover:text-slate-700' }}">Bulan</button>
                    </div>
                </div>
            </div>

            <div class="h-64 md:h-[350px] w-full relative mb-10 flex-1">
                <div id="chartLoading"
                    class="absolute inset-0 bg-white/50 backdrop-blur-[1px] items-center justify-center z-10 hidden">
                    <div class="w-8 h-8 border-4 rounded-full border-primary/20 border-t-primary animate-spin"></div>
                </div>
                <canvas id="attendanceChart"></canvas>
            </div>

            @php
                $totalAbsenToday = array_sum($attendanceBreakdown) ?: 1;
            @endphp
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-200">
                <div class="p-3 rounded-lg border border-yellow-300 bg-yellow-50 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-yellow-700">
                        Izin
                    </p>
                    <h4 class="text-xl font-bold text-yellow-800">
                        {{ $attendanceBreakdown['Izin'] }}
                    </h4>
                    <div class="h-1 w-full bg-yellow-200 rounded-full mt-2 overflow-hidden">
                        <div
                            class="h-full bg-yellow-600 rounded-full"
                            style="width: {{ $totalAbsenToday > 0 ? ($attendanceBreakdown['Izin'] / $totalAbsenToday) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                <div class="p-3 rounded-lg border border-red-300 bg-red-50 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-red-700">
                        Sakit
                    </p>
                    <h4 class="text-xl font-bold text-red-800">
                        {{ $attendanceBreakdown['Sakit'] }}
                    </h4>
                    <div class="h-1 w-full bg-red-200 rounded-full mt-2 overflow-hidden">
                        <div
                            class="h-full bg-red-600 rounded-full"
                            style="width: {{ $totalAbsenToday > 0 ? ($attendanceBreakdown['Sakit'] / $totalAbsenToday) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 lg:col-span-4 card md:p-8 shadow-soft flex flex-col items-center hover:shadow-md transition-shadow duration-300">
            <h4 class="w-full mb-6 text-lg font-bold tracking-tight uppercase text-slate-800">Ringkasan Absensi Hari Ini</h4>

            <div class="relative w-full aspect-square flex items-center justify-center max-w-[350px] mx-auto mb-10">
                <canvas id="attendanceDonutChart"></canvas>
                <div class="absolute flex flex-col items-center justify-center text-center">
                    <h2 class="text-3xl font-extrabold leading-none tracking-tighter text-slate-900">
                        {{ array_sum($attendanceBreakdown) }}
                    </h2>
                    <p class="mt-1 text-[10px] font-bold tracking-widest uppercase text-slate-500">Aktivitas</p>
                </div>
            </div>

            <div class="w-full space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <i class='bx bx-check-shield text-xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Kehadiran</p>
                            <p class="text-[10px] font-medium text-slate-400">Total peserta hadir</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-slate-900">{{ $attendanceBreakdown['Hadir'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-12">
        <div class="p-6 lg:col-span-8 card md:p-8 shadow-soft hover:shadow-md transition-shadow duration-300">
            <h4 class="mb-6 text-lg font-bold tracking-tight uppercase text-slate-800">Feedback Peserta</h4>
            <div class="relative group">
                <div class="absolute top-0 left-0 right-0 z-10 h-8 pointer-events-none bg-gradient-to-b from-white to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div
                    class="flex flex-col flex-1 px-1 py-2 space-y-3 md:space-y-4 overflow-y-auto min-h-0 max-h-[500px] feedback-scroll">
                    @forelse ($feedbacks as $feedback)
                            <div class="flex gap-3 p-4 border border-slate-100 rounded-2xl hover:bg-slate-50/50 transition-colors duration-200 group relative">
                                <div class="flex-shrink-0">
                                    @if($feedback->peserta->foto)
                                        <img src="{{ asset('storage/' . $feedback->peserta->foto) }}" 
                                             class="object-cover w-12 h-12 shadow-sm rounded-xl border border-slate-100">
                                    @else
                                        <div class="flex items-center justify-center w-12 h-12 font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                            {{ strtoupper(substr($feedback->peserta->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-bold text-slate-800">
                                            {{ $feedback->peserta->nama }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ $feedback->created_at->diffForHumans() }}
                                            </p>
                                            <button type="button" 
                                                class="transition-all duration-200 p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg delete-feedback" 
                                                data-id="{{ $feedback->id }}"
                                                title="Hapus Feedback">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 italic">
                                        "{{ $feedback->pesan }}"
                                    </p>
                                </div>
                            </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <i class='bx bx-message-square-detail text-3xl text-slate-300'></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Belum ada feedback peserta</p>
                        </div>
                    @endforelse
                </div>

                <!-- Bottom Fade -->
                <div class="absolute bottom-0 left-0 right-0 z-10 h-12 pointer-events-none bg-gradient-to-t from-white via-white/80 to-transparent"></div>
            </div>
        </div>

        <div class="p-6 lg:col-span-4 card md:p-8 shadow-soft flex flex-col items-center hover:shadow-md transition-shadow duration-300">
            <h4 class="w-full mb-6 text-lg font-bold tracking-tight uppercase text-slate-800">
                Perbandingan Peserta
            </h4>
            <div class="relative w-full aspect-square flex items-center justify-center max-w-[350px] mx-auto mb-10">
                <canvas id="salesPieChart"></canvas>
                <div class="absolute flex flex-col items-center justify-center text-center">
                    <h2 class="text-3xl font-extrabold leading-none tracking-tighter text-slate-900">
                        {{ $totalPkl + $totalMagang }}
                    </h2>
                    <p class="mt-1 text-[10px] font-bold tracking-widest uppercase text-slate-500">
                        Total Peserta
                    </p>
                </div>
            </div>
            <div class="w-full space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                            <div class="w-3 h-3 bg-[#4f46e5] rounded-full"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">PKL</p>
                            <p class="text-[10px] font-medium text-slate-400">Peserta aktif PKL</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-slate-900">
                        {{ $totalPkl }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                            <div class="w-3 h-3 bg-[#f59e0b] rounded-full"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Magang</p>
                            <p class="text-[10px] font-medium text-slate-400">Peserta aktif Magang</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-slate-900">
                        {{ $totalMagang }}
                    </span>
                </div>
            </div>
        </div>

    </div>
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="card shadow-soft p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                <h4 class="text-lg font-bold tracking-tight uppercase text-slate-800">Peserta per Sekolah</h4>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest rounded-full">
                    {{ count($pesertaSekolah) }} Institusi
                </span>
            </div>
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <div id="schoolChartWrapper" data-count="{{ count($pesertaSekolah) }}" class="min-w-full">
                    <canvas id="schoolBarChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="card shadow-soft p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                <h4 class="text-lg font-bold tracking-tight uppercase text-slate-800">Peserta per Universitas</h4>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest rounded-full">
                    {{ count($pesertaUniversitas) }} Institusi
                </span>
            </div>
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <div id="uniChartWrapper" data-count="{{ count($pesertaUniversitas) }}" class="min-w-full">
                    <canvas id="uniBarChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/admin/dashboard.css')
@endpush

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.dashboardData = {
            totalPkl: {{ $totalPkl ?? 0 }},
            totalMagang: {{ $totalMagang ?? 0 }},
            pesertaSekolah: @json($pesertaSekolah ?? []),
            pesertaUniversitas: @json($pesertaUniversitas ?? []),
        };
        window.attendanceData = @json($absensiData);
        window.attendanceBreakdown = @json($attendanceBreakdown);
        window.routes = {
            dashboard: "{{ route('admin.dashboard') }}"
        };
        window.initialFilter = "{{ $filter }}";
    </script>
    @vite('resources/js/admin/dashboard.js')
@endsection
