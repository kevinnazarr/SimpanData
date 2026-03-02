@extends('layouts.app')

@section('title', 'Data Partner')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                        Data Partner
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola data universitas dan sekolah partner
                    </p>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                        bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg
                        hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                    <i class="text-lg bx bx-plus"></i>
                    <span>Tambah Partner</span>
                </button>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <div id="partnerGridContainer">
                @include('admin.partners.partials.partner-grid', ['partners' => $partners])
            </div>
        </div>
    </div>
@endsection

@push('modals')
{{-- createModal --}}
<div id="createModalOverlay" class="hidden fixed inset-0 z-30 bg-gray-500/75" onclick="closeCreateModal(event)"></div>
<div id="createModal" class="hidden fixed top-16 inset-x-0 bottom-0 z-[35] overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Tambah Partner Baru</h3>
                    <p class="mt-1 text-gray-600">Masukkan informasi partner institusi</p>
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
        </div>
    </div>
</div>

{{-- deleteModal --}}
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
                <h3 class="text-xl font-bold text-center text-gray-800">Hapus Partner</h3>
                <p class="mt-1 text-sm text-center text-gray-600">Konfirmasi penghapusan data partner</p>
            </div>

            <div class="p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-50">
                        <i class='text-xl text-red-600 bx bx-error'></i>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-gray-800">Apakah Anda yakin?</h4>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Data partner <span class="font-medium text-gray-800" id="deletePartnerName">-</span> akan dihapus.
                        Tindakan ini <span class="font-medium text-red-600">tidak dapat dibatalkan</span>.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 p-6 bg-gray-50 rounded-b-2xl">
                <button onclick="closeDeleteModal(event)"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class='mr-2 bx bx-x'></i>
                    Batal
                </button>
                <button onclick="confirmDeletePartner()"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-red-500/20 inline-flex items-center gap-2">
                    <i class='bx bx-trash-alt'></i>
                    <span>Ya, Hapus Data</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('styles')
    @vite('resources/css/admin/peserta.css')
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.partnerConfig = {
        indexUrl: '{{ route('admin.partners.index') }}',
        createUrl: '{{ route('admin.partners.create') }}',
        storeUrl: '{{ route('admin.partners.store') }}',
        baseUrl: '{{ url('admin/partners') }}'
    };
</script>
@vite('resources/js/admin/partners.js')
@endsection
