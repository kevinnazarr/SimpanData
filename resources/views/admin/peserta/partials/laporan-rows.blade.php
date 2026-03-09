@forelse($laporans as $laporan)
    <div class="relative pl-14 group">
        <div
            class="absolute left-0 top-1 w-10 h-10 bg-white border-2 border-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all duration-500 z-10 shadow-sm shadow-indigo-100/50">
            <i class='bx bx-file-blank text-xl'></i>
        </div>
        <div
            class="bg-white border border-gray-100 rounded-[2rem] p-8 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-500">
            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <h4
                            class="text-lg font-black text-gray-900 group-hover:text-indigo-700 transition-colors font-display tracking-tight">
                            {{ $laporan->judul }}</h4>
                        <span
                            class="px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $laporan->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($laporan->status == 'Revisi' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100') }}">
                            {{ $laporan->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 italic">
                        "{{ $laporan->deskripsi }}"</p>

                    <div class="flex flex-wrap items-center gap-6">
                        <div
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                            <i class='bx bx-time-five text-base text-indigo-300'></i>
                            {{ $laporan->tanggal_laporan->format('d F Y') }}
                        </div>
                        @if ($laporan->file_path)
                            <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-4 py-1.5 rounded-xl border border-indigo-100 transition-all">
                                <i class='bx bx-link-alt text-base'></i>
                                Berkas Pendukung
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex">
                    <a href="{{ route('admin.laporan.harian.show', $laporan->id) }}"
                        class="w-full lg:w-auto px-8 py-3 bg-gray-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-gray-200 text-center">
                        Review Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div
        class="py-20 text-center bg-gray-50/50 rounded-[2.5rem] border-2 border-dashed border-gray-100">
        <div
            class="w-20 h-20 bg-white border border-gray-100 rounded-[2rem] flex items-center justify-center mx-auto mb-4 shadow-sm">
            <i class='bx bx-ghost text-4xl text-gray-200'></i>
        </div>
        <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Log laporan masih
            kosong</p>
    </div>
@endforelse
