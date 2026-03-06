@if($peserta->count() > 0)
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($peserta as $p)
            <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-xl hover:shadow-lg hover:-translate-y-1">
                <div class="absolute top-3 right-3">
                    @if($p->penilaian)
                        <span class="flex w-3 h-3 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)]" title="Sudah Dinilai"></span>
                    @else
                        <span class="flex w-3 h-3 bg-amber-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]" title="Menunggu Penilaian"></span>
                    @endif
                </div>

                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if($p->foto)
                            <img src="{{ asset('storage/'.$p->foto) }}"
                                alt="{{ $p->nama }}"
                                class="object-cover w-12 h-12 border-2 border-gray-200 rounded-full">
                        @else
                            <div class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white rounded-full bg-gradient-to-br from-indigo-500 to-purple-500">
                                {{ strtoupper(substr($p->nama, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 truncate">{{ $p->nama }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ $p->asal_sekolah_universitas }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $p->jenis_kegiatan == 'PKL' ? 'bg-indigo-100 text-indigo-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $p->jenis_kegiatan }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if($p->penilaian)
                            <div class="p-3 rounded-lg bg-gradient-to-r from-emerald-50 to-teal-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Nilai Akhir</p>
                                        <p class="text-2xl font-bold text-emerald-600">{{ $p->penilaian->nilai_akhir }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-sm font-bold rounded
                                            {{ $p->penilaian->nilai_akhir >= 90 ? 'bg-emerald-100 text-emerald-700' :
                                                ($p->penilaian->nilai_akhir >= 80 ? 'bg-blue-100 text-blue-700' :
                                                ($p->penilaian->nilai_akhir >= 70 ? 'bg-amber-100 text-amber-700' :
                                                ($p->penilaian->nilai_akhir >= 60 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'))) }}">
                                            {{ $p->penilaian->nilai_akhir >= 90 ? 'A' :
                                                ($p->penilaian->nilai_akhir >= 80 ? 'B' :
                                                ($p->penilaian->nilai_akhir >= 70 ? 'C' :
                                                ($p->penilaian->nilai_akhir >= 60 ? 'D' : 'E'))) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <button onclick="openDetailModal({{ $p->id }})"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-indigo-600 transition-colors bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                    <i class='mr-1 bx bx-show'></i>
                                    Detail
                                </button>
                                <button onclick="openEditModal({{ $p->id }})"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700">
                                    <i class='mr-1 bx bx-edit'></i>
                                    Edit
                                </button>
                            </div>
                        @else
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="flex items-center justify-center gap-2 text-gray-400">
                                    <i class='text-xl bx bx-time-five'></i>
                                    <span class="text-sm">Belum dinilai</span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button onclick="openPenilaianModal({{ $p->id }}, '{{ $p->nama }}', '{{ $p->asal_sekolah_universitas }}', '{{ $p->jurusan }}', '{{ $p->foto }}')"
                                    class="w-full px-3 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700">
                                    <i class='mr-1 bx bx-star'></i>
                                    Beri Nilai
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4 mt-4 border-t border-gray-200">
        {{ $peserta->links() }}
    </div>
@else
    <div class="py-12 text-center">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100">
            <i class='text-3xl text-gray-400 bx bx-search-alt'></i>
        </div>
        <h3 class="text-lg font-medium text-gray-800">Tidak ada peserta ditemukan</h3>
        <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda</p>
    </div>
@endif
