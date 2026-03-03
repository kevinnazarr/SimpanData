@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 p-6 md:flex-row md:items-center card shadow-soft animate-fade-in">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 text-2xl text-blue-600 shadow-inner rounded-xl bg-blue-50">
                <i class='bx bx-calendar-check'></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Data Absensi</h1>
                <p class="text-sm font-medium text-slate-500">Silakan lakukan presensi kehadiran hari ini, {{ date('l, j F Y') }}</p>
            </div>
        </div>
        <div class="px-4 py-2 border border-blue-100 bg-blue-50 rounded-xl animate-fade-in" style="animation-delay: 200ms">
            <p class="text-xs font-bold tracking-tighter text-blue-600 uppercase">Status Absensi</p>
            <p class="text-sm font-extrabold text-blue-900">
                @if($isIzinSakit)
                    Izin / Sakit
                @elseif(!$hasMasuk)
                    Belum Presensi
                @elseif($hasMasuk && !$hasPulang)
                    Sudah Masuk
                @else
                    Sudah Pulang
                @endif
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="p-6 card shadow-soft animate-fade-in-up" style="animation-delay: 100ms">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-8 h-8 text-lg rounded-lg text-slate-400 bg-slate-50">
                    <i class='bx bx-time-five'></i>
                </div>
                <h4 class="text-xs font-bold tracking-widest uppercase text-slate-500">Waktu Sekarang</h4>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-4xl font-bold tracking-tight text-slate-900" id="current-time">00:00:00</p>
                    <p class="mt-2 text-sm font-bold text-slate-400" id="current-date">{{ date('j F Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 card shadow-soft animate-fade-in-up" style="animation-delay: 200ms">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-8 h-8 text-lg rounded-lg text-slate-400 bg-slate-50">
                    <i class='bx bx-map'></i>
                </div>
                <h4 class="text-xs font-bold tracking-widest uppercase text-slate-500">Status Lokasi</h4>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-bold text-slate-800" id="location-status">Mendeteksi lokasi...</p>
                <div id="location-address" class="hidden mt-2 animate-fade-in">
                    <div class="flex items-start gap-2 p-3 border border-blue-100 rounded-xl bg-blue-50/50">
                        <i class='mt-1 text-blue-500 bx bx-current-location'></i>
                        <span class="text-xs font-medium leading-relaxed text-slate-600" id="address-text">Mencari alamat...</span>
                    </div>
                </div>
                <button type="button" id="refresh-location-btn" class="flex items-center self-start gap-1 mt-3 text-xs font-bold text-blue-600 uppercase transition-all hover:text-blue-800 focus:outline-none">
                    <i class='text-base bx bx-refresh'></i> Refresh Lokasi
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('peserta.absensi.store') }}" method="POST" id="attendance-form">
        @csrf
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="attendance_time" id="attendance-time">
        <input type="hidden" name="type" id="attendance-type">

        <div class="p-6 card shadow-soft md:p-8 animate-fade-in-up" style="animation-delay: 300ms">
            <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                <div class="flex items-center justify-center w-10 h-10 text-xl rounded-lg text-primary bg-primary/5">
                    <i class='bx bx-edit'></i>
                </div>
                <h4 class="text-lg font-bold uppercase text-slate-800">Form Absensi Harian</h4>
            </div>

            <div class="space-y-8">
                @if($hasPulang || $isIzinSakit)
                    <div class="p-8 text-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl text-green-600 bg-green-100 rounded-2xl">
                            <i class='bx bx-check-double'></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800">Presensi Selesai</h4>
                        <p class="mt-2 text-sm font-medium text-slate-500">
                            Terima kasih! Anda telah menyelesaikan seluruh rangkaian presensi untuk hari ini.
                        </p>
                    </div>
                @else
                    @if(!$hasMasuk)
                        <div id="status-selection-section">
                            <label class="block mb-4 text-xs font-bold tracking-widest uppercase text-slate-500">Status Kehadiran</label>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <label class="relative flex items-center p-4 transition-all duration-200 border-2 cursor-pointer border-slate-100 rounded-2xl hover:border-green-400 hover:bg-green-50/30 group">
                                    <input type="radio" name="status" value="Hadir" class="w-5 h-5 text-green-600 focus:ring-green-500">
                                    <div class="ml-4">
                                        <span class="text-base font-bold text-slate-800">Hadir</span>
                                        <p class="text-xs font-medium text-slate-500">Kehadiran normal</p>
                                    </div>
                                    <i class='absolute text-xl transition-opacity opacity-0 bx bx-check-circle text-green-600 right-4 group-has-[:checked]:opacity-100'></i>
                                </label>

                                <label class="relative flex items-center p-4 transition-all duration-200 border-2 cursor-pointer border-slate-100 rounded-2xl hover:border-yellow-400 hover:bg-yellow-50/30 group">
                                    <input type="radio" name="status" value="Izin" class="w-5 h-5 text-yellow-600 focus:ring-yellow-500">
                                    <div class="ml-4">
                                        <span class="text-base font-bold text-slate-800">Izin</span>
                                        <p class="text-xs font-medium text-slate-500">Dengan alasan izin</p>
                                    </div>
                                    <i class='absolute text-xl transition-opacity opacity-0 bx bx-check-circle text-yellow-600 right-4 group-has-[:checked]:opacity-100'></i>
                                </label>

                                <label class="relative flex items-center p-4 transition-all duration-200 border-2 cursor-pointer border-slate-100 rounded-2xl hover:border-red-400 hover:bg-red-50/30 group">
                                    <input type="radio" name="status" value="Sakit" class="w-5 h-5 text-red-600 focus:ring-red-500">
                                    <div class="ml-4">
                                        <span class="text-base font-bold text-slate-800">Sakit</span>
                                        <p class="text-xs font-medium text-slate-500">Berhalangan sakit</p>
                                    </div>
                                    <i class='absolute text-xl transition-opacity opacity-0 bx bx-check-circle text-red-600 right-4 group-has-[:checked]:opacity-100'></i>
                                </label>
                            </div>
                        </div>

                        <div id="work-mode-section" class="hidden">
                            <label class="block mb-4 text-xs font-bold tracking-widest uppercase text-slate-500">Mode Kerja</label>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="relative flex items-center p-4 transition-all duration-200 border-2 cursor-pointer border-slate-100 rounded-2xl hover:border-blue-400 hover:bg-blue-50/30 group">
                                    <input type="radio" name="mode_kerja" value="WFO" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4">
                                        <span class="text-base font-bold text-slate-800">WFO</span>
                                        <p class="text-xs font-medium text-slate-500">Work From Office</p>
                                    </div>
                                    <i class='absolute text-xl transition-opacity opacity-0 bx bx-check-circle text-blue-600 right-4 group-has-[:checked]:opacity-100'></i>
                                </label>

                                <label class="relative flex items-center p-4 transition-all duration-200 border-2 cursor-pointer border-slate-100 rounded-2xl hover:border-blue-400 hover:bg-blue-50/30 group">
                                    <input type="radio" name="mode_kerja" value="WFA" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4">
                                        <span class="text-base font-bold text-slate-800">WFA</span>
                                        <p class="text-xs font-medium text-slate-500">Work From Anywhere</p>
                                    </div>
                                    <i class='absolute text-xl transition-opacity opacity-0 bx bx-check-circle text-blue-600 right-4 group-has-[:checked]:opacity-100'></i>
                                </label>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="status" value="Hadir">
                    @endif

                    <div>
                        <label class="block mb-4 text-xs font-bold tracking-widest uppercase text-slate-500">Jenis Absensi</label>
                        <div id="hadir-types-container" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @if(!$hasMasuk)
                                <button type="button" id="checkin-btn"
                                        class="p-4 transition-all duration-200 border-2 border-slate-100 rounded-2xl hover:border-green-400 hover:bg-green-50/30 group focus:outline-none">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 text-xl text-green-600 transition-colors bg-green-50 rounded-xl group-hover:bg-green-100">
                                            <i class='bx bx-log-in'></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="block text-sm font-bold text-slate-800">Absensi Masuk</span>
                                            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-green-600">Check In</span>
                                        </div>
                                    </div>
                                </button>
                            @else
                                <button type="button" id="checkout-btn"
                                        class="p-4 transition-all duration-200 border-2 border-slate-100 rounded-2xl hover:border-red-400 hover:bg-red-50/30 group focus:outline-none col-span-2">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 text-xl text-red-600 transition-colors bg-red-50 rounded-xl group-hover:bg-red-100">
                                            <i class='bx bx-log-out'></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="block text-sm font-bold text-slate-800">Absensi Pulang</span>
                                            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-red-600">Check Out</span>
                                        </div>
                                    </div>
                                </button>
                            @endif
                        </div>

                        <div id="izin-sakit-types-container" class="hidden">
                            <button type="button" id="keterangan-btn"
                                    class="w-full p-4 transition-all duration-200 border-2 border-slate-100 rounded-2xl hover:border-indigo-400 hover:bg-indigo-50/30 group focus:outline-none">
                                <div class="flex items-center justify-center gap-3 text-center">
                                    <div class="flex items-center justify-center w-10 h-10 text-xl text-indigo-600 transition-colors bg-indigo-50 rounded-xl group-hover:bg-indigo-100">
                                        <i class='bx bx-info-circle'></i>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-sm font-bold text-slate-800">Kirim Penjelasan</span>
                                        <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-indigo-600">Submit Keterangan</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                @endif

                @if(!$hasPulang && !$isIzinSakit)
                    <div>
                        <label for="notes" class="block mb-4 text-xs font-bold tracking-widest uppercase text-slate-500">
                            Catatan (Opsional)
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                                    class="w-full px-4 py-3 text-sm font-medium transition-all duration-200 border resize-none rounded-2xl text-slate-700 border-slate-200 bg-slate-50/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white placeholder-slate-400"
                                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        <p class="mt-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Catatan akan disimpan sebagai informasi tambahan.</p>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-slate-100">
                        <button type="submit" id="submit-btn"
                                class="inline-flex items-center gap-2 px-8 py-3 text-sm font-extrabold text-white transition-all duration-200 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class='text-lg bx bx-send'></i>
                            <span>Kirim Absensi</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @vite('resources/js/peserta/absensi.js')
@endsection
