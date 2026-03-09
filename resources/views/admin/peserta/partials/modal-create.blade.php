<form id="createPesertaForm" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <div class="p-4 border border-gray-200 rounded-lg md:p-6 bg-gray-50">
            <h2 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-800">
                <i class='text-indigo-600 bx bx-user'></i>
                Data Akun
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="create_username" class="block mb-2 text-sm font-medium text-gray-700">Username *</label>
                    <input type="text" id="create_username" name="username" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_email" class="block mb-2 text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="create_email" name="email" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_password" class="block mb-2 text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" id="create_password" name="password" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password *</label>
                    <input type="password" id="create_password_confirmation" name="password_confirmation" required
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
                    <label for="create_nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" id="create_nama" name="nama" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_asal_sekolah" class="block mb-2 text-sm font-medium text-gray-700">Asal Sekolah/Universitas *</label>
                    <input type="text" id="create_asal_sekolah" name="asal_sekolah_universitas" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_jurusan" class="block mb-2 text-sm font-medium text-gray-700">Jurusan *</label>
                    <input type="text" id="create_jurusan" name="jurusan" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_tugas" class="block mb-2 text-sm font-medium text-gray-700">Tugas</label>
                    <input type="text" id="create_tugas" name="tugas"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: Web Developer">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div class="md:col-span-2">
                    <label for="create_alamat" class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="create_alamat" name="alamat" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                    <label for="create_no_telepon" class="block mb-2 text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" id="create_no_telepon" name="no_telepon"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="create_foto" class="block mb-2 text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" id="create_foto" name="foto" accept="image/*"
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
                    <label for="create_jenis_kegiatan" class="block mb-2 text-sm font-medium text-gray-700">Jenis Kegiatan *</label>
                    <select id="create_jenis_kegiatan" name="jenis_kegiatan" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Jenis</option>
                        <option value="PKL">PKL</option>
                        <option value="Magang">Magang</option>
                    </select>
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_status" class="block mb-2 text-sm font-medium text-gray-700">Status *</label>
                    <select id="create_status" name="status" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Arsip">Arsip</option>
                    </select>
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Mulai *</label>
                    <input type="date" id="create_tanggal_mulai" name="tanggal_mulai" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
                <div>
                    <label for="create_tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Selesai *</label>
                    <input type="date" id="create_tanggal_selesai" name="tanggal_selesai" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="mt-1 text-sm text-red-600 error-message"></div>
                </div>
            </div>
        </div>
    </div>
</form>
