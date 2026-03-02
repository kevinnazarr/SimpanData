@php /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\User[] $users */ @endphp
@if($users->count() > 0)
<div class="user-grid">
    <div class="grid grid-cols-1 gap-4 md:gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($users as $user)
        <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-lg user-card rounded-xl hover-card hover-lift">
            <div class="relative p-5 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        @if($user->peserta && $user->peserta->foto)
                        <div class="relative flex-shrink-0">
                            <img src="{{ asset('storage/'.$user->peserta->foto) }}"
                                alt="{{ $user->username }}"
                                class="object-cover border-white rounded-full shadow-md w-14 h-14 border-3">
                            @if($user->peserta && $user->peserta->is_lengkap)
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full bg-emerald-500"></div>
                            @else
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full bg-amber-500"></div>
                            @endif
                        </div>
                        @else
                        <div class="relative flex items-center justify-center flex-shrink-0 text-xl font-bold text-white rounded-full shadow-md w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500">
                            {{ strtoupper(substr($user->username, 0, 1)) }}
                            @if($user->peserta && $user->peserta->is_lengkap)
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full bg-emerald-500"></div>
                            @else
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full bg-amber-500"></div>
                            @endif
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate transition-colors duration-200 hover:text-indigo-600">
                                {{ $user->username }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <button data-show-id="{{ $user->id }}"
                                class="p-2 text-gray-400 transition-all duration-200 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 hover:scale-110"
                                title="Lihat Detail">
                            <i class='text-xl bx bx-show'></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="text-sm text-gray-500">
                        ID: <span class="font-medium text-gray-700">{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="p-5">
                <div class="space-y-4">
                    @if($user->peserta && $user->peserta->is_lengkap)
                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-emerald-50 hover:bg-emerald-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-emerald-600 bx bx-check-circle'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-emerald-600">Status Profil</p>
                            <p class="font-medium text-gray-800">
                                <i class='mr-1 bx bx-check'></i> Profil Terisi
                            </p>
                        </div>
                    </div>


                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-gray-600 bx bx-user'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500">Nama Lengkap</p>
                            <p class="font-medium text-gray-800 truncate">{{ $user->peserta->nama }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-gray-600 bx bx-building-house'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500">Sekolah/Universitas</p>
                            <p class="font-medium text-gray-800 truncate">{{ $user->peserta->asal_sekolah_universitas }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-gray-600 bx bx-book'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500">Jurusan</p>
                            <p class="font-medium text-gray-800 truncate">{{ $user->peserta->jurusan }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg">
                                <i class='text-sm text-indigo-600 bx bx-briefcase-alt'></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-indigo-600">Jenis</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $user->peserta->jenis_kegiatan }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg">
                                <i class='text-sm text-purple-600 bx bx-time'></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-purple-600">Status</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $user->peserta->status }}</p>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-amber-50 hover:bg-amber-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-amber-600 bx bx-x-circle'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-amber-600">Status Profil</p>
                            <p class="font-medium text-gray-800">
                                <i class='mr-1 bx bx-x'></i> Belum Mengisi Data Diri
                            </p>
                        </div>
                    </div>

                    <div class="p-4 text-center border border-gray-200 rounded-lg bg-gray-50">
                        <i class='mb-2 text-3xl text-gray-400 bx bx-info-circle'></i>
                        <p class="text-sm text-gray-600">Akun ini belum melengkapi data profil peserta</p>
                        <p class="mt-1 text-xs text-gray-500">Minta user untuk mengisi data profil</p>
                    </div>
                    @endif
                </div>

                <div class="flex gap-2 pt-5 mt-5 border-t border-gray-100">
                    <button data-show-id="{{ $user->id }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 hover:shadow-lg hover:scale-105 group">
                        <i class='text-base bx bx-show'></i>
                        <span>Detail</span>
                    </button>

                    @if($user->peserta)
                    <a href="{{ route('admin.peserta.index') }}?search={{ $user->peserta->nama }}"
                            class="inline-flex items-center justify-center w-12 px-3 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200 hover:shadow-lg hover:scale-105"
                            title="Lihat Peserta">
                        <i class='text-base bx bx-user'></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($users->hasPages())
<div class="mt-8">
    <div class="pagination">
        {{ $users->onEachSide(1)->links() }}
    </div>
</div>
@endif

@else
<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="relative mb-6">
        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <i class='text-4xl text-gray-400 bx bx-user-x'></i>
        </div>
    </div>
    <h3 class="mb-3 text-xl font-semibold text-gray-700">Tidak ada akun ditemukan</h3>
    <p class="max-w-md mb-8 text-gray-500">
        Tidak ada data akun peserta yang sesuai dengan pencarian atau filter yang Anda terapkan.
    </p>
    <div class="flex gap-3">
        <button onclick="resetFilters()"
                class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all duration-200 hover:scale-105 inline-flex items-center">
            <i class='mr-2 bx bx-refresh'></i>Reset Filter
        </button>
    </div>
</div>
@endif

