<form id="editPesertaForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" id="edit_peserta_id" name="peserta_id" value="{{ $peserta->id }}">
    <div class="space-y-6">
        <div class="p-4 border border-gray-200 rounded-lg md:p-6 bg-gray-50">
            <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                <i class='text-indigo-600 bx bx-user'></i>
                Data Akun
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="edit_username" class="block mb-2 text-sm font-medium text-gray-700">Username *</label>
                    <input type="text" id="edit_username" name="username" value="{{ $peserta->user->username }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_email" class="block mb-2 text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="edit_email" name="email" value="{{ $peserta->user->email }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_password" class="block mb-2 text-sm font-medium text-gray-700">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" id="edit_password" name="password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
            </div>
        </div>

        <div class="p-4 border border-gray-200 rounded-lg md:p-6 bg-gray-50">
            <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                <i class='text-indigo-600 bx bx-id-card'></i>
                Data Peserta
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="edit_nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" id="edit_nama" name="nama" value="{{ $peserta->nama }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_asal_sekolah" class="block mb-2 text-sm font-medium text-gray-700">Asal Sekolah/Universitas *</label>
                    <input type="text" id="edit_asal_sekolah" name="asal_sekolah_universitas" value="{{ $peserta->asal_sekolah_universitas }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_jurusan" class="block mb-2 text-sm font-medium text-gray-700">Jurusan *</label>
                    <input type="text" id="edit_jurusan" name="jurusan" value="{{ $peserta->jurusan }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_nim_nis" class="block mb-2 text-sm font-medium text-gray-700">NIM / NIS</label>
                    <input type="text" id="edit_nim_nis" name="nim_nis" value="{{ $peserta->nim_nis }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_tugas" class="block mb-2 text-sm font-medium text-gray-700">Tugas</label>
                    <input type="text" id="edit_tugas" name="tugas" value="{{ $peserta->tugas }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div class="md:col-span-2">
                    <label for="edit_alamat" class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="edit_alamat" name="alamat" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $peserta->alamat }}</textarea>
                </div>
                <div>
                    <label for="edit_no_telepon" class="block mb-2 text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" id="edit_no_telepon" name="no_telepon" value="{{ $peserta->no_telepon }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="edit_foto" class="block mb-2 text-sm font-medium text-gray-700">Foto</label>
                    @if($peserta->user->photo_profile)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$peserta->user->photo_profile) }}" alt="{{ $peserta->nama }}" class="object-cover w-20 h-20 rounded-lg">
                    </div>
                    @endif
                    <input type="file" id="edit_foto" name="foto" accept="image/*"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                    <label for="edit_jenis_kegiatan" class="block mb-2 text-sm font-medium text-gray-700">Jenis Kegiatan *</label>
                    <select id="edit_jenis_kegiatan" name="jenis_kegiatan" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Jenis</option>
                        <option value="PKL" {{ $peserta->jenis_kegiatan == 'PKL' ? 'selected' : '' }}>PKL</option>
                        <option value="Magang" {{ $peserta->jenis_kegiatan == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_status" class="block mb-2 text-sm font-medium text-gray-700">Status *</label>
                    <select id="edit_status" name="status" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Status</option>
                        <option value="Aktif" {{ $peserta->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Selesai" {{ $peserta->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Arsip" {{ $peserta->status == 'Arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Mulai *</label>
                    <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" value="{{ $peserta->tanggal_mulai->format('Y-m-d') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="edit_tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Selesai *</label>
                    <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" value="{{ $peserta->tanggal_selesai->format('Y-m-d') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
            </div>
        </div>
    </div>
</form>
