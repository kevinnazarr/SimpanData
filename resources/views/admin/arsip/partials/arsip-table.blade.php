<div class="overflow-x-auto" id="arsipTableWrapper">
    @if($pesertaArsip->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="flex items-center justify-center w-20 h-20 mb-4 rounded-full bg-slate-100">
                <i class='text-4xl text-slate-400 bx bx-archive'></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Arsip Kosong</h3>
            <p class="text-sm text-gray-500">Tidak ada data arsip yang cocok dengan pencarian.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Peserta</th>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Jenis</th>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Tgl. Selesai</th>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Diarsipkan</th>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Sisa Waktu</th>
                    <th class="px-5 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pesertaArsip as $peserta)
                    @php
                        $sisa = $peserta->sisa_hari;
                        if ($sisa === null) {
                            $badgeClass = 'bg-gray-100 text-gray-600';
                            $badgeText  = 'Tidak diketahui';
                        } elseif ($sisa <= 7) {
                            $badgeClass = 'bg-red-100 text-red-700';
                            $badgeText  = $sisa === 0 ? 'Akan segera dihapus' : "{$sisa} hari lagi";
                        } elseif ($sisa <= 14) {
                            $badgeClass = 'bg-amber-100 text-amber-700';
                            $badgeText  = "{$sisa} hari lagi";
                        } else {
                            $badgeClass = 'bg-green-100 text-green-700';
                            $badgeText  = "{$sisa} hari lagi";
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center flex-shrink-0 w-9 h-9 font-bold text-white rounded-full bg-gradient-to-br from-slate-500 to-slate-600 text-xs">
                                    {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $peserta->nama }}</p>
                                    <p class="text-xs text-gray-400">{{ $peserta->asal_sekolah_universitas }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $peserta->jenis_kegiatan === 'PKL' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $peserta->jenis_kegiatan }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $peserta->arsip ? \Carbon\Carbon::parse($peserta->arsip->diarsipkan_pada)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                <i class='bx bx-time-five'></i>
                                {{ $badgeText }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    data-id="{{ $peserta->id }}"
                                    data-nama="{{ $peserta->nama }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-200 btn-pulihkan"
                                    title="Pulihkan ke status Selesai">
                                    <i class='bx bx-undo'></i> Pulihkan
                                </button>
                                <button
                                    type="button"
                                    data-id="{{ $peserta->id }}"
                                    data-nama="{{ $peserta->nama }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors border border-red-200 btn-hapus"
                                    title="Hapus permanen sekarang">
                                    <i class='bx bx-trash'></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($pesertaArsip->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $pesertaArsip->links() }}
            </div>
        @endif
    @endif
</div>
