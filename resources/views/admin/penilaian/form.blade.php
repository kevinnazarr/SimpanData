@extends('layouts.app')

@section('title', isset($penilaian) ? 'Edit Penilaian Peserta' : 'Beri Penilaian Peserta')

@section('content')
    <div class="mb-6 card">
        <div class="p-4 border-b border-gray-200 md:p-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.penilaian.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 text-gray-400 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-indigo-600 transition-all">
                    <i class='bx bx-left-arrow-alt text-2xl'></i>
                </a>
                <div>
                    <h2 class="text-base font-semibold text-gray-800 md:text-lg">
                        {{ isset($penilaian) ? 'Edit Penilaian Peserta' : 'Beri Penilaian Peserta' }}
                    </h2>
                    <p class="text-xs text-gray-500 font-medium">Lengkapi aspek kriteria untuk kalkulasi nilai akhir</p>
                </div>
            </div>
            <a href="{{ route('admin.penilaian.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 m-6 text-red-700 bg-red-50 border border-red-100 rounded-xl" role="alert">
                <div class="flex items-center gap-2 mb-2 font-bold">
                    <i class='bx bx-error-circle text-xl'></i>
                    <span>Terdapat kesalahan input:</span>
                </div>
                <ul class="list-disc pl-9 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col lg:flex-row items-stretch gap-6 p-6 rounded-xl bg-gray-50 border border-gray-200 mb-8">
                <div class="flex-1 flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div class="relative flex-shrink-0">
                        @if ($peserta->user->photo_profile)
                            <img src="{{ asset('storage/' . $peserta->user->photo_profile) }}"
                                class="w-32 h-32 md:w-36 md:h-36 rounded-2xl object-cover border-4 border-white shadow-sm">
                        @else
                            <div class="w-32 h-32 md:w-36 md:h-36 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-5xl font-bold text-white shadow-sm border-4 border-white">
                                {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 w-full text-center md:text-left">
                        <div class="mb-4">
                            <div class="flex flex-col md:flex-row items-center gap-2 mb-1 justify-center md:justify-start">
                                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $peserta->nama }}</h1>
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border {{ $peserta->status == 'Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                                    {{ $peserta->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">{{ $peserta->asal_sekolah_universitas }} • {{ $peserta->jurusan }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm shrink-0">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Username</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $peserta->user->username }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm shrink-0">
                                        <i class='bx bx-id-card'></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">NIM / NIS</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $peserta->nim_nis ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm shrink-0">
                                        <i class='bx bx-calendar'></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Periode</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $peserta->tanggal_mulai->format('d M') }} - {{ $peserta->tanggal_selesai->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-indigo-600 shadow-sm shrink-0">
                                        <i class='bx bx-bookmark'></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase leading-none">Program</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $peserta->jenis_kegiatan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-44 flex-shrink-0 flex items-center justify-center lg:border-l lg:border-gray-200 lg:pl-6">
                    <div class="w-full aspect-square p-6 rounded-xl bg-white border border-indigo-100 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3">
                            <i class='bx bxs-star text-xl'></i>
                        </div>
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1 leading-none">Score</p>
                        
                        @if (isset($penilaian))
                            <div class="flex items-end gap-0.5 mb-1.5">
                                <span class="text-4xl font-bold text-indigo-600 leading-none">{{ round($penilaian->nilai_akhir) }}</span>
                                <span class="text-xs font-bold text-indigo-300 mb-0.5">/100</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-600 text-white text-[9px] font-bold uppercase rounded-md">
                                Grade: {{ $penilaian->grade }}
                            </span>
                        @else
                            <h4 class="text-xl font-bold text-gray-400 uppercase tracking-tight mb-1">?</h4>
                            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider leading-none">PENDING</p>
                        @endif
                    </div>
                </div>
            </div>

        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Kriteria Penilaian</h3>
            <div class="flex gap-2">
                @if($kategoris->isEmpty())
                    <form action="{{ route('admin.penilaian.copy_defaults', $peserta->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all shadow-sm">
                            <i class='bx bx-list-check text-base'></i> Gunakan Kriteria Standar
                        </button>
                    </form>
                @endif
                <button type="button" onclick="openKategoriModalCreate()" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm">
                    <i class='bx bx-plus text-base'></i> Tambah Kriteria Kustom
                </button>
            </div>
        </div>

        @if($kategoris->isEmpty())
            <div class="p-8 mb-6 text-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-white rounded-full shadow-sm border border-gray-100">
                    <i class='text-3xl text-gray-400 bx bx-category'></i>
                </div>
                <h4 class="text-gray-900 font-bold mb-1">Belum ada Kriteria Khusus</h4>
                <p class="text-gray-500 text-sm max-w-sm mx-auto mb-6">Silakan tambah kriteria satu per satu sesuai perkembangan peserta ini, atau gunakan kriteria standar perusahaan.</p>
                <div class="flex justify-center gap-3">
                    <form action="{{ route('admin.penilaian.copy_defaults', $peserta->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-all shadow-sm">
                            Gunakan Kriteria Standar
                        </button>
                    </form>
                    <button type="button" onclick="openKategoriModalCreate()" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md">
                        Tambah Satu Per Satu
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ isset($penilaian) ? route('admin.penilaian.update', $penilaian->id) : route('admin.penilaian.store') }}" method="POST">
            @csrf
            @if(isset($penilaian)) @method('PUT') @endif

            <input type="hidden" name="peserta_id" value="{{ $peserta->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @foreach($kategoris as $kategori)
                    @php
                        $nilaiLama = 75;
                        if(isset($penilaian) && $penilaian->details) {
                            $detail = $penilaian->details->where('kategori_penilaian_id', $kategori->id)->first();
                            if($detail) $nilaiLama = $detail->nilai;
                        }

                        $oldValue = old('kategori.'.$kategori->id, $nilaiLama);
                    @endphp
                    <div class="p-4 border border-gray-200 rounded-xl bg-white shadow-sm hover:shadow-md transition-shadow relative">

                        <div class="absolute top-2 right-2 flex gap-1 z-10 transition-colors">
                            @if(!$kategori->trashed())
                                <button type="button" onclick="openKategoriModalEdit({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}', '{{ addslashes($kategori->deskripsi) }}')" class="flex items-center justify-center w-7 h-7 text-gray-400 hover:text-indigo-600 bg-white hover:bg-indigo-50 rounded shadow-sm border border-gray-100 transition-colors" title="Edit Kriteria">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button type="button" onclick="deleteKategori({{ $kategori->id }})" class="flex items-center justify-center w-7 h-7 text-gray-400 hover:text-red-600 bg-white hover:bg-red-50 rounded shadow-sm border border-gray-100 transition-colors" title="Hapus Kriteria">
                                    <i class='bx bx-trash'></i>
                                </button>
                            @endif
                        </div>

                        <div class="flex justify-between items-start mb-2 pr-16 relative">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $kategori->nama }}</h4>
                                @if($kategori->deskripsi)
                                    <p class="text-xs text-gray-500">{{ $kategori->deskripsi }}</p>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-sm font-bold text-indigo-700 bg-indigo-100 rounded" id="label-{{ $kategori->id }}">
                                {{ $oldValue }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <input type="range"
                                name="kategori[{{ $kategori->id }}]"
                                id="range-{{ $kategori->id }}"
                                min="0" max="100"
                                value="{{ $oldValue }}"
                                oninput="updateRangeLabel({{ $kategori->id }})"
                                class="custom-slider w-full h-2 rounded-lg appearance-none cursor-pointer">

                            <div class="flex justify-between text-xs text-gray-400 font-medium px-1 mt-1">
                                <span>0</span>
                                <span>25</span>
                                <span>50</span>
                                <span>75</span>
                                <span>100</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-6">
                <label for="catatan" class="block mb-2 text-sm font-medium text-gray-900">Catatan Penilaian (Opsional)</label>
                <textarea name="catatan" id="catatan" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Tuliskan catatan atau masukan untuk peserta...">{{ old('catatan', $penilaian->catatan ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.penilaian.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">
                    Selesai & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    input[type="range"].custom-slider {
        appearance: none;
        width: 100%;
        height: 10px;
        border-radius: 10px;
        background: #e2e8f0;
        outline: none;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    input[type="range"].custom-slider::-webkit-slider-thumb {
        appearance: none;
        width: 26px;
        height: 26px;
        background: #4f46e5;
        border: 4px solid #ffffff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4), 0 2px 4px -2px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
        margin-top: 0px; 
    }

    input[type="range"].custom-slider:active::-webkit-slider-thumb {
        transform: scale(1.15);
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0.15);
    }

    input[type="range"].custom-slider::-moz-range-thumb {
        width: 22px;
        height: 22px;
        background: #4f46e5;
        border: 4px solid #ffffff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);
        transition: all 0.2s ease;
    }

    input[type="range"].custom-slider:active::-moz-range-thumb {
        transform: scale(1.15);
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0.1);
    }
