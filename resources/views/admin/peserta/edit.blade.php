@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-4 md:mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.peserta.index') }}"
                class="p-2 text-gray-600 transition-colors duration-200 rounded-lg hover:bg-gray-100">
                <i class='text-xl bx bx-arrow-back'></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Peserta</h1>
                <p class="mt-1 text-gray-600">Perbarui data peserta {{ $peserta->nama }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="card">
            <div class="p-4 md:p-6">
        <form action="{{ route('admin.peserta.update', $peserta->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="p-4 border border-gray-200 rounded-lg md:p-6 bg-gray-50">
                <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                    <i class='text-indigo-600 bx bx-user'></i>
                    Data Akun
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username *</label>
                        <input type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $peserta->user->username) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('username') @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email *</label>
                        <input type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $peserta->user->email) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password"
                                id="password"
                                name="password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                    <i class='text-indigo-600 bx bx-id-card'></i>
                    Data Peserta
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap *</label>
                        <input type="text"
                                id="nama"
                                name="nama"
                                value="{{ old('nama', $peserta->nama) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama') @enderror">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asal_sekolah_universitas" class="block mb-2 text-sm font-medium text-gray-700">Asal Sekolah/Universitas *</label>
                        <input type="text"
                                id="asal_sekolah_universitas"
                                name="asal_sekolah_universitas"
                                value="{{ old('asal_sekolah_universitas', $peserta->asal_sekolah_universitas) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('asal_sekolah_universitas') @enderror">
                        @error('asal_sekolah_universitas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jurusan" class="block mb-2 text-sm font-medium text-gray-700">Jurusan *</label>
                        <input type="text"
                                id="jurusan"
                                name="jurusan"
                                value="{{ old('jurusan', $peserta->jurusan) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('jurusan') @enderror">
                        @error('jurusan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat"
                                    name="alamat"
                                    rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('alamat') @enderror">{{ old('alamat', $peserta->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon" class="block mb-2 text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="text"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon', $peserta->no_telepon) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('no_telepon') @enderror">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto" class="block mb-2 text-sm font-medium text-gray-700">Foto Profil</label>
                        @if($peserta->user->photo_profile)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$peserta->user->photo_profile) }}" alt="{{ $peserta->nama }}" class="object-cover w-20 h-20 rounded-lg">
                        </div>
                        @endif
                        <input type="file"
                                id="foto"
                                name="foto"
                                accept="image/*"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('foto') @enderror">
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="p-4 border border-gray-200 rounded-lg md:p-6 bg-gray-50">
                <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                    <i class='text-indigo-600 bx bx-calendar'></i>
                    Data Kegiatan
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="jenis_kegiatan" class="block mb-2 text-sm font-medium text-gray-700">Jenis Kegiatan *</label>
                        <select id="jenis_kegiatan"
                                name="jenis_kegiatan"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('jenis_kegiatan') @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="PKL" {{ old('jenis_kegiatan', $peserta->jenis_kegiatan) == 'PKL' ? 'selected' : '' }}>PKL</option>
                            <option value="Magang" {{ old('jenis_kegiatan', $peserta->jenis_kegiatan) == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        @error('jenis_kegiatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status *</label>
                        <select id="status"
                                name="status"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') @enderror">
                            <option value="">Pilih Status</option>
                            <option value="Aktif" {{ old('status', $peserta->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Selesai" {{ old('status', $peserta->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Mulai *</label>
                        <input type="date"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $peserta->tanggal_mulai->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_mulai') @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Selesai *</label>
                        <input type="date"
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $peserta->tanggal_selesai->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_selesai') @enderror">
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.peserta.index') }}"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class='mr-2 bx bx-save'></i>
                    Update Peserta
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>
</div>
@endsection
