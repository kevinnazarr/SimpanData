@php /** @var \App\Models\User $user */ @endphp
<div class="space-y-6">
    <div class="p-5 border border-gray-200 rounded-lg bg-gradient-to-br from-gray-50 to-white">
        <div class="flex items-center gap-4 mb-4">
            @if($user->photo_profile)
            <div class="relative flex-shrink-0">
                <img src="{{ asset('storage/'.$user->photo_profile) }}"
                    alt="{{ $user->username }}"
                    class="object-cover w-20 h-20 border-4 border-white rounded-full shadow-lg">
                @if($user->peserta && $user->peserta->is_lengkap)
                <div class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 border-2 border-white rounded-full bg-emerald-500">
                    <i class='text-xs text-white bx bx-check'></i>
                </div>
                @else
                <div class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 border-2 border-white rounded-full bg-amber-500">
                    <i class='text-xs text-white bx bx-error'></i>
                </div>
                @endif
            </div>
            @else
            <div class="relative flex items-center justify-center flex-shrink-0 w-20 h-20 text-3xl font-bold text-white rounded-full shadow-lg bg-gradient-to-br from-indigo-500 to-purple-500">
                {{ strtoupper(substr($user->username, 0, 1)) }}
                @if($user->peserta && $user->peserta->is_lengkap)
                <div class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 border-2 border-white rounded-full bg-emerald-500">
                    <i class='text-xs text-white bx bx-check'></i>
                </div>
                @else
                <div class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 border-2 border-white rounded-full bg-amber-500">
                    <i class='text-xs text-white bx bx-error'></i>
                </div>
                @endif
            </div>
            @endif

            <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-800">{{ $user->username }}</h4>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 border border-indigo-200 rounded-full">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->peserta && $user->peserta->is_lengkap)
                    <span class="px-3 py-1 text-xs font-semibold border rounded-full text-emerald-700 bg-emerald-100 border-emerald-200">
                        <i class='mr-1 bx bx-check'></i> Profil Terisi
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs font-semibold border rounded-full text-amber-700 bg-amber-100 border-amber-200">
                        <i class='mr-1 bx bx-error'></i> Profil Belum Lengkap
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 mt-4 border-t border-gray-200">
            <div>
                <p class="text-xs font-medium text-gray-500">ID User</p>
                <p class="text-sm font-semibold text-gray-800">{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Bergabung</p>
                <p class="text-sm font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    @if($user->peserta && $user->peserta->is_lengkap)
    <div class="space-y-6">
        <div>
            <h5 class="flex items-center gap-2 mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider">
                <i class='text-indigo-600 bx bx-graduation'></i>
                Informasi Akademik & Identitas
            </h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 shadow-sm">
                            <i class='text-lg text-white bx bx-id-card'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">NIM / NIS</p>
                            <p class="font-semibold text-gray-800 truncate">{{ $user->peserta->nim_nis ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 shadow-sm">
                            <i class='text-lg text-white bx bx-building-house'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">Asal Sekolah/Univ</p>
                            <p class="font-semibold text-gray-800 truncate">{{ $user->peserta->asal_sekolah_universitas }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 shadow-sm">
                            <i class='text-lg text-white bx bx-book-bookmark'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">Jurusan</p>
                            <p class="font-semibold text-gray-800 truncate">{{ $user->peserta->jurusan }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 shadow-sm">
                            <i class='text-lg text-white bx bx-phone'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">No. Telepon</p>
                            <p class="font-semibold text-gray-800 truncate">{{ $user->peserta->no_telepon ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h5 class="flex items-center gap-2 mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider">
                <i class='text-indigo-600 bx bx-briefcase'></i>
                Detail Program & Tugas
            </h5>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 border-indigo-100 col-span-1 md:col-span-2">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 shadow-indigo-200 shadow-lg shrink-0">
                            <i class='text-2xl text-white bx bx-task'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-indigo-600 uppercase">Nama Tugas / Project</p>
                            <p class="text-lg font-bold text-gray-900 leading-tight">{{ $user->peserta->tugas ?: 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-violet-500 to-purple-500 shadow-sm">
                            <i class='text-lg text-white bx bx-category'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">Jenis Kegiatan</p>
                            <p class="font-semibold text-gray-800 truncate">{{ $user->peserta->jenis_kegiatan }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-500 shadow-sm">
                            <i class='text-lg text-white bx bx-calendar'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase">Periode</p>
                            <p class="font-semibold text-gray-800 text-sm">
                                {{ $user->peserta->tanggal_mulai->format('d M Y') }} - {{ $user->peserta->tanggal_selesai->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h5 class="flex items-center gap-2 mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider">
                <i class='text-indigo-600 bx bx-map'></i>
                Informasi Alamat
            </h5>
            <div class="p-4 transition-colors border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-gray-600 to-gray-800 shadow-sm shrink-0">
                        <i class='text-lg text-white bx bx-map-alt'></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase">Alamat Lengkap</p>
                        <p class="font-semibold text-gray-800 whitespace-pre-line leading-relaxed">{{ $user->peserta->alamat ?: 'Alamat belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="p-8 text-center border rounded-lg border-amber-200 bg-amber-50">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100">
            <i class='text-3xl text-amber-600 bx bx-info-circle'></i>
        </div>
        <h5 class="mb-2 text-lg font-bold text-gray-800">Profil Belum Dilengkapi</h5>
        <p class="text-sm text-gray-600">
            Akun ini belum mengisi data profil peserta. Minta user untuk melengkapi profil melalui menu "Edit Profil" setelah login.
        </p>
    </div>
    @endif

    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
        <h5 class="mb-3 text-sm font-bold text-gray-700">Metadata</h5>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500">Dibuat pada</p>
                <p class="font-medium text-gray-800">{{ $user->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Terakhir diupdate</p>
                <p class="font-medium text-gray-800">{{ $user->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>
