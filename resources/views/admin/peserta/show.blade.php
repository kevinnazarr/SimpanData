@extends('layouts.app')

@section('title', 'Monitoring Dashboard - ' . $peserta->nama)

@section('content')
    <div class="p-4 md:p-6 lg:p-8 bg-gray-50/50 min-h-screen">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 md:mb-10">
            <div class="flex items-center gap-4 md:gap-5">
                <a href="{{ route('admin.peserta.index') }}"
                    class="flex-shrink-0 flex items-center justify-center w-11 h-11 md:w-12 md:h-12 bg-white border border-gray-200 text-gray-400 rounded-2xl hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-100 transition-all duration-300 shadow-sm group">
                    <i class='bx bx-left-arrow-alt text-xl md:text-2xl group-hover:-translate-x-1 transition-transform'></i>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span
                            class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-md border border-indigo-100">Live
                            Monitor</span>
                        <div
                            class="flex items-center gap-1 text-[10px] md:text-[11px] text-gray-400 font-bold uppercase tracking-tighter">
                            <span class="hidden sm:inline">Database</span>
                            <i class='bx bx-chevron-right text-xs hidden sm:inline'></i>
                            <span class="text-gray-600 truncate max-w-[150px] md:max-w-none">{{ $peserta->nama }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button onclick="window.print()"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-2xl hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-100 transition-all duration-300 shadow-sm font-bold text-sm">
                    <i class='bx bx-printer text-xl'></i>
                    <span>Export PDF</span>
                </button>
                <a href="{{ route('admin.peserta.edit', $peserta->id) }}"
                    class="flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-2xl hover:bg-black hover:shadow-xl hover:shadow-gray-200 transition-all duration-300 font-bold text-sm">
                    <i class='bx bx-cog text-xl'></i>
                    <span>Pengaturan Profil</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="lg:col-span-3 bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/30 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl">
                </div>

                <div
                    class="relative flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start text-center md:text-left">
                    <div class="relative flex-shrink-0">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] blur opacity-20">
                        </div>
                        @if ($peserta->foto)
                            <img src="{{ asset('storage/' . $peserta->foto) }}"
                                class="relative w-32 h-32 md:w-40 md:h-40 rounded-[2rem] object-cover ring-4 ring-white shadow-2xl">
                        @else
                            <div
                                class="relative w-32 h-32 md:w-40 md:h-40 rounded-[2rem] bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-4xl md:text-6xl font-black text-white shadow-2xl ring-4 ring-white font-display">
                                {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                            </div>
                        @endif
                        <div
                            class="absolute -bottom-1 md:-bottom-2 -right-1 md:-right-2 bg-emerald-500 w-8 h-8 md:w-10 md:h-10 rounded-full border-4 border-white shadow-lg profile-ring-active flex items-center justify-center text-white">
                            <i class='bx bxs-check-shield text-base md:text-lg'></i>
                        </div>
                    </div>

                    <div class="flex-1 w-full">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-8">
                            <div>
                                <div class="flex flex-col md:flex-row items-center gap-2 md:gap-3 mb-2">
                                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight font-display">
                                        {{ $peserta->nama }}</h1>
                                    <span
                                        class="px-3 py-1.5 {{ $peserta->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} text-[9px] font-black uppercase tracking-[0.2em] rounded-xl border-2">{{ $peserta->status }}</span>
                                </div>
                                <div
                                    class="flex flex-wrap items-center justify-center md:justify-start gap-3 md:gap-4 text-[10px] md:text-xs font-bold text-gray-400">
                                    <span
                                        class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 text-indigo-600">
                                        <i class='bx bx-id-card text-lg'></i>
                                        {{ $peserta->user->username }}
                                    </span>
                                    <span class="hidden md:block w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                    <span class="flex items-center gap-1.5">
                                        <i class='bx bx-buildings text-lg text-gray-400'></i>
                                        {{ $peserta->asal_sekolah_universitas }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100/80 space-y-5">
                                <div>
                                    <p
                                        class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-indigo-400 rounded-full"></span>
                                        Identitas & Kontak
                                    </p>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3 group/item">
                                            @php
                                                $wa_link = '#';
                                                if ($peserta->no_telepon) {
                                                    $wa_number = preg_replace('/[^0-9]/', '', $peserta->no_telepon);
                                                    if (str_starts_with($wa_number, '0')) {
                                                        $wa_number = '62' . substr($wa_number, 1);
                                                    } elseif (str_starts_with($wa_number, '620')) {
                                                        $wa_number = '62' . substr($wa_number, 3);
                                                    }
                                                    $wa_link = 'https://wa.me/' . $wa_number;
                                                }
                                            @endphp
                                            <a href="{{ $wa_link }}" target="_blank"
                                                class="w-9 h-9 bg-white border border-gray-100 text-emerald-500 rounded-xl flex items-center justify-center shadow-sm group-hover/item:bg-emerald-500 group-hover/item:text-white transition-all {{ !$peserta->no_telepon ? 'opacity-50 pointer-events-none' : '' }}"
                                                title="Chat WhatsApp">
                                                <i class='bx bxl-whatsapp text-xl'></i>
                                            </a>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">WhatsApp</span>
                                                <span
                                                    class="text-sm font-black text-gray-700 font-display">{{ $peserta->no_telepon ?: '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 group/item">
                                            <div
                                                class="w-9 h-9 bg-white border border-gray-100 text-purple-500 rounded-xl flex items-center justify-center shadow-sm group-hover/item:bg-purple-500 group-hover/item:text-white transition-all">
                                                <i class='bx bx-briefcase text-xl'></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Jurusan</span>
                                                <span
                                                    class="text-sm font-black text-gray-700 font-display leading-tight">{{ $peserta->jurusan ?: '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100/80 space-y-5">
                                <div>
                                    <p
                                        class="text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-blue-400 rounded-full"></span>
                                        Program & Periode
                                    </p>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3 group/item">
                                            <div
                                                class="w-9 h-9 bg-white border border-gray-100 text-blue-500 rounded-xl flex items-center justify-center shadow-sm group-hover/item:bg-blue-500 group-hover/item:text-white transition-all">
                                                <i class='bx bx-calendar text-xl'></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Rentang
                                                    Waktu</span>
                                                <span
                                                    class="text-sm font-black text-gray-700 font-display italic">{{ $peserta->tanggal_mulai->format('d M') }}
                                                    - {{ $peserta->tanggal_selesai->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 group/item">
                                            <div
                                                class="w-9 h-9 bg-white border border-gray-100 text-indigo-500 rounded-xl flex items-center justify-center shadow-sm group-hover/item:bg-indigo-500 group-hover/item:text-white transition-all">
                                                <i class='bx bx-bookmark text-xl'></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Jenis
                                                    Kegiatan</span>
                                                <span
                                                    class="text-sm font-black text-gray-700 font-display">{{ $peserta->jenis_kegiatan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-indigo-600 rounded-2xl p-6 flex flex-col justify-between border border-indigo-700 shadow-xl shadow-indigo-100 relative overflow-hidden group/stats">
                                <div
                                    class="absolute -right-4 -bottom-4 opacity-10 group-hover/stats:scale-110 transition-transform duration-700">
                                    <i class='bx bx-pulse text-8xl text-white'></i>
                                </div>
                                <div class="relative">
                                    <div class="flex items-center justify-between mb-6">
                                        <span
                                            class="text-[9px] font-black uppercase text-indigo-100 tracking-[0.2em] flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-300 animate-pulse"></span>
                                            Ringkasan Aktivitas
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-center flex-1">
                                            <h4 class="text-3xl font-black text-white leading-none mb-2">
                                                {{ $peserta->absensis->where('status', 'Hadir')->count() }}</h4>
                                            <p class="text-[8px] font-black text-indigo-200 uppercase tracking-widest">Hadir
                                            </p>
                                        </div>
                                        <div class="w-px h-10 bg-indigo-500/50"></div>
                                        <div class="text-center flex-1">
                                            <h4 class="text-3xl font-black text-white leading-none mb-2">
                                                {{ $peserta->laporans->count() }}</h4>
                                            <p class="text-[8px] font-black text-indigo-200 uppercase tracking-widest">
                                                Laporan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div onclick="switchTab('penilaian')"
                    class="h-full bg-gradient-to-br from-indigo-600 to-indigo-900 rounded-3xl p-6 md:p-8 shadow-xl shadow-indigo-100/50 text-white overflow-hidden relative group cursor-pointer hover:-translate-y-1 transition-all duration-300 flex flex-col justify-center min-h-[160px] md:min-h-0">
                    <div
                        class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform duration-700 rotate-12">
                        <i class='bx bxs-award text-[10rem] md:text-[12rem]'></i>
                    </div>
                    <div class="relative text-center lg:text-left">
                        <div class="flex items-center justify-between mb-4 md:mb-6">
                            <p class="text-[9px] md:text-[10px] font-black text-indigo-100 uppercase tracking-widest">
                                Performance Score</p>
                            <div
                                class="w-9 h-9 md:w-10 md:h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                <i class='bx bxs-star text-amber-300 text-lg md:text-xl'></i>
                            </div>
                        </div>
                        @if ($peserta->penilaian)
                            <div class="flex items-end justify-center lg:justify-start gap-2">
                                <h4 class="text-4xl md:text-5xl font-black font-display tracking-tighter">
                                    {{ $peserta->penilaian->nilai_akhir }}</h4>
                                <span class="text-lg md:text-xl font-black text-white/50 mb-1.5">/ 100</span>
                            </div>
                            <div class="mt-4 md:mt-6 flex items-center justify-center lg:justify-start gap-2">
                                <span
                                    class="px-4 py-1.5 bg-white/20 text-white rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest backdrop-blur-sm border border-white/10">Grade:
                                    {{ $peserta->penilaian->grade }}</span>
                            </div>
                        @else
                            <h4 class="text-xl md:text-2xl font-black font-display uppercase tracking-tight">Belum Dinilai
                            </h4>
                            <p
                                class="text-[9px] md:text-[10px] text-white/50 mt-3 md:mt-4 font-black uppercase tracking-widest flex items-center justify-center lg:justify-start gap-2 italic">
                                <i class='bx bx-edit-alt'></i> Klik untuk input nilai
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden min-h-[600px]">
            <div class="px-4 md:px-6 py-4 bg-white border-b border-gray-100">
                <div class="inline-flex p-1.5 bg-gray-100/70 rounded-2xl w-full md:w-auto overflow-x-auto scroller-hidden justify-between md:justify-start gap-1">
                    <button onclick="switchTab('absensi')" id="btn-absensi"
                        class="tab-btn active flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl gap-2 transition-all" title="Absensi">
                        <i class='bx bx-calendar text-xl md:text-lg'></i>
                        <span class="hidden md:inline text-xs font-black uppercase tracking-widest">Absensi</span>
                    </button>
                    <button onclick="switchTab('laporan')" id="btn-laporan"
                        class="tab-btn flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl gap-2 transition-all" title="Log Laporan">
                        <i class='bx bx-book-content text-xl md:text-lg'></i>
                        <span class="hidden md:inline text-xs font-black uppercase tracking-widest">Log Laporan</span>
                    </button>
                    <button onclick="switchTab('pajak')" id="btn-pajak"
                        class="tab-btn flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl gap-2 transition-all" title="Akademik">
                        <i class='bx bx-info-circle text-xl md:text-lg'></i>
                        <span class="hidden md:inline text-xs font-black uppercase tracking-widest">Akademik</span>
                    </button>
                    <button onclick="switchTab('feedback')" id="btn-feedback"
                        class="tab-btn flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl gap-2 transition-all relative" title="Feedback">
                        <i class='bx bx-comment-detail text-xl md:text-lg'></i>
                        <span class="hidden md:inline text-xs font-black uppercase tracking-widest">Feedback</span>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div id="tab-absensi" class="tab-panel animate-fade-in block">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 font-display tracking-tight">Riwayat Absensi</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">Laporan
                                deteksi waktu dan lokasi presensi menyeluruh</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-3xl border border-gray-100">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-gray-600 border-b bg-gray-50/50">
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">No</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Waktu Absen</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Jenis</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Mode</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Status</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Pesan / WA</th>
                                    <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($peserta->absensis->sortByDesc('waktu_absen') as $index => $absensi)
                                    <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                        <td class="px-6 py-4 text-gray-500 font-bold">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xs font-black">
                                                    {{ $absensi->waktu_absen->format('d') }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-gray-900 leading-none mb-1">
                                                        {{ $absensi->waktu_absen->format('H:i') }} <span
                                                            class="text-[8px] text-gray-400 font-bold ml-0.5">WIB</span>
                                                    </p>
                                                    <p
                                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                                                        {{ $absensi->waktu_absen->format('Y-m-d') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ $absensi->jenis_absen === 'Masuk' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                                {{ $absensi->jenis_absen }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($absensi->mode_kerja === 'WFO')
                                                <span
                                                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-indigo-700 bg-indigo-100 rounded-lg">WFO</span>
                                            @elseif($absensi->mode_kerja === 'WFA')
                                                <span
                                                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-purple-700 bg-purple-100 rounded-lg">WFA</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-gray-500 bg-gray-100 rounded-lg">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                        {{ $absensi->status === 'Hadir' ? 'bg-emerald-100 text-emerald-700' : ($absensi->status === 'Izin' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                                {{ $absensi->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 font-medium">
                                            <p class="text-xs italic line-clamp-1 max-w-[150px]">
                                                "{{ $absensi->wa_pengirim ?: '-' }}"</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($absensi->latitude && $absensi->longitude)
                                                <button
                                                    onclick="showMap('{{ $absensi->latitude }}', '{{ $absensi->longitude }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 hover:border-blue-300 transition-all group">
                                                    <i class='bx bx-map-pin'></i>
                                                    <span>Detail</span>
                                                    <i
                                                        class='bx bx-chevron-right transition-transform group-hover:translate-x-0.5'></i>
                                                </button>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class='bx bx-calendar-x text-5xl text-gray-200 mb-2'></i>
                                                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">
                                                    Data Absensi Kosong</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="tab-laporan" class="tab-panel animate-fade-in hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-gray-900 font-display">Log Kegiatan Harian</h3>
                    </div>

                    <div
                        class="space-y-10 relative before:absolute before:left-[19px] before:top-4 before:bottom-4 before:w-0.5 before:bg-gradient-to-b before:from-indigo-50 before:via-indigo-200 before:to-indigo-50">
                        @forelse($peserta->laporans as $laporan)
                            <div class="relative pl-14 group">
                                <div
                                    class="absolute left-0 top-1 w-10 h-10 bg-white border-2 border-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all duration-500 z-10 shadow-sm shadow-indigo-100/50">
                                    <i class='bx bx-file-blank text-xl'></i>
                                </div>
                                <div
                                    class="bg-white border border-gray-100 rounded-[2rem] p-8 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-500">
                                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                                <h4
                                                    class="text-lg font-black text-gray-900 group-hover:text-indigo-700 transition-colors font-display tracking-tight">
                                                    {{ $laporan->judul }}</h4>
                                                <span
                                                    class="px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $laporan->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($laporan->status == 'Revisi' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100') }}">
                                                    {{ $laporan->status }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 italic">
                                                "{{ $laporan->deskripsi }}"</p>

                                            <div class="flex flex-wrap items-center gap-6">
                                                <div
                                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                    <i class='bx bx-time-five text-base text-indigo-300'></i>
                                                    {{ $laporan->tanggal_laporan->format('d F Y') }}
                                                </div>
                                                @if ($laporan->file_path)
                                                    <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-4 py-1.5 rounded-xl border border-indigo-100 transition-all">
                                                        <i class='bx bx-link-alt text-base'></i>
                                                        Berkas Pendukung
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex">
                                            <a href="{{ route('admin.laporan.harian.show', $laporan->id) }}"
                                                class="w-full lg:w-auto px-8 py-3 bg-gray-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-gray-200 text-center">
                                                Review Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="py-20 text-center bg-gray-50/50 rounded-[2.5rem] border-2 border-dashed border-gray-100">
                                <div
                                    class="w-20 h-20 bg-white border border-gray-100 rounded-[2rem] flex items-center justify-center mx-auto mb-4 shadow-sm">
                                    <i class='bx bx-ghost text-4xl text-gray-200'></i>
                                </div>
                                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Log laporan masih
                                    kosong</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="tab-pajak" class="tab-panel animate-fade-in hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
                        <div>
                            <div class="flex items-center gap-3 mb-6 md:mb-8">
                                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Pendidikan &
                                    Institusi</h4>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="p-5 md:p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100 hover:border-indigo-100 transition-colors group">
                                    <p
                                        class="text-[9px] md:text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2 group-hover:text-indigo-400 transition-colors font-display">
                                        Institusi Pendidikan</p>
                                    <p class="text-sm md:text-base font-black text-gray-800 font-display leading-tight">
                                        {{ $peserta->asal_sekolah_universitas }}</p>
                                </div>
                                <div
                                    class="p-5 md:p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100 hover:border-indigo-100 transition-colors group">
                                    <p
                                        class="text-[9px] md:text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2 group-hover:text-indigo-400 transition-colors font-display">
                                        Program Studi / Jurusan</p>
                                    <p class="text-sm md:text-base font-black text-gray-800 font-display leading-tight">
                                        {{ $peserta->jurusan }}</p>
                                </div>
                                <div
                                    class="p-5 md:p-6 bg-emerald-50/30 rounded-[1.5rem] border border-emerald-100/50 group">
                                    <p
                                        class="text-[9px] md:text-[10px] uppercase font-black text-emerald-400 tracking-widest mb-2 font-display">
                                        Status Sistem</p>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 profile-ring-active"></div>
                                        <p
                                            class="text-sm md:text-base font-black text-emerald-700 font-display uppercase tracking-tighter">
                                            {{ $peserta->status }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-6 md:mb-8">
                                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Kontak & Periode
                                </h4>
                            </div>
                            <div class="space-y-4">
                                <div class="p-5 md:p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100 group">
                                    @php
                                        $wa_number = preg_replace('/[^0-9]/', '', $peserta->no_telepon);
                                        if (str_starts_with($wa_number, '0')) {
                                            $wa_number = '62' . substr($wa_number, 1);
                                        } elseif (str_starts_with($wa_number, '620')) {
                                            $wa_number = '62' . substr($wa_number, 3);
                                        }
                                        $wa_link = 'https://wa.me/' . $wa_number;
                                    @endphp
                                    <div class="flex items-center justify-between mb-2">
                                        <p
                                            class="text-[9px] md:text-[10px] uppercase font-black text-gray-400 tracking-widest font-display">
                                            Kontak</p>
                                        @if ($peserta->no_telepon)
                                            <a href="{{ $wa_link }}" target="_blank"
                                                class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-emerald-200">
                                                <i class='bx bxl-whatsapp text-lg'></i>
                                            </a>
                                        @endif
                                    </div>
                                    <p class="text-base md:text-lg font-black text-gray-800 font-display">
                                        {{ $peserta->no_telepon ?: '-' }}</p>
                                </div>
                                <div class="p-5 md:p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100 group">
                                    <p
                                        class="text-[9px] md:text-[10px] uppercase font-black text-gray-400 tracking-widest mb-3 font-display">
                                        periode PKL/Magang</p>
                                    <div class="flex items-center gap-3 md:gap-4">
                                        <div class="flex-1">
                                            <span
                                                class="text-[8px] md:text-[9px] text-gray-400 font-black uppercase block mb-0.5">Mulai</span>
                                            <span
                                                class="text-xs md:text-sm font-black text-gray-700 font-display">{{ $peserta->tanggal_mulai->format('d M Y') }}</span>
                                        </div>
                                        <div
                                            class="w-8 h-8 md:w-10 md:h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-300 flex-shrink-0">
                                            <i class='bx bx-transfer-alt text-lg md:text-xl'></i>
                                        </div>
                                        <div class="flex-1 text-right">
                                            <span
                                                class="text-[8px] md:text-[9px] text-gray-400 font-black uppercase block mb-0.5">Selesai</span>
                                            <span
                                                class="text-xs md:text-sm font-black text-gray-700 font-display">{{ $peserta->tanggal_selesai->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 md:p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100">
                                    <p
                                        class="text-[9px] md:text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2 font-display">
                                        Alamat Domisili</p>
                                    <p class="text-xs md:text-sm font-bold text-gray-600 leading-relaxed italic">
                                        "{{ $peserta->alamat ?: 'Data alamat belum diperbarui oleh peserta.' }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-feedback" class="tab-panel animate-fade-in hidden">
                    <div class="max-w-4xl mx-auto flex flex-col min-h-[400px] md:h-[550px]">
                        <div class="flex items-center justify-between mb-6 md:mb-8">
                            <div>
                                <h3 class="text-lg md:text-xl font-black text-gray-800 font-display tracking-tight">
                                    Feedback Peserta</h3>
                                <p
                                    class="text-[9px] md:text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">
                                    Daftar masukan dan laporan dari peserta</p>
                            </div>
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                <i class='bx bx-message-square-detail text-xl md:text-2xl'></i>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto space-y-6 md:space-y-8 mb-6 pr-2 md:pr-4 scroller scroll-smooth"
                            id="chat-container">
                            @forelse($peserta->feedbacks->where('pengirim', 'Peserta') as $fb)
                                <div class="animate-fade-in group/item" id="feedback-{{ $fb->id }}">
                                    <div
                                        class="bg-white border border-gray-100 rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-8 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-100 transition-all duration-500 relative group">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 md:mb-6">
                                            <div class="flex items-center gap-3 md:gap-4">
                                                <div
                                                    class="w-10 h-10 md:w-12 md:h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100/50">
                                                    <i class='bx bx-user text-lg md:text-xl'></i>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="text-xs md:text-sm font-black text-gray-900 font-display tracking-tight leading-none mb-1">
                                                        {{ $peserta->nama }}</h4>
                                                    <p
                                                        class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                                        {{ $fb->created_at->translatedFormat('d F Y • H:i') }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between md:justify-end gap-3">
                                                @if ($fb->rating)
                                                    <div
                                                        class="flex items-center gap-1 bg-amber-50 px-2.5 py-1.5 rounded-xl border border-amber-100/50">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class='bx {{ $i <= $fb->rating ? 'bxs-star text-amber-400' : 'bx-star text-amber-200' }} text-[9px] md:text-[10px]'></i>
                                                        @endfor
                                                    </div>
                                                @endif
                                                <button onclick="deleteFeedback({{ $fb->id }})"
                                                    class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center text-red-400 bg-red-50/50 border border-red-100/50 rounded-xl hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300">
                                                    <i class='bx bx-trash text-lg'></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <i
                                                class='bx bxs-quote-left absolute -left-2 md:-left-4 -top-2 md:-top-4 text-3xl md:text-4xl text-indigo-50 opacity-50'></i>
                                            <p
                                                class="text-sm md:text-base font-medium text-gray-600 leading-relaxed pl-3 md:pl-4 line-clamp-4 group-hover:line-clamp-none transition-all duration-500 font-display">
                                                {{ $fb->pesan }}
                                            </p>
                                        </div>

                                        <div class="mt-4 md:mt-6 flex items-center justify-end">
                                            <span
                                                class="text-[8px] md:text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">{{ $fb->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="h-full flex flex-col items-center justify-center text-center py-12 md:py-20 bg-gray-50/30 rounded-[2.5rem] md:rounded-[3rem] border border-dashed border-gray-100">
                                    <div
                                        class="w-20 h-20 md:w-24 md:h-24 bg-white rounded-[2rem] md:rounded-[2.5rem] flex items-center justify-center mb-6 shadow-sm border border-gray-50">
                                        <i class='bx bx-message-square-x text-4xl md:text-5xl text-gray-200'></i>
                                    </div>
                                    <h5
                                        class="text-base md:text-lg font-black text-gray-400 uppercase tracking-widest mb-2 font-display">
                                        Feed Kosong</h5>
                                    <p
                                        class="text-[10px] md:text-xs text-gray-300 italic max-w-xs leading-relaxed font-medium">
                                        Kotak masuk feedback saat ini belum menerima kiriman pesan dari peserta.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>

    @vite(['resources/css/admin/monitoring.css', 'resources/js/admin/monitoring.js'])

    @push('modals')
        <div id="map-modal" class="fixed inset-0 z-30 flex hidden  items-center justify-center p-3 md:p-6">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-all duration-500" onclick="closeMap()">
            </div>
            <div class="relative w-full max-w-3xl animate-fade-in-up">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/20">
                    <div
                        class="p-5 md:p-8 border-b border-gray-100 flex items-center justify-between bg-white/50 backdrop-blur-md">
                        <div class="flex items-center gap-3 md:gap-4">
                            <div
                                class="w-10 h-10 md:w-14 md:h-14 bg-indigo-50 text-indigo-600 rounded-xl md:rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100/50">
                                <i class='bx bx-map-pin text-xl md:text-2xl'></i>
                            </div>
                            <div>
                                <h3
                                    class="text-base md:text-xl font-black text-gray-900 font-display tracking-tight leading-none mb-1 md:mb-1.5">
                                    Lokasi Real-time</h3>
                                <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase tracking-widest"
                                    id="map-coords-text">Mendeteksi Koordinat...</p>
                            </div>
                        </div>
                        <button onclick="closeMap()"
                            class="w-10 h-10 md:w-12 md:h-12 bg-gray-50 text-gray-400 rounded-xl md:rounded-2xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all">
                            <i class='bx bx-x text-xl md:text-2xl'></i>
                        </button>
                    </div>

                    <div class="relative">
                        <div id="map-container-leaflet" class="w-full h-[300px] md:h-[450px] bg-gray-50"></div>

                        <div class="absolute bottom-4 left-4 right-4 md:bottom-6 md:left-6 md:right-6 z-[1000] animate-fade-in"
                            id="map-address-card" style="display: none;">
                            <div
                                class="bg-white/90 backdrop-blur-xl p-4 md:p-6 rounded-2xl md:rounded-[2rem] shadow-2xl border border-white flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                                <div class="flex items-start gap-3 md:gap-4 flex-1">
                                    <div
                                        class="w-8 h-8 md:w-10 md:h-10 bg-indigo-600 text-white rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                                        <i class='bx bx-current-location text-lg'></i>
                                    </div>
                                    <div>
                                        <h4
                                            class="text-[8px] md:text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">
                                            Alamat Terdeteksi</h4>
                                        <p class="text-xs md:text-sm font-bold text-gray-800 leading-relaxed font-display line-clamp-2"
                                            id="location-address">Mengambil data alamat...</p>
                                    </div>
                                </div>
                                <a href="#" id="google-maps-link" target="_blank"
                                    class="w-full md:w-auto px-6 md:px-8 py-2.5 md:py-3 bg-gray-900 text-white rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-gray-200 text-center flex items-center justify-center gap-2">
                                    <i class='bx bxl-google text-base md:text-lg'></i>
                                    <span>Google Maps</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
