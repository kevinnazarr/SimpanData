@extends('layouts.app')

@section('title', 'Monitoring - ' . $peserta->nama)

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.peserta.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-indigo-600 transition-all">
                    <i class='bx bx-left-arrow-alt text-2xl'></i>
                </a>
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">Monitoring Peserta</h2>
                    <p class="text-xs text-gray-500 font-medium">Data detail dan riwayat aktivitas peserta</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                    <i class='bx bx-printer'></i> Cetak Laporan
                </button>
            </div>
        </div>

        <div class="p-4 md:p-6 lg:p-8">
            <input type="hidden" name="peserta_id" value="{{ $peserta->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div class="lg:col-span-3">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 p-6 rounded-xl bg-gray-50 border border-gray-200 h-full">
                        <div class="relative flex-shrink-0">
                            @if ($peserta->user->photo_profile)
                                <img src="{{ asset('storage/' . $peserta->user->photo_profile) }}"
                                    class="w-32 h-32 md:w-36 md:h-36 rounded-2xl object-cover border-4 border-white shadow-sm">
                            @else
                                <div class="w-32 h-32 md:w-36 md:h-36 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-5xl font-bold text-white shadow-sm border-4 border-white">
                                    {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-emerald-500 w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center text-white" title="Status Aktif">
                                <i class='bx bxs-check-shield text-base'></i>
                            </div>
                        </div>

                        <div class="flex-1 w-full text-center md:text-left">
                            <div class="mb-4">
                                <div class="flex flex-col md:flex-row items-center gap-2 mb-1 justify-center md:justify-start">
                                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $peserta->nama }}</h1>
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border {{ $peserta->status == 'Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                        {{ $peserta->status }}
                                    </span>
                                    @if($peserta->tugas)
                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border bg-indigo-50 text-indigo-700 border-indigo-100">
                                            {{ $peserta->tugas }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 font-medium">{{ $peserta->asal_sekolah_universitas }} • {{ $peserta->jurusan }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm">
                                            <i class='bx bx-user'></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Username</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ $peserta->user->username }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm">
                                            <i class='bx bx-id-card'></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">NIM / NIS</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ $peserta->nim_nis ?: '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm">
                                            <i class='bx bx-briefcase'></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Tugas</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ $peserta->tugas ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm">
                                            <i class='bx bx-calendar'></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Periode</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ $peserta->tanggal_mulai->format('d M') }} - {{ $peserta->tanggal_selesai->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm">
                                            <i class='bx bx-bookmark'></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Program</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ $peserta->jenis_kegiatan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div onclick="window.location.href='{{ route('admin.penilaian.form', $peserta->id) }}'"
                        class="h-full p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 shadow-sm hover:shadow-md transition-all cursor-pointer group flex flex-col justify-center items-center text-center">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class='bx bxs-star text-2xl'></i>
                        </div>
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Nilai Akhir</p>
                        
                        @if ($peserta->penilaian)
                            <div class="flex items-end gap-1 mb-2">
                                <span class="text-5xl font-bold text-indigo-600">{{ round($peserta->penilaian->nilai_akhir) }}</span>
                                <span class="text-sm font-bold text-indigo-300 mb-2">/100</span>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase rounded-lg">
                                Grade: {{ $peserta->penilaian->grade }}
                            </span>
                        @else
                            <h4 class="text-lg font-bold text-gray-400 uppercase tracking-tight mb-2">Belum Dinilai</h4>
                            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest flex items-center gap-1">
                                <i class='bx bx-edit-alt'></i> Klik untuk Input
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden min-h-[500px]">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 overflow-x-auto scroller-hidden">
                    <div class="flex gap-2">
                        <button onclick="switchTab('absensi')" id="btn-absensi"
                            class="tab-btn active px-4 py-2 rounded-lg gap-2 transition-all flex items-center">
                            <i class='bx bx-calendar text-lg'></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Absensi</span>
                        </button>
                        <button onclick="switchTab('laporan')" id="btn-laporan"
                            class="tab-btn px-4 py-2 rounded-lg gap-2 transition-all flex items-center">
                            <i class='bx bx-book-content text-lg'></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Log Laporan</span>
                        </button>
                        <button onclick="switchTab('pajak')" id="btn-pajak"
                            class="tab-btn px-4 py-2 rounded-lg gap-2 transition-all flex items-center">
                            <i class='bx bx-info-circle text-lg'></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Akademik</span>
                        </button>
                        <button onclick="switchTab('feedback')" id="btn-feedback"
                            class="tab-btn px-4 py-2 rounded-lg gap-2 transition-all flex items-center">
                            <i class='bx bx-comment-detail text-lg'></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Feedback</span>
                        </button>
                    </div>
                </div>

                <div class="p-4 md:p-6">
                <div id="tab-absensi" class="tab-panel animate-fade-in block">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Riwayat Absensi</h3>
                            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Laporan deteksi waktu dan lokasi presensi menyeluruh</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-gray-600 border-b bg-gray-50">
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">No</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Waktu Absen</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Jenis</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Mode</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Status</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Pesan / WA</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px] text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="absensi-container">
                                @include('admin.peserta.partials.absensi-rows', ['absensis' => $absensis])
                            </tbody>
                        </table>
                    </div>
                    <div id="absensi-pagination" class="mt-4">
                        {{ $absensis->appends(['laporan_page' => $laporans->currentPage()])->links() }}
                    </div>
                </div>

                <div id="tab-laporan" class="tab-panel animate-fade-in hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-gray-800 tracking-tight">Log Kegiatan Harian</h3>
                    </div>

                    <div class="space-y-10 relative before:absolute before:left-[19px] before:top-4 before:bottom-4 before:w-0.5 before:bg-gradient-to-b before:from-indigo-50 before:via-indigo-200 before:to-indigo-50" id="laporan-container">
                        @include('admin.peserta.partials.laporan-rows', ['laporans' => $laporans])
                    </div>
                    <div id="laporan-pagination" class="mt-8">
                        {{ $laporans->appends(['absensi_page' => $absensis->currentPage()])->links() }}
                    </div>
                </div>

                <div id="tab-pajak" class="tab-panel animate-fade-in hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Data Akademik</h3>
                            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Informasi instansi pendidikan dan periode magang</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100">
                                    <i class='bx bxs-graduation text-2xl'></i>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Institusi</h4>
                                    <p class="text-sm font-bold text-gray-800 uppercase tracking-tight">Pendidikan</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class='bx bxs-school text-lg'></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight block mb-0.5">Asal Sekolah/Universitas</span>
                                        <p class="text-sm font-bold text-gray-700 leading-tight">{{ $peserta->asal_sekolah_universitas }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class='bx bxs-book-bookmark text-lg'></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight block mb-0.5">Jurusan</span>
                                        <p class="text-sm font-bold text-gray-700">{{ $peserta->jurusan ?: '-' }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class='bx bx-id-card text-lg'></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight block mb-0.5">NIM / NIS</span>
                                        <p class="text-sm font-bold text-gray-700">{{ $peserta->nim_nis ?: '-' }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class='bx bx-task text-lg'></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight block mb-0.5">Tugas</span>
                                        <p class="text-sm font-bold text-gray-700">{{ $peserta->tugas ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100">
                                    <i class='bx bxs-calendar-event text-2xl'></i>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Periode</h4>
                                    <p class="text-sm font-bold text-gray-800 uppercase tracking-tight">PKL / Magang</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 flex items-center gap-4">
                                    <div class="flex-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Mulai</span>
                                        <span class="text-sm font-bold text-gray-800">{{ $peserta->tanggal_mulai->format('d M Y') }}</span>
                                    </div>
                                    <div class="w-10 h-10 bg-white text-blue-500 rounded-xl flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class='bx bx-transfer-alt text-xl'></i>
                                    </div>
                                    <div class="flex-1 text-right">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Selesai</span>
                                        <span class="text-sm font-bold text-gray-800">{{ $peserta->tanggal_selesai->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100/50 flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white text-emerald-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-emerald-100/50">
                                        <i class='bx bxs-toggle-right text-xl'></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-xs font-black text-emerald-400 uppercase tracking-widest block mb-1">Status Saat Ini</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 profile-ring-active"></div>
                                            <span class="text-sm font-black text-emerald-700 uppercase tracking-tighter">{{ $peserta->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-white text-purple-600 rounded-full flex items-center justify-center shadow-sm border border-purple-50">
                                    <i class='bx bxs-map-alt text-3xl'></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Kontak</h4>
                                    <p class="text-base font-black text-gray-800 font-display uppercase tracking-tight">& Domisili</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                                            <i class='bx bxs-phone text-xl'></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-gray-400 uppercase block mb-1">Nomor Telepon</span>
                                            <p class="text-base font-black text-gray-800 leading-none">{{ $peserta->no_telepon ?: '-' }}</p>
                                        </div>
                                    </div>
                                    @if($peserta->no_telepon)
                                        @php
                                            $wa_number = preg_replace('/[^0-9]/', '', $peserta->no_telepon);
                                            if(str_starts_with($wa_number, '0')) $wa_number = '62' . substr($wa_number, 1);
                                        @endphp
                                        <a href="https://wa.me/{{ $wa_number }}" target="_blank" class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center hover:scale-110 transition-all shadow-lg shadow-emerald-200">
                                            <i class='bx bxl-whatsapp text-xl'></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex items-start gap-4">
                                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                                        <i class='bx bxs-map-pin text-xl'></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase block mb-1">Alamat Lengkap</span>
                                        <p class="text-xs font-bold text-gray-600 leading-relaxed italic line-clamp-2" title="{{ $peserta->alamat }}">
                                            "{{ $peserta->alamat ?: 'Data alamat belum diperbarui.' }}"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-feedback" class="tab-panel animate-fade-in hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 font-display tracking-tight">Feedback Peserta</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">Kumpulan masukan dan pesan dari peserta magang</p>
                        </div>
                    </div>

                    <div class="space-y-6 scroller max-h-[600px] overflow-y-auto pr-2" id="chat-container">
                        @forelse($peserta->feedbacks->where('pengirim', 'Peserta')->sortByDesc('created_at') as $fb)
                            <div class="bg-gray-50/50 rounded-[2rem] p-6 border border-gray-100 group/feedback animate-fade-in">
                                <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-indigo-600">
                                            <i class='bx bx-user text-xl'></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-gray-900 font-display">{{ $peserta->nama }}</h4>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $fb->created_at->translatedFormat('d M Y • H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($fb->rating)
                                            <div class="flex items-center gap-1 bg-amber-50/50 px-2.5 py-1 rounded-lg border border-amber-100/50">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class='bx {{ $i <= $fb->rating ? 'bxs-star text-amber-400' : 'bx-star text-amber-200' }} text-[10px]'></i>
                                                @endfor
                                            </div>
                                        @endif
                                        <button onclick="deleteFeedback({{ $fb->id }})" class="w-8 h-8 flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4 pl-14 relative">
                                    <i class='bx bxs-quote-left absolute left-4 top-0 text-2xl text-indigo-100'></i>
                                    <p class="text-sm text-gray-600 leading-relaxed font-medium italic font-display">
                                        {{ $fb->pesan }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-20 bg-gray-50/30 rounded-[3rem] border-2 border-dashed border-gray-100">
                                <i class='bx bx-message-square-x text-5xl text-gray-200 mb-4'></i>
                                <h5 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-1 font-display">Belum Ada Feedback</h5>
                                <p class="text-[10px] text-gray-300 italic">Antrean feedback saat ini masih kosong.</p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>

        <div class="mt-8 bg-white rounded-[2.5rem] shadow-md border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100/50">
                        <i class='bx bx-map-alt text-3xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800 font-display tracking-tight">Lokasi Domisili & Koordinat</h3>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">Peta lokasi real-time berdasarkan titik koordinat yang disimpan peserta</p>
                    </div>
                </div>

                @if($peserta->latitude && $peserta->longitude)
                    <div class="relative w-full h-[400px] rounded-3xl overflow-hidden border-4 border-gray-50 shadow-inner z-[1]">
                        <div id="peserta-map" class="w-full h-full"></div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-4">
                         <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                             <i class='bx bx-current-location text-indigo-500'></i>
                             <span class="text-xs font-bold text-gray-600">{{ $peserta->latitude }}, {{ $peserta->longitude }}</span>
                         </div>
                         <a href="https://www.google.com/maps?q={{ $peserta->latitude }},{{ $peserta->longitude }}" target="_blank" class="flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-4 py-2 rounded-xl transition-colors text-xs font-bold border border-indigo-100 shadow-sm">
                             <i class='bx bx-map text-lg'></i> Buka di Google Maps
                         </a>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var map = L.map('peserta-map').setView([{{ $peserta->latitude }}, {{ $peserta->longitude }}], 16);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);
                            L.marker([{{ $peserta->latitude }}, {{ $peserta->longitude }}]).addTo(map)
                                .bindPopup("<div class='text-center'><b class='text-sm text-gray-800 block mb-1'>{{ addslashes($peserta->nama) }}</b><span class='text-xs text-gray-500'>Lokasi domisili tersimpan.</span></div>")
                                .openPopup();
                        });
                    </script>
                @else
                    <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-amber-500 shadow-sm shrink-0 border border-amber-100">
                            <i class='bx bx-map-pin text-2xl'></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900 mb-1">Koordinat Belum Tersedia</h4>
                            <p class="text-xs text-amber-700">Peserta belum menyimpan titik koordinat lokasi domisilinya melalui profil.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @vite(['resources/css/admin/monitoring.css', 'resources/js/admin/monitoring.js'])

    @push('modals')
        <div id="map-modal" class="fixed inset-0 z-30 hidden items-center justify-center p-3 md:p-6">
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
