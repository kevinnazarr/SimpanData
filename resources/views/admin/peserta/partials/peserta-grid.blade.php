@if($peserta->count() > 0)
<div class="peserta-grid">
    <div class="grid grid-cols-1 gap-4 md:gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($peserta as $item)
        <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-lg peserta-card rounded-xl hover-card hover-lift">
            <div class="relative p-5 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        @if($item->foto)
                        <div class="relative flex-shrink-0">
                            <img src="{{ asset('storage/'.$item->foto) }}"
                                alt="{{ $item->nama }}"
                                class="object-cover border-white rounded-full shadow-md w-14 h-14 border-3">
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full {{ $item->status == 'Aktif' ? 'bg-emerald-500' : ($item->status == 'Selesai' ? 'bg-amber-500' : 'bg-gray-400') }}"></div>
                        </div>
                        @else
                        <div class="relative flex items-center justify-center flex-shrink-0 text-xl font-bold text-white rounded-full shadow-md w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                            <div class="absolute bottom-0 right-0 w-3 h-3 border-2 border-white rounded-full {{ $item->status == 'Aktif' ? 'bg-emerald-500' : ($item->status == 'Selesai' ? 'bg-amber-500' : 'bg-gray-400') }}"></div>
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate transition-colors duration-200 hover:text-indigo-600">
                                {{ $item->nama }}
                            </h3>
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $item->jenis_kegiatan == 'PKL' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                                    {{ $item->jenis_kegiatan }}
                                </span>
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $item->status == 'Aktif' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($item->status == 'Selesai' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-gray-100 text-gray-800 border border-gray-200') }}">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                <a href="{{ route('admin.peserta.show', $item->id) }}"
                    class="p-2 text-gray-400 transition-all duration-200 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 hover:scale-110"
                    title="Detail Monitoring">
                    <i class='text-xl bx bx-show'></i>
                </a>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="text-sm text-gray-500">
                        ID: <span class="font-medium text-gray-700">{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $item->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="p-5">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-gray-600 bx bx-book'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500">Jurusan</p>
                            <p class="font-medium text-gray-800 truncate">{{ $item->jurusan }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 transition-colors duration-200 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class='text-lg text-gray-600 bx bx-building-house'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500">Asal Sekolah/Universitas</p>
                            <p class="font-medium text-gray-800 truncate">{{ $item->asal_sekolah_universitas }}</p>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <div class="flex items-center gap-2 px-4 text-gray-500 bg-white">
                                <i class='text-sm bx bx-calendar'></i>
                                <span class="text-xs font-medium">Periode Kegiatan</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-2">
                        <div class="text-center">
                            <p class="text-xs font-medium text-gray-500">Mulai</p>
                            <p class="text-sm font-bold text-gray-800">{{ $item->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div class="w-8 h-px bg-gray-300"></div>
                        <div class="text-center">
                            <p class="text-xs font-medium text-gray-500">Selesai</p>
                            <p class="text-sm font-bold text-gray-800">{{ $item->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                <i class='text-sm text-blue-600 bx bx-phone'></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-blue-600">Telepon</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $item->no_telepon ?: 'Tidak ada' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-green-50 hover:bg-green-100 transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                                <i class='text-sm text-green-600 bx bx-envelope'></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-green-600">Email</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $item->user->email ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-5 mt-5 border-t border-gray-100">
                    <button data-edit-id="{{ $item->id }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 hover:shadow-lg hover:scale-105 group">
                        <i class='text-base bx bx-edit'></i>
                        <span>Edit</span>
                    </button>

                    <button data-delete-id="{{ $item->id }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg hover:from-red-600 hover:to-pink-700 hover:shadow-lg hover:scale-105 group">
                        <i class='text-base bx bx-trash'></i>
                        <span>Hapus</span>
                    </button>

                    <button data-print-id="{{ $item->id }}"
                        class="inline-flex items-center justify-center w-12 px-3 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200 hover:shadow-lg hover:scale-105"
                        title="Cetak ID Card">
                        <i class='text-base bx bx-id-card'></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($peserta->hasPages())
<div class="mt-8">
    <div class="pagination">
        {{ $peserta->onEachSide(1)->links() }}
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
    <h3 class="mb-3 text-xl font-semibold text-gray-700">Tidak ada peserta ditemukan</h3>
    <p class="max-w-md mb-8 text-gray-500">
        Tidak ada data peserta yang sesuai dengan pencarian atau filter yang Anda terapkan.
    </p>
    <div class="flex gap-3">
        <button onclick="resetFilters()"
                class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all duration-200 hover:scale-105 inline-flex items-center">
            <i class='mr-2 bx bx-refresh'></i>Reset Filter
        </button>
        <button onclick="openCreateModal()"
            class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 hover:scale-105 inline-flex items-center">
            <i class='mr-2 bx bx-plus'></i>Tambah Peserta Baru
        </button>
    </div>
</div>
@endif

