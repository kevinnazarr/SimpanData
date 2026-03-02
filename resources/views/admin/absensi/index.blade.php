@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
    <div class="space-y-6">
        <div class="mb-4 md:mb-6 card">
            <div class="p-4 border-b border-gray-200 md:p-5">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                            Statistik Kehadiran
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Pantau dan kelola data kehadiran harian peserta
                        </p>
                    </div>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-500 border border-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors duration-200 shadow-md whitespace-nowrap">
                        <i class='bx bxs-file-export'></i>
                        <span>Excel</span>
                    </a>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div
                        class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm bg-gradient-to-br from-emerald-500 to-teal-500 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Hadir Hari
                                        Ini</p>
                                    <div class="flex items-center mt-2">
                                        <div class="flex-1 text-center">
                                            <p class="text-xs text-white/70">Masuk</p>
                                            <h3 class="text-3xl font-bold text-white">{{ $hadirMasuk }}</h3>
                                        </div>
                                        <div class="w-px h-10 mx-3 bg-white/30"></div>
                                        <div class="flex-1 text-center">
                                            <p class="text-xs text-white/70">Pulang</p>
                                            <h3 class="text-3xl font-bold text-white">{{ $hadirPulang }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class="text-2xl text-white bx bx-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm bg-gradient-to-br from-amber-500 to-orange-500 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Izin</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $izin }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Peserta</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class="text-2xl text-white bx bx-envelope"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm bg-gradient-to-br from-rose-500 to-pink-500 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">Sakit</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $sakit }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Peserta</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class="text-2xl text-white bx bx-plus-medical"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm bg-gradient-to-br from-blue-500 to-indigo-500 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">WFO</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $wfo }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Peserta</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class="text-2xl text-white bx bx-building-house"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm bg-gradient-to-br from-indigo-500 to-purple-500 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">WFA</p>
                                    <h3 class="text-3xl font-bold text-white">{{ $wfa }}</h3>
                                    <p class="mt-1 text-xs text-white/70">Peserta</p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                        <i class="text-2xl text-white bx bx-home-alt"></i>
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
                <form method="GET" id="filterForm" class="grid grid-cols-1 gap-4 lg:grid-cols-[1fr_auto]">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggalFilter" value="{{ $tanggal ?? '' }}"
                                onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Rentang</label>
                            <label
                                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 font-medium focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all duration-200">
                                <input type="checkbox" name="all_dates" id="allDatesFilter" value="1"
                                    {{ $allDates ? 'checked' : '' }} onchange="toggleAllDates(this)"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                Semua Tanggal
                            </label>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Sekolah</label>
                            <div class="relative">
                                <select name="asal_sekolah_universitas" onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                                    <option value="">Semua Sekolah</option>
                                    @foreach ($sekolahs as $item)
                                        <option value="{{ $item->asal_sekolah_universitas }}"
                                            {{ $sekolah == $item->asal_sekolah_universitas ? 'selected' : '' }}>
                                            {{ $item->asal_sekolah_universitas }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Jenis</label>
                            <div class="relative">
                                <select name="jenis_absen" onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                                    <option value="">Jenis Absen</option>
                                    <option value="Masuk" {{ $jenis == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                    <option value="Pulang" {{ $jenis == 'Pulang' ? 'selected' : '' }}>Pulang</option>
                                </select>
                                <i
                                    class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-semibold uppercase tracking-wider text-gray-500">Status</label>
                            <div class="relative">
                                <select name="status" onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                                    <option value="">Status</option>
                                    <option value="Hadir" {{ $status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Izin" {{ $status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Sakit" {{ $status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                </select>
                                <i
                                    class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch lg:justify-end">
                        <a href="{{ url()->current() }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-colors duration-200 whitespace-nowrap">
                            <i class='bx bx-reset'></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>


        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-600 border-b bg-gray-50/50">
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">No</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Nama Peserta</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Jenis</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Waktu Absen</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Mode</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Status</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">WA Pengirim</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($absensis as $index => $item)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + $absensis->firstItem() }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $item->peserta->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg
                                    {{ $item->jenis_absen === 'Masuk' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $item->jenis_absen ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $item->waktu_absen ? \Carbon\Carbon::parse($item->waktu_absen)->format('Y-m-d H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($item->mode_kerja === 'WFO')
                                        <span
                                            class="px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-lg">
                                            WFO
                                        </span>
                                    @elseif ($item->mode_kerja === 'WFA')
                                        <span
                                            class="px-2.5 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-lg">
                                            WFA
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-lg">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium
                                    {{ $item->status === 'Hadir'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($item->status === 'Izin'
                                            ? 'bg-amber-100 text-amber-700'
                                            : 'bg-rose-100 text-rose-700') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $item->peserta->no_telepon ?? ($item->wa_pengirim ?? '-') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($item->latitude && $item->longitude)
                                        <button type="button"
                                            onclick="openLocationModal('{{ $item->peserta->nama ?? '-' }}', '{{ $item->waktu_absen ? \Carbon\Carbon::parse($item->waktu_absen)->format('Y-m-d H:i') : '-' }}', '{{ $item->jenis_absen }}', '{{ $item->status }}', '{{ $item->mode_kerja ?? '-' }}', {{ $item->latitude }}, {{ $item->longitude }}, '{{ addslashes($item->wa_pengirim ?? '') }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 group">
                                            <i class='text-sm bx bx-map group-hover:animate-bounce'></i>
                                            <span>Lokasi</span>
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-50 rounded-lg cursor-not-allowed">
                                            <i class='text-sm bx bx-map'></i>
                                            <span>N/A</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class='mb-2 text-4xl text-gray-300 bx bx-info-circle'></i>
                                        <p>Tidak ada data absensi untuk filter yang dipilih</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($absensis->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 md:px-5">
                    {{ $absensis->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

        <div id="locationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
            <div class="relative w-full max-w-2xl mx-4 bg-white shadow-2xl rounded-2xl animate-fade-in-up">
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 bg-blue-50 rounded-xl">
                            <i class='bx bx-map'></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Detail Lokasi Absensi</h3>
                            <p class="text-xs text-gray-500" id="modalSubtitle"></p>
                        </div>
                    </div>
                    <button onclick="closeLocationModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                        <i class='text-xl bx bx-x'></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="p-3 rounded-xl bg-gray-50">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Nama</p>
                            <p class="text-sm font-bold text-gray-800 truncate" id="modalNama"></p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Jenis</p>
                            <p class="text-sm font-bold text-gray-800" id="modalJenis"></p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Status</p>
                            <p class="text-sm font-bold text-gray-800" id="modalStatus"></p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Mode</p>
                            <p class="text-sm font-bold text-gray-800" id="modalMode"></p>
                        </div>
                    </div>

                    <div id="modalCatatanWrapper" class="hidden">
                        <div class="flex items-start gap-2 p-3 border border-amber-100 rounded-xl bg-amber-50/50">
                            <i class='mt-0.5 text-amber-500 bx bx-note'></i>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-amber-400 mb-0.5">Catatan</p>
                                <p class="text-xs font-medium leading-relaxed text-gray-700" id="modalCatatan"></p>
                            </div>
                        </div>
                    </div>

                    <div id="mapContainer" class="w-full overflow-hidden border border-gray-200 rounded-xl" style="height:320px;"></div>

                    <div class="flex items-start gap-2 p-3 border border-blue-100 rounded-xl bg-blue-50/50">
                        <i class='mt-0.5 text-blue-500 bx bx-current-location'></i>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-400 mb-0.5">Alamat</p>
                            <p class="text-xs font-medium leading-relaxed text-gray-700" id="modalAddress">Memuat alamat...</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class='bx bx-time-five'></i>
                        <span id="modalWaktu"></span>
                        <span class="mx-1">•</span>
                        <span id="modalCoords"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleAllDates(checkbox) {
            const form = checkbox.form;
            const dateInput = form.querySelector('#tanggalFilter');
            if (!dateInput) return;

            if (checkbox.checked) {
                dateInput.value = '';
                dateInput.disabled = true;
            } else {
                dateInput.disabled = false;
                if (!dateInput.value) {
                    dateInput.value = '{{ \Carbon\Carbon::today()->toDateString() }}';
                }
            }

            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('allDatesFilter');
            const dateInput = document.getElementById('tanggalFilter');
            if (checkbox && dateInput && checkbox.checked) {
                dateInput.disabled = true;
            }
        });

        let locationMap = null;

        function openLocationModal(nama, waktu, jenis, status, mode, lat, lng, catatan) {
            document.getElementById('modalNama').textContent = nama;
            document.getElementById('modalJenis').textContent = jenis;
            document.getElementById('modalStatus').textContent = status;
            document.getElementById('modalMode').textContent = mode;
            document.getElementById('modalWaktu').textContent = waktu;
            document.getElementById('modalSubtitle').textContent = nama + ' — ' + jenis + ' ' + waktu;
            document.getElementById('modalCoords').textContent = lat.toFixed(7) + ', ' + lng.toFixed(7);
            document.getElementById('modalAddress').textContent = 'Memuat alamat...';

            const catatanWrapper = document.getElementById('modalCatatanWrapper');
            if (catatan && catatan.trim() !== '') {
                document.getElementById('modalCatatan').textContent = catatan;
                catatanWrapper.classList.remove('hidden');
            } else {
                catatanWrapper.classList.add('hidden');
            }

            const modal = document.getElementById('locationModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                if (locationMap) {
                    locationMap.remove();
                }
                locationMap = L.map('mapContainer').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(locationMap);
                L.marker([lat, lng]).addTo(locationMap)
                    .bindPopup('<b>' + nama + '</b><br>' + jenis + ' — ' + waktu)
                    .openPopup();

                fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1', {
                    headers: { 'Accept-Language': 'id-ID,id;q=0.9', 'User-Agent': 'SimpanData-Attendance-System' }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('modalAddress').textContent = data.display_name || 'Alamat tidak ditemukan';
                })
                .catch(() => {
                    document.getElementById('modalAddress').textContent = 'Gagal memuat alamat';
                });
            }, 100);
        }

        function closeLocationModal() {
            document.getElementById('locationModal').classList.add('hidden');
            document.body.style.overflow = '';
            if (locationMap) {
                locationMap.remove();
                locationMap = null;
            }
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLocationModal(); });
        document.getElementById('locationModal')?.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeLocationModal();
        });
    </script>
@endsection