</style>

<form id="deleteKategoriForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<div id="kategoriModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm transition-opacity">
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl">
        <div class="flex items-start justify-between p-4 border-b border-gray-100 rounded-t">
            <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Form Kategori</h3>
            <button type="button" onclick="closeKategoriModal()" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center transition-colors">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="kategoriForm" method="POST" action="">
                @csrf
                <input type="hidden" name="peserta_id" value="{{ $peserta->id }}">
                <div id="methodContainer"></div>
                <div class="mb-5">
                    <label for="kategori_nama" class="block mb-2 text-sm font-semibold text-gray-900">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="kategori_nama" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full p-2.5 transition-colors" placeholder="Cth: Kedisiplinan" required>
                </div>
                <div class="mb-5">
                    <label for="kategori_deskripsi" class="block mb-2 text-sm font-semibold text-gray-900">Deskripsi Singkat <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" id="kategori_deskripsi" rows="3" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full p-2.5 transition-colors" placeholder="Pendeklarasian tentang aspek penilaian tersebut..."></textarea>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100 gap-3">
                    <button type="button" onclick="closeKategoriModal()" class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:outline-none focus:ring-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:ring-2 focus:outline-none focus:ring-indigo-500 transition-colors shadow-sm">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function updateRangeLabel(id) {
        const slider = document.getElementById('range-' + id);
        const val = slider.value;
        document.getElementById('label-' + id).textContent = val;
        
        const percentage = (val - slider.min) / (slider.max - slider.min) * 100;
        slider.style.background = `linear-gradient(to right, #4f46e5 ${percentage}%, #e2e8f0 ${percentage}%)`;
    }
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type="range"].custom-slider').forEach(slider => {
            const id = slider.id.replace('range-', '');
            updateRangeLabel(id);
        });
    });

    const kategoriModal = document.getElementById('kategoriModal');
    const kategoriForm = document.getElementById('kategoriForm');
    const kategoriMethodContainer = document.getElementById('methodContainer');
    const kategoriTitle = document.getElementById('modalTitle');

    function openKategoriModalCreate() {
        kategoriTitle.textContent = 'Tambah Kriteria Penilaian';
        kategoriForm.action = "{{ route('admin.kategori_penilaians.store') }}";
        kategoriMethodContainer.innerHTML = '';
        document.getElementById('kategori_nama').value = '';
        document.getElementById('kategori_deskripsi').value = '';
        kategoriModal.classList.remove('hidden');
        kategoriModal.classList.add('flex');
    }

    function openKategoriModalEdit(id, nama, deskripsi) {
        kategoriTitle.textContent = 'Edit Kriteria Penilaian';
        kategoriForm.action = `/admin/kategori-penilaian/${id}`;
        kategoriMethodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('kategori_nama').value = nama;
        document.getElementById('kategori_deskripsi').value = deskripsi && deskripsi !== 'null' ? deskripsi : '';
        kategoriModal.classList.remove('hidden');
        kategoriModal.classList.add('flex');
    }

    function closeKategoriModal() {
        kategoriModal.classList.add('hidden');
        kategoriModal.classList.remove('flex');
    }

    function deleteKategori(id) {
        if(confirm('Apakah Anda yakin ingin menghapus kriteria penilaian ini?')) {
            const form = document.getElementById('deleteKategoriForm');
            form.action = `/admin/kategori-penilaian/${id}`;
            form.submit();
        }
    }
</script>
@endsection
