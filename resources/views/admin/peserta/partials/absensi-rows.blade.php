@forelse($absensis as $index => $absensi)
    <tr class="hover:bg-gray-50/50 transition-all duration-300">
        <td class="px-6 py-4 text-gray-500 font-bold">{{ $index + $absensis->firstItem() }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xs font-black">
                    {{ $absensi->waktu_absen->format('d') }}
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 leading-none mb-1">
                        {{ $absensi->waktu_absen->format('H:i') }} <span
                            class="text-[8px] text-gray-400 font-bold ml-0.5">WIB</span>
                    </p>
                    <p
                        class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                        {{ $absensi->waktu_absen->format('Y-m-d') }}</p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <span
                class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ $absensi->jenis_absen === 'Masuk' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                {{ $absensi->jenis_absen }}
            </span>
        </td>
        <td class="px-6 py-4">
            @if ($absensi->mode_kerja === 'WFO')
                <span
                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-indigo-700 bg-indigo-100 rounded-lg">WFO</span>
            @elseif($absensi->mode_kerja === 'WFA')
                <span
                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-purple-700 bg-purple-100 rounded-lg">WFA</span>
            @else
                <span
                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-gray-500 bg-gray-100 rounded-lg">-</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <span
                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
        {{ $absensi->status === 'Hadir' ? 'bg-emerald-100 text-emerald-700' : ($absensi->status === 'Izin' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                {{ $absensi->status }}
            </span>
        </td>
        <td class="px-6 py-4 text-gray-600 font-medium">
            <p class="text-xs italic line-clamp-1 max-w-[150px]">
                "{{ $absensi->wa_pengirim ?: '-' }}"</p>
        </td>
        <td class="px-6 py-4 text-center">
            @if ($absensi->latitude && $absensi->longitude)
                <button
                    onclick="showMap('{{ $absensi->latitude }}', '{{ $absensi->longitude }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 hover:border-blue-300 transition-all group">
                    <i class='bx bx-map-pin'></i>
                    <span>Detail</span>
                    <i
                        class='bx bx-chevron-right transition-transform group-hover:translate-x-0.5'></i>
                </button>
            @else
                <span
                    class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">N/A</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-20 text-center">
            <div class="flex flex-col items-center">
                <i class='bx bx-calendar-x text-5xl text-gray-200 mb-2'></i>
                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">
                    Data Absensi Kosong</p>
            </div>
        </td>
    </tr>
@endforelse
