@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                        Data Peserta
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola data peserta PKL dan Magang
                    </p>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                        bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg
                        hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                    <i class="text-lg bx bx-plus"></i>
                    <span>Tambah Peserta</span>
                </button>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
                <div onclick="filterByTotal()"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-indigo-500 to-purple-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Total Peserta
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalPeserta">
                                    {{ $totalPeserta ?? $peserta->total() }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div onclick="filterByJenis('PKL')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-blue-500 to-indigo-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    PKL
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalPkl">
                                    {{ $totalPkl ?? 0 }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div onclick="filterByJenis('Magang')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-emerald-500 to-teal-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Magang
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalMagang">
                                    {{ $totalMagang ?? 0 }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-briefcase-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div onclick="filterByStatus('Aktif')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-amber-500 to-orange-500 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Aktif
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalAktif">
                                    {{ $aktif ?? 0 }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-time-five"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div onclick="filterByStatus('Selesai')"
                    class="overflow-hidden transition-all duration-300 rounded-lg shadow-sm cursor-pointer bg-gradient-to-br from-green-500 to-emerald-600 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="mb-1 text-xs font-semibold tracking-wider uppercase text-white/80">
                                    Selesai
                                </p>
                                <h3 class="text-3xl font-bold text-white" id="statTotalSelesai">
                                    {{ $selesai ?? 0 }}
                                </h3>
                                <p class="mt-1 text-xs text-white/70">Peserta</p>
                            </div>
                            <div class="ml-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 transition-colors rounded-lg bg-white/20 group-hover:bg-white/30">
                                    <i class="text-2xl text-white bx bx-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 card">
        <div class="p-4 md:p-5">
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1">
                    <div class="relative">
                        <i class='absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2 bx bx-search'></i>
                        <input type="text"
                                id="searchInput"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, sekolah, atau jurusan..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative">
                        <select id="filterJenisKegiatan"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Jenis</option>
                            <option value="PKL" {{ request('jenis_kegiatan') == 'PKL' ? 'selected' : '' }}>PKL</option>
                            <option value="Magang" {{ request('jenis_kegiatan') == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <div class="relative">
                        <select id="filterAsalSekolah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Sekolah/Universitas</option>
                            @foreach ($sekolahs as $item)
                                <option value="{{ $item->asal_sekolah_universitas }}"
                                    {{ request('asal_sekolah_universitas') == $item->asal_sekolah_universitas ? 'selected' : '' }}>
                                    {{ $item->asal_sekolah_universitas }}
                                </option>
                            @endforeach
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <div class="relative">
                        <select id="filterStatus"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white pr-10">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <i class='absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2 bx bx-chevron-down'></i>
                    </div>

                    <button onclick="resetFilters()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 whitespace-nowrap">
                        <i class='bx bx-refresh'></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-4 md:p-5" id="pesertaGridContainer">
            @include('admin.peserta.partials.peserta-grid', ['peserta' => $peserta])
        </div>
    </div>
@endsection

@push('modals')
{{-- createModal: overlay z-30 tidak menutupi navbar (z-40), card z-50 di atas navbar --}}
<div id="createModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75" onclick="closeCreateModal(event)"></div>
<div id="createModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Tambah Peserta Baru</h3>
                    <p class="mt-1 text-gray-600">Masukkan data peserta PKL atau Magang</p>
                </div>
                <button onclick="closeCreateModal(event)"
                        class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <i class='text-2xl bx bx-x'></i>
                </button>
            </div>
            <div class="p-6">
                <div id="createModalContent">
                    <div class="py-12 text-center">
                        <i class="text-4xl text-indigo-600 bx bx-loader-alt bx-spin"></i>
                        <p class="mt-3 text-gray-600">Memuat formulir...</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                <button onclick="closeCreateModal(event)"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
                <button onclick="submitCreateForm()"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                    <i class='bx bx-save'></i>
                    <span>Simpan Peserta</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- editModal: overlay z-30 tidak menutupi navbar (z-40), card z-50 di atas navbar --}}
<div id="editModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75" onclick="closeEditModal(event)"></div>
<div id="editModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Edit Peserta</h3>
                    <p class="mt-1 text-gray-600">Perbarui data peserta</p>
                </div>
                <button onclick="closeEditModal(event)"
                        class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <i class='text-2xl bx bx-x'></i>
                </button>
            </div>
            <div class="p-6">
                <div id="editModalContent">
                    <div class="py-12 text-center">
                        <i class="text-4xl text-indigo-600 bx bx-loader-alt bx-spin"></i>
                        <p class="mt-3 text-gray-600">Memuat formulir...</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                <button onclick="closeEditModal(event)"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
                <button onclick="submitEditForm()"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                    <i class='bx bx-save'></i>
                    <span>Update Peserta</span>
                </button>
            </div>
        </div>
    </div>
</div>


{{-- deleteModal: overlay z-30 tidak menutupi navbar (z-40), card z-50 di atas navbar --}}
<div id="deleteModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75" onclick="closeDeleteModal(event)"></div>
<div id="deleteModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-md my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="p-6 border-b border-gray-100 modal-header">
                <div class="flex items-center justify-center mb-4">
                    <div class="flex items-center justify-center w-16 h-16 border rounded-full bg-gradient-to-br from-red-50 to-red-100/50 border-red-100">
                        <i class='text-2xl text-red-600 bx bx-trash-alt'></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-800">Hapus Peserta</h3>
                <p class="mt-1 text-sm text-center text-gray-600">Konfirmasi penghapusan data peserta</p>
            </div>

            <div class="p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-50">
                        <i class='text-xl text-red-600 bx bx-error'></i>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-gray-800">Apakah Anda yakin?</h4>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Data peserta yang dihapus <span class="font-medium text-red-600">tidak dapat dikembalikan</span>.
                        Semua data terkait akan hilang secara permanen.
                    </p>
                    <div class="p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">ID Peserta:</span>
                            <span class="text-sm font-medium text-gray-800" id="deletePesertaId">-</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-500">Nama:</span>
                            <span class="text-sm font-medium text-gray-800" id="deletePesertaName">-</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-500">Jenis:</span>
                            <span class="text-sm font-medium text-gray-800" id="deletePesertaJenis">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                <button onclick="closeDeleteModal(event)"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class='mr-2 bx bx-x'></i>
                    Batal
                </button>
                <button onclick="confirmDeletePeserta()"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-red-500/20 inline-flex items-center gap-2">
                    <i class='bx bx-trash-alt'></i>
                    <span>Ya, Hapus Data</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="printModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm" onclick="closePrintModal(event)"></div>
<div id="printModal" class="hidden fixed inset-0 z-[40] overflow-y-auto flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl transition-all transform bg-transparent">
        <div class="absolute right-0 z-50 -top-12">
            <button onclick="closePrintModal(event)" 
                    class="flex items-center justify-center w-10 h-10 text-white transition-all bg-white/20 hover:bg-white/30 rounded-full backdrop-blur-md">
                <i class='text-2xl bx bx-x'></i>
            </button>
        </div>
        
        <div class="overflow-hidden bg-white shadow-2xl rounded-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 text-indigo-600 bg-indigo-100 rounded-lg">
                        <i class='text-xl bx bx-id-card'></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Preview ID Card</h4>
                        <p class="text-xs text-gray-500">Tampilan sebelum dicetak</p>
                    </div>
                </div>
                <button onclick="document.getElementById('printFrame').contentWindow.print()" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white transition-all rounded-lg bg-indigo-600 hover:bg-indigo-700 shadow-md active:scale-95">
                    <i class='bx bx-printer'></i>
                    <span>Cetak Sekarang</span>
                </button>
            </div>
            <div class="relative bg-gray-100 aspect-[4/5] sm:aspect-video flex items-center justify-center p-8">
                <div id="printLoader" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-300">
                    <i class="text-4xl text-indigo-600 bx bx-loader-alt bx-spin"></i>
                    <p class="mt-3 text-sm font-medium text-gray-600">Menyiapkan Preview...</p>
                </div>
                <iframe id="printFrame" src="" class="w-full h-full border-none shadow-lg rounded-lg bg-white" onload="document.getElementById('printLoader').classList.add('opacity-0', 'pointer-events-none')"></iframe>
            </div>
        </div>
    </div>
</div>
@endpush

@push('styles')
    @vite('resources/css/admin/peserta.css')
    @vite('resources/css/admin/grid-cards.css')
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.pesertaConfig = {
        indexUrl: '{{ route('admin.peserta.index') }}',
        createUrl: '{{ route('admin.peserta.create') }}',
        storeUrl: '{{ route('admin.peserta.store') }}',
        baseUrl: '{{ url('admin/peserta') }}'
    };
</script>
@vite('resources/js/admin/peserta.js')
@endsection
