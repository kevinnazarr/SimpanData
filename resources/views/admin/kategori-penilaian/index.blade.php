@extends('layouts.app')

@section('title', 'Kategori Penilaian')

@section('content')
<div class="mb-6 card">
    <div class="p-4 border-b border-gray-200 md:p-5">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800 md:text-lg">Kategori Penilaian</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola kategori atau aspek penilaian bagi peserta PKL dan Magang.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.penilaian.index') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700
                        bg-white border border-gray-300 rounded-lg
                        hover:bg-gray-50 transition-all shadow-sm">
                    <i class="text-lg bx bx-arrow-back"></i>
                    <span>Kembali</span>
                </a>
                <button onclick="openCreateModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                        bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg
                        hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                    <i class="text-lg bx bx-plus"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            <span class="font-medium">Sukses!</span> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-4 md:p-5">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="text-gray-600 border-b bg-gray-50/50">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">No</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Nama Kategori</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-[11px]">Deskripsi</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-center text-[11px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($kategoris as $index => $kategori)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ $kategoris->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $kategori->nama }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $kategori->deskripsi ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="openEditModal({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}', '{{ addslashes($kategori->deskripsi) }}')" class="mr-2 text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit Kategori">
                                <i class='text-lg bx bx-edit'></i>
                            </button>
                            <form action="{{ route('admin.kategori_penilaians.destroy', $kategori->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus Kategori">
                                    <i class='text-lg bx bx-trash'></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='text-4xl text-gray-300 bx bx-folder-open mb-2'></i>
                                <p>Belum ada kategori penilaian.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($kategoris->hasPages())
        <div class="pt-4 mt-4 border-t border-gray-100">
            {{ $kategoris->links() }}
        </div>
        @endif
    </div>
</div>

<div id="kategoriModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative w-full max-w-lg overflow-hidden transition-all transform bg-white shadow-2xl rounded-2xl animate-fade-in-up">
            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 text-white shadow-lg bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-indigo-200">
                        <i class='text-xl bx bx-category'></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Form Kategori</h3>
                    </div>
                </div>
                <button type="button" onclick="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-600">
                    <i class='text-2xl bx bx-x'></i>
                </button>
            </div>
            
            <!-- Modal body -->
            <div class="px-8 py-6">
                <form id="kategoriForm" method="POST" action="">
                    @csrf
                    <div id="methodContainer"></div>
                    <div class="space-y-4">
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-semibold text-gray-900">Nama Kategori</label>
                            <input type="text" name="nama" id="nama" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full p-2.5 transition-colors shadow-sm" placeholder="Contoh: Kedisiplinan" required>
                        </div>
                        <div>
                            <label for="deskripsi" class="block mb-2 text-sm font-semibold text-gray-900">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full p-2.5 transition-colors shadow-sm" placeholder="Opsi penjelasan tentang kategori ini..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white shadow-md bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            <i class='bx bx-save'></i>
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const modal = document.getElementById('kategoriModal');
    const form = document.getElementById('kategoriForm');
    const methodContainer = document.getElementById('methodContainer');
    const title = document.getElementById('modalTitle');
    
    function openCreateModal() {
        title.textContent = 'Tambah Kategori Penilaian';
        form.action = "{{ route('admin.kategori_penilaians.store') }}";
        methodContainer.innerHTML = '';
        document.getElementById('nama').value = '';
        document.getElementById('deskripsi').value = '';
        modal.classList.remove('hidden');
    }
    
    function openEditModal(id, nama, deskripsi) {
        title.textContent = 'Edit Kategori Penilaian';
        form.action = `/admin/kategori-penilaian/${id}`;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('nama').value = nama;
        document.getElementById('deskripsi').value = deskripsi !== 'null' ? deskripsi : '';
        modal.classList.remove('hidden');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endsection
