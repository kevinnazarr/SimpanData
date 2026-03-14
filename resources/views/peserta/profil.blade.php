@extends('layouts.app')

@section('title', 'Data Diri')

@php
    /** @var \App\Models\User $user */
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @vite(['resources/css/peserta/profil.css', 'resources/js/peserta/profil.js'])
@endpush

@section('content')
    @php
        $jenisKegiatan = $peserta->jenis_kegiatan ?? 'Kegiatan';
        $isNewUser = !$peserta;
    @endphp

    <div class="space-y-6">
        @if (session('success'))
            <div
                class="flex items-center justify-between p-4 border-l-4 border-green-500 rounded-lg shadow-sm bg-green-50 animate-fade-in">
                <div class="flex items-center space-x-3">
                    <i class='text-xl text-green-500 bx bxs-check-circle'></i>
                    <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class='text-xl bx bx-x'></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="flex items-center justify-between p-4 border-l-4 border-red-500 rounded-lg shadow-sm bg-red-50 animate-fade-in">
                <div class="flex items-center space-x-3">
                    <i class='text-xl text-red-500 bx bxs-error-circle'></i>
                    <p class="text-sm font-semibold text-red-800">Terdapat kesalahan input. Silakan periksa kembali formulir
                        Anda.</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class='text-xl bx bx-x'></i>
                </button>
            </div>
        @endif

        <div id="profileView" class="{{ $isNewUser ? 'hidden' : '' }} space-y-6">
            <div class="relative p-6 overflow-hidden bg-white card md:p-8 shadow-soft group">
                <div class="relative z-10 flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex flex-col items-center gap-6 md:flex-row">
                        <div class="relative">
                            @if ($user->photo_profile)
                                <img src="{{ asset('storage/' . $user->photo_profile) }}"
                                    class="object-cover w-32 h-32 border-4 border-white shadow-lg rounded-2xl">
                            @else
                                <div
                                    class="flex items-center justify-center w-32 h-32 text-5xl font-bold border-2 shadow-inner bg-primary-light text-primary rounded-2xl border-primary/10">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </div>
                            @endif
                            <div
                                class="absolute flex items-center justify-center w-10 h-10 bg-green-500 border-4 border-white rounded-full shadow-md -bottom-2 -right-2">
                                <i class='text-2xl text-white bx bx-check'></i>
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">
                                {{ $peserta->nama ?? $user->username }}</h1>
                            <div class="flex flex-wrap items-center justify-center gap-2 mt-2 md:justify-start">
                                <p
                                    class="px-3 py-1 text-xs font-bold tracking-widest uppercase border rounded-full text-primary bg-primary/5 border-primary/10 mb-0">
                                    {{ $jenisKegiatan }} - {{ $peserta->jurusan ?? 'Jurusan Belum Diisi' }}
                                </p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-4 mt-5 md:justify-start">
                                <span
                                    class="px-3.5 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold border border-slate-200 uppercase tracking-wider shadow-sm">
                                    <i class='bx bx-buildings mr-1.5 text-base'></i>
                                    {{ $peserta->asal_sekolah_universitas ?? 'Institusi Belum Diisi' }}
                                </span>
                                <span
                                    class="px-3.5 py-1.5 bg-blue-100/50 text-blue-700 rounded-lg text-xs font-bold border border-blue-200 uppercase tracking-wider shadow-sm">
                                    <i class='bx bx-id-card mr-1.5 text-base'></i>
                                    {{ $peserta && $peserta->nim_nis ? $peserta->nim_nis : '-' }}
                                </span>
                                <span
                                    class="px-3.5 py-1.5 bg-indigo-100/50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-200 uppercase tracking-wider shadow-sm">
                                    <i class='bx bx-task mr-1.5 text-base'></i>
                                    {{ $peserta && $peserta->tugas ? $peserta->tugas : '-' }}
                                </span>
                                @if (!$peserta)
                                    <span
                                        class="px-3.5 py-1.5 bg-blue-100/50 text-blue-700 rounded-lg text-xs font-bold border border-blue-200 uppercase tracking-wider shadow-sm">
                                        <i class='bx bx-check-shield mr-1.5 text-base'></i> Akun {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button id="btnPrintIDCard"
                            class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-dark transition-all shadow-sm active:scale-[0.98]">
                            <i class='text-base bx bx-printer'></i> Cetak ID Card
                        </button>
                        <button id="btnEditProfile"
                            class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm active:scale-[0.98]">
                            <i class='text-base bx bx-edit-alt'></i> Edit Profil
                        </button>
                    </div>
                </div>
                <i
                    class='absolute transition-colors opacity-50 bx bx-user -right-8 -bottom-8 text-9xl text-slate-50 group-hover:text-slate-100'></i>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="grid grid-rows-2 gap-6 lg:col-span-2">
                    <div class="h-full p-6 bg-white card md:p-8 shadow-soft">
                        <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-2xl shadow-inner bg-primary/10 text-primary rounded-2xl">
                                <i class='bx bx-briefcase-alt-2'></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-extrabold tracking-tight text-slate-900">Data Akademik &
                                    {{ $jenisKegiatan }}</h4>
                                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Informasi
                                    institusi dan masa kegiatan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-10 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Asal Sekolah /
                                    Universitas</label>
                                <p class="text-base font-bold text-slate-800">
                                    {{ $peserta->asal_sekolah_universitas ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Program Studi /
                                    Jurusan</label>
                                <p class="text-base font-bold text-slate-800">{{ $peserta->jurusan ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Jenis
                                    Kegiatan</label>
                                <p class="text-base font-bold text-slate-800">{{ $peserta->jenis_kegiatan ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">NIM / NIS</label>
                                <p class="text-base font-bold text-slate-800">{{ $peserta->nim_nis ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Tugas</label>
                                <p class="text-base font-bold text-slate-800">{{ $peserta->tugas ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Masa
                                    Kegiatan</label>
                                <p class="text-base font-bold text-slate-800">
                                    {{ $peserta && $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') : '-' }}
                                    s/d
                                    {{ $peserta && $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="h-full p-6 bg-white card md:p-8 shadow-soft">
                        <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-2xl text-orange-600 bg-orange-100 shadow-inner rounded-2xl">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-extrabold tracking-tight text-slate-900">Informasi Kontak & Domisili
                                </h4>
                                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Alamat lengkap dan
                                    nomor yang bisa dihubungi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-10 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Nomor Telepon /
                                    WA</label>
                                <p class="text-base font-bold text-slate-800">{{ $peserta->no_telepon ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Alamat
                                    Email</label>
                                <p class="text-base font-bold truncate text-slate-800">{{ $user->email }}</p>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Alamat
                                    Lengkap</label>
                                <p class="text-base font-bold leading-relaxed text-slate-800">{{ $peserta->alamat ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-rows-2 gap-6">
                    <div class="h-full p-6 bg-white border card md:p-8 shadow-soft border-slate-100">
                        <div class="flex items-center gap-3 pb-5 mb-8 border-b border-slate-100">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-2xl text-blue-600 shadow-inner bg-blue-50 rounded-2xl">
                                <i class='bx bx-key'></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-extrabold tracking-tight text-slate-900">Keamanan Akun</h4>
                                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Detail login
                                    sistem</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 border shadow-inner bg-slate-50 border-slate-100 rounded-xl">
                                <p class="mb-1 text-xs font-bold tracking-widest uppercase text-slate-500">Username</p>
                                <p class="text-base font-bold text-slate-800">{{ $user->username }}</p>
                            </div>
                            <div class="p-4 border shadow-inner bg-slate-50 border-slate-100 rounded-xl">
                                <p class="mb-1 text-xs font-bold tracking-widest uppercase text-slate-500">Email</p>
                                <p class="text-base font-bold truncate text-slate-800">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="h-full p-6 bg-white border card md:p-8 shadow-soft border-slate-100">
                        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                            <div
                                class="flex items-center justify-center w-10 h-10 text-xl text-indigo-600 bg-indigo-50 rounded-xl">
                                <i class='bx bx-info-circle'></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-extrabold tracking-tight text-slate-900">Panduan Sistem</h4>
                                <p class="text-sm font-bold tracking-widest uppercase text-slate-500">Langkah awal untuk
                                    Anda</p>
                            </div>
                        </div>

                        <ul class="space-y-4">
                            <li class="flex gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 mt-0.5 text-sm font-extrabold text-indigo-700 bg-indigo-100 rounded-full shadow-sm">
                                    1</div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Lengkapi Profil</p>
                                    <p class="text-sm font-medium leading-relaxed text-slate-600">Pastikan data diri
                                        valid untuk verifikasi.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 mt-0.5 text-sm font-extrabold text-indigo-700 bg-indigo-100 rounded-full shadow-sm">
                                    2</div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Presensi Harian</p>
                                    <p class="text-sm font-medium leading-relaxed text-slate-600">Lakukan absen Masuk &
                                        Pulang setiap hari.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 mt-0.5 text-sm font-extrabold text-indigo-700 bg-indigo-100 rounded-full shadow-sm">
                                    3</div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Kirim Laporan</p>
                                    <p class="text-sm font-medium leading-relaxed text-slate-600">Update progres
                                        kegiatan Anda secara rutin.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="editView" class="{{ $isNewUser ? '' : 'hidden' }} animate-fade-in">
            <form action="{{ route('peserta.profil.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div class="p-6 bg-white card md:p-8 shadow-soft">
                    <div class="flex items-center justify-between pb-4 mb-8 border-b border-slate-50">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-12 h-12 text-2xl shadow-inner bg-primary/10 text-primary rounded-2xl">
                                <i class='bx bx-user-circle'></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-extrabold tracking-tight text-slate-900">
                                    {{ $isNewUser ? 'Lengkapi Data Diri' : 'Update Profil Peserta' }}</h4>
                                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Harap isi data
                                    dengan benar untuk keperluan administrasi</p>
                            </div>
                        </div>
                        @if (!$isNewUser)
                            <button type="button" id="btnCancelEdit"
                                class="text-xs font-bold uppercase transition-colors text-slate-400 hover:text-slate-600">Batal</button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="flex flex-col items-center space-y-4 md:col-span-2 md:items-start">
                            <label class="w-full text-xs font-bold tracking-widest uppercase text-slate-500">Foto
                                Profil</label>
                            <div class="flex items-center gap-6">
                                <div id="imagePreview"
                                    class="flex items-center justify-center w-24 h-24 overflow-hidden border-2 border-dashed rounded-2xl bg-slate-100 border-slate-200">
                                    @if ($user->photo_profile)
                                        <img src="{{ asset('storage/' . $user->photo_profile) }}"
                                            class="object-cover w-full h-full">
                                    @else
                                        <i class='text-3xl bx bx-camera text-slate-300'></i>
                                    @endif
                                </div>
                                <div class="space-y-2">
                                    <label for="fotoInput"
                                        class="inline-block px-4 py-2 text-xs font-bold tracking-wider uppercase transition-all border rounded-lg cursor-pointer bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100">Pilih
                                        Foto</label>
                                    <input type="file" name="foto" id="fotoInput" class="hidden"
                                        accept="image/*">
                                    <p class="text-[10px] text-slate-400">Format: JPG, PNG, JPEG. Max 2MB.</p>
                                    @error('foto')
                                        <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $peserta->nama ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('nama') @enderror"
                                placeholder="Masukkan nama lengkap...">
                            @error('nama')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Asal Sekolah /
                                Universitas</label>
                            <input type="text" name="asal_sekolah_universitas"
                                value="{{ old('asal_sekolah_universitas', $peserta->asal_sekolah_universitas ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('asal_sekolah_universitas') @enderror"
                                placeholder="SMK Negeri / Universitas...">
                            @error('asal_sekolah_universitas')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan', $peserta->jurusan ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('jurusan') @enderror"
                                placeholder="Teknik Informatika / Multimedia...">
                            @error('jurusan')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Jenis
                                Kegiatan</label>
                            <select name="jenis_kegiatan"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('jenis_kegiatan') @enderror">
                                <option value="PKL"
                                    {{ old('jenis_kegiatan', $peserta->jenis_kegiatan ?? '') == 'PKL' ? 'selected' : '' }}>
                                    PKL</option>
                                <option value="Magang"
                                    {{ old('jenis_kegiatan', $peserta->jenis_kegiatan ?? '') == 'Magang' ? 'selected' : '' }}>
                                    Magang</option>
                            </select>
                            @error('jenis_kegiatan')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $peserta && $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('tanggal_mulai') @enderror">
                            @error('tanggal_mulai')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Tanggal
                                Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $peserta && $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('tanggal_selesai') @enderror">
                            @error('tanggal_selesai')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">NIM / NIS</label>
                            <input type="text" name="nim_nis" value="{{ old('nim_nis', $peserta->nim_nis ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('nim_nis') @enderror"
                                placeholder="Masukkan NIM / NIS...">
                            @error('nim_nis')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Tugas</label>
                            <input type="text" name="tugas" value="{{ old('tugas', $peserta->tugas ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('tugas') @enderror"
                                placeholder="Masukkan Tugas...">
                            @error('tugas')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Nomor HP /
                                WhatsApp</label>
                            <input type="text" name="no_telepon"
                                value="{{ old('no_telepon', $peserta->no_telepon ?? '') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all @error('no_telepon') @enderror"
                                placeholder="0812...">
                            @error('no_telepon')
                                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Alamat Email
                                (Akun)</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-3 text-base font-bold border outline-none cursor-not-allowed bg-slate-100 border-slate-200 rounded-xl text-slate-500"
                                placeholder="email@user.com">
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold tracking-widest uppercase text-slate-500">Alamat
                                    Lengkap</label>
                                <button type="button" id="btnGetGPS"
                                    class="flex items-center gap-1.5 px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-indigo-100 transition-all border border-indigo-200 shadow-sm active:scale-95">
                                    <i class='text-base bx bx-map-pin'></i> Ambil dari GPS
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitudeInput"
                                value="{{ old('latitude', $peserta->latitude ?? '') }}">
                            <input type="hidden" name="longitude" id="longitudeInput"
                                value="{{ old('longitude', $peserta->longitude ?? '') }}">
                            <textarea name="alamat" id="alamatInput" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base font-bold text-slate-800 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all @error('alamat') @enderror"
                                placeholder="Alamat tinggal saat ini...">{{ old('alamat', $peserta->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="mapContainer" class="map-preview-container">
                                <div
                                    class="mt-6 overflow-hidden bg-white border shadow-sm border-slate-200 rounded-2xl ring-4 ring-slate-50">
                                    <div
                                        class="flex items-center justify-between px-4 py-3 border-b bg-slate-50 border-slate-100">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 rounded-lg shadow-inner bg-primary/10 text-primary">
                                                <i class='text-lg bx bx-map-pin'></i>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[11px] font-extrabold text-slate-900 uppercase tracking-widest leading-none">
                                                    Status Lokasi</p>
                                                <p id="locationLabel"
                                                    class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">
                                                    Belum Terdeteksi</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-[10px] text-blue-600 font-extrabold uppercase bg-blue-50 px-2 py-1 rounded-md border border-blue-100 italic tracking-wider">Live
                                            Preview</span>
                                    </div>

                                    <div class="p-1">
                                        <div id="map" class="border border-slate-100"></div>
                                    </div>

                                    <div class="p-4 bg-white">
                                        <div
                                            class="flex items-start gap-3 p-3 border border-indigo-100 bg-indigo-50/50 rounded-xl">
                                            <i class='bx bx-info-circle text-indigo-500 text-lg mt-0.5'></i>
                                            <p class="text-xs font-medium leading-relaxed text-indigo-900">
                                                Pin berhasil diletakkan. Alamat lengkap telah diperbarui secara otomatis di
                                                kolom teks di atas. Anda dapat **menggeser penanda** untuk koreksi manual.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 md:col-span-2">
                            <button type="submit"
                                class="w-full py-4 px-6 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-dark transition-all shadow-lg active:scale-[0.98]">
                                {{ $isNewUser ? 'Simpan Data Diri' : 'Simpan Perubahan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('modals')
    <div id="printModalOverlay" class="hidden fixed inset-0 z-[950] bg-gray-900/50 backdrop-blur-sm"
        onclick="closePrintModal(event)"></div>
    <div id="printModal" class="hidden fixed inset-0 z-[1100] overflow-y-auto p-4" onclick="closePrintModal(event)">
        <div class="flex items-center justify-center min-h-full">
            <div class="relative w-full max-w-2xl transition-all transform duration-300 scale-95 opacity-0"
                id="printModalContainer" onclick="event.stopPropagation()">
                <div class="absolute right-0 z-50 -top-12">
                    <button onclick="closePrintModal(event)"
                        class="flex items-center justify-center w-10 h-10 text-white transition-all bg-white/20 hover:bg-white/30 rounded-full backdrop-blur-md">
                        <i class='text-2xl bx bx-x'></i>
                    </button>
                </div>

                <div class="overflow-hidden bg-white shadow-2xl rounded-2xl">
                    <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 text-primary bg-primary/10 rounded-lg">
                                <i class='text-xl bx bx-id-card'></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Preview ID Card</h4>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Tampilan sebelum
                                    dicetak</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a id="downloadIdCardBtn" href="#" download
                                class="group relative flex items-center justify-start w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:w-36 hover:bg-emerald-600 hover:text-white overflow-hidden shadow-sm active:scale-95">
                                <div class="flex items-center justify-center min-w-[40px]">
                                    <i class='text-xl bx bx-download'></i>
                                </div>
                                <span
                                    class="opacity-0 group-hover:opacity-100 transition-all duration-300 delay-100 font-bold text-sm whitespace-nowrap pr-4 pointer-events-none">Download
                                    PDF</span>
                            </a>

                            <button onclick="document.getElementById('printFrame').contentWindow.print()"
                                class="group relative flex items-center justify-start w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:w-28 hover:bg-indigo-600 hover:text-white overflow-hidden shadow-sm active:scale-95">
                                <div class="flex items-center justify-center min-w-[40px]">
                                    <i class='text-xl bx bx-printer'></i>
                                </div>
                                <span
                                    class="opacity-0 group-hover:opacity-100 transition-all duration-300 delay-100 font-bold text-sm whitespace-nowrap pr-4 pointer-events-none">Cetak</span>
                            </button>
                        </div>
                    </div>
                    <div
                        class="relative bg-slate-100/50 aspect-[4/5] sm:aspect-video flex items-center justify-center p-4 sm:p-8">
                        <div id="printLoader"
                            class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-300">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin">
                                </div>
                            </div>
                            <p class="mt-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Menyiapkan
                                Preview...</p>
                        </div>
                        <iframe id="printFrame" src=""
                            class="w-full h-full border-none shadow-xl rounded-xl bg-white"
                            onload="document.getElementById('printLoader').classList.add('hidden')"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.printUrl = '{{ route('peserta.profil.print') }}';
    </script>
@endpush

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        window.openPrintModal = function(id) {
            const modal = document.getElementById('printModal');
            const overlay = document.getElementById('printModalOverlay');
            const frame = document.getElementById('printFrame');
            const loader = document.getElementById('printLoader');
            const baseUrl = '{{ route('peserta.profil.print') }}';

            if (!modal || !frame || !loader) return;

            loader.classList.remove('opacity-0', 'pointer-events-none');
            frame.src = baseUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closePrintModal = function(e) {
            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }

            const modal = document.getElementById('printModal');
            const overlay = document.getElementById('printModalOverlay');
            const frame = document.getElementById('printFrame');

            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (overlay) overlay.classList.add('hidden');
                if (frame) frame.src = '';
                document.body.style.overflow = '';
            }
        };
    </script>
@endsection
