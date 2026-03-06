@extends('layouts.app')

@section('title', 'Feedback')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 p-6 md:flex-row md:items-center card shadow-soft animate-fade-in">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 text-2xl text-teal-600 rounded-xl bg-teal-50">
                    <i class='bx bx-message-square-detail'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Feedback</h1>
                    <p class="text-sm font-medium text-slate-500">Mohon berikan feedback dengan bahasa yang sopan</p>
                </div>
            </div>

            @if($feedbacks->count() > 0)
                <div class="px-4 py-2 border border-teal-100 bg-teal-50 rounded-xl animate-fade-in" style="animation-delay: 200ms">
                    <p class="text-xs font-bold text-teal-600 uppercase">Total Pesan</p>
                    <p class="text-sm font-extrabold text-teal-900">{{ $feedbacks->count() }} pesan</p>
                </div>
            @endif
        </div>

        @if($peserta)
            <div class="p-6 card shadow-soft animate-fade-in-up" style="animation-delay: 100ms">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-10 h-10 text-xl rounded-lg text-primary bg-primary/5">
                        <i class='bx bx-edit'></i>
                    </div>
                    <h4 class="text-lg font-bold uppercase text-slate-800">Kirim Pesan</h4>
                </div>

                <form action="{{ route('peserta.feedback.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700">Rating (Opsional)</label>
                            <div class="flex flex-row-reverse justify-end gap-1 group/rating">
                                <input type="radio" name="rating" id="star5" value="5" class="hidden peer/5">
                                <label for="star5" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/5:text-yellow-400 group-hover/rating:text-gray-300 hover:!text-yellow-400 peer-hover/5:!text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="star4" value="4" class="hidden peer/4">
                                <label for="star4" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/4:text-yellow-400 group-hover/rating:text-gray-300 hover:!text-yellow-400 peer-hover/4:!text-yellow-400 peer-checked/5:text-yellow-400 hover:peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="star3" value="3" class="hidden peer/3">
                                <label for="star3" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/3:text-yellow-400 group-hover/rating:text-gray-300 hover:!text-yellow-400 peer-hover/3:!text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="star2" value="2" class="hidden peer/2">
                                <label for="star2" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/2:text-yellow-400 group-hover/rating:text-gray-300 hover:!text-yellow-400 peer-hover/2:!text-yellow-400 peer-checked/3:text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="star1" value="1" class="hidden peer/1">
                                <label for="star1" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/1:text-yellow-400 group-hover/rating:text-gray-300 hover:!text-yellow-400 peer-hover/1:!text-yellow-400 peer-checked/2:text-yellow-400 peer-checked/3:text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>
                            </div>
                            @push('styles')
                                @vite('resources/css/peserta/feedback.css')
                            @endpush
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700">Pesan</label>
                            <textarea
                                name="pesan"
                                id="pesanFeedback"
                                rows="4"
                                maxlength="1000"
                                class="w-full px-4 py-3 text-sm font-medium transition-all duration-200 border resize-none rounded-xl text-slate-700 border-slate-200 bg-slate-50/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white placeholder-slate-400"
                                placeholder="Tulis pesan feedback Anda di sini..."
                                required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-slate-400">Maksimal 1000 karakter</p>
                                <p class="text-xs font-medium text-slate-400">
                                    <span id="charCount">0</span>/1000
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                                <i class='text-lg bx bx-send'></i>
                                Kirim Feedback
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if($feedbacks->count() > 0)
                <div class="p-6 card shadow-soft animate-fade-in-up" style="animation-delay: 200ms">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 text-xl text-indigo-600 rounded-lg bg-indigo-50">
                            <i class='bx bx-conversation'></i>
                        </div>
                        <h4 class="text-lg font-bold uppercase text-slate-800">Riwayat Feedback</h4>
                    </div>

                    <div class="space-y-6">
                        @foreach($feedbacks as $index => $fb)
                            @if($fb->pengirim === 'Peserta')
                                <div class="animate-fade-in group/item" id="feedback-{{ $fb->id }}" style="animation-delay: {{ ($index * 50) + 100 }}ms">
                                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 transition-all duration-300 relative">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shadow-sm">
                                                    <i class='bx bx-user text-xl'></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900 leading-none mb-1">{{ $peserta->nama }}</h4>
                                                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">
                                                        {{ $fb->created_at->format('d M Y • H:i') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                                @if ($fb->rating)
                                                    <div class="flex items-center gap-0.5 bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-100">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class='bx {{ $i <= $fb->rating ? 'bxs-star text-yellow-400' : 'bx-star text-yellow-200' }} text-[10px]'></i>
                                                        @endfor
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-2">
                                                    <button onclick="editFeedback({{ $fb->id }}, @js($fb->pesan), {{ $fb->rating ?? 0 }})" 
                                                        class="w-8 h-8 flex items-center justify-center text-blue-500 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-500 hover:text-white transition-all"
                                                        title="Edit Feedback">
                                                        <i class='bx bx-edit-alt text-lg'></i>
                                                    </button>
                                                    <button type="button" onclick="confirmDeleteFeedback({{ $fb->id }})" 
                                                        class="w-8 h-8 flex items-center justify-center text-red-500 bg-red-50 border border-red-100 rounded-lg hover:bg-red-500 hover:text-white transition-all"
                                                        title="Hapus Feedback">
                                                        <i class='bx bx-trash text-lg'></i>
                                                    </button>
                                                    <form id="delete-form-{{ $fb->id }}" action="{{ route('peserta.feedback.destroy', $fb->id) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative pl-4">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary/20 rounded-full"></div>
                                            <p class="text-sm text-slate-600 leading-relaxed">{{ $fb->pesan }}</p>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-[10px] font-medium text-slate-400">
                                                {{ $fb->created_at->diffForHumans() }}
                                            </span>
                                            @if($fb->dibaca)
                                                <span class="text-[10px] font-bold text-blue-500 flex items-center gap-1">
                                                    <i class='bx bx-check-double'></i> Dibaca oleh Admin
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                                    <i class='bx bx-check text-[14px]'></i> Terkirim
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="animate-fade-in" id="feedback-{{ $fb->id }}" style="animation-delay: {{ ($index * 50) + 100 }}ms">
                                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-5 shadow-sm relative">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-sm">
                                                <i class='bx bx-shield-quarter text-xl'></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-indigo-900 leading-none mb-1">Admin</h4>
                                                <p class="text-[10px] text-indigo-400 font-medium uppercase tracking-widest">
                                                    {{ $fb->created_at->format('d M Y • H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="relative pl-4 border-l-4 border-indigo-200">
                                            <p class="text-sm text-indigo-900/80 leading-relaxed font-bold italic">"{{ $fb->pesan }}"</p>
                                        </div>
                                        <div class="mt-3">
                                            <span class="text-[10px] font-medium text-indigo-400">
                                                {{ $fb->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 card shadow-soft animate-fade-in" style="animation-delay: 200ms">
                    <div class="flex items-center justify-center w-48 h-48 mb-6 rounded-full bg-slate-50">
                        <i class='bx bx-message-square-dots text-8xl text-slate-200'></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-slate-800">Belum Ada Feedback</h3>
                    <p class="max-w-md text-center text-slate-500">
                        Belum ada pesan feedback. Kirim pesan pertama Anda kepada pembimbing melalui form di atas!
                    </p>
                </div>
            @endif
        @else
            <div class="flex flex-col items-center justify-center py-20 card shadow-soft animate-fade-in">
                <div class="flex items-center justify-center w-48 h-48 mb-6 rounded-full bg-red-50">
                    <i class='text-red-200 bx bxs-error-circle text-8xl'></i>
                </div>
                <h3 class="mb-2 text-xl font-bold text-slate-800">Profil Belum Lengkap</h3>
                <p class="max-w-md mb-6 text-center text-slate-500">
                    Lengkapi data diri Anda terlebih dahulu sebelum menggunakan fitur feedback.
                </p>
                <a href="{{ route('peserta.profil') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl bg-red-500 hover:bg-red-600 active:scale-95">
                    <i class='text-lg bx bx-user'></i>
                    Lengkapi Profil
                </a>
            </div>
        @endif
    </div>

    <!-- Edit Feedback Modal -->
    <div id="editFeedbackModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
            
            <div class="relative w-full max-w-lg overflow-hidden bg-white shadow-2xl rounded-3xl animate-fade-in-up">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 rounded-lg bg-blue-50">
                            <i class='bx bx-edit-alt'></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Edit Feedback</h3>
                    </div>
                    <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class='text-2xl bx bx-x'></i>
                    </button>
                </div>

                <form id="editFeedbackForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700">Rating (Opsional)</label>
                            <div class="flex flex-row-reverse justify-end gap-1 group/edit-rating">
                                <input type="radio" name="rating" id="edit-star5" value="5" class="hidden peer/5">
                                <label for="edit-star5" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/5:text-yellow-400 group-hover/edit-rating:text-gray-300 hover:!text-yellow-400 peer-hover/5:!text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="edit-star4" value="4" class="hidden peer/4">
                                <label for="edit-star4" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/4:text-yellow-400 group-hover/edit-rating:text-gray-300 hover:!text-yellow-400 peer-hover/4:!text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="edit-star3" value="3" class="hidden peer/3">
                                <label for="edit-star3" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/3:text-yellow-400 group-hover/edit-rating:text-gray-300 hover:!text-yellow-400 peer-hover/3:!text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="edit-star2" value="2" class="hidden peer/2">
                                <label for="edit-star2" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/2:text-yellow-400 group-hover/edit-rating:text-gray-300 hover:!text-yellow-400 peer-hover/2:!text-yellow-400 peer-checked/3:text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>

                                <input type="radio" name="rating" id="edit-star1" value="1" class="hidden peer/1">
                                <label for="edit-star1" class="text-2xl text-gray-300 cursor-pointer transition-colors peer-checked/1:text-yellow-400 group-hover/edit-rating:text-gray-300 hover:!text-yellow-400 peer-hover/1:!text-yellow-400 peer-checked/2:text-yellow-400 peer-checked/3:text-yellow-400 peer-checked/4:text-yellow-400 peer-checked/5:text-yellow-400">
                                    <i class='bx bxs-star'></i>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700">Pesan</label>
                            <textarea
                                name="pesan"
                                id="editPesanFeedback"
                                rows="4"
                                maxlength="1000"
                                class="w-full px-4 py-3 text-sm font-medium transition-all duration-200 border resize-none rounded-xl text-slate-700 border-slate-200 bg-slate-50/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white placeholder-slate-400"
                                required></textarea>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-slate-400">Maksimal 1000 karakter</p>
                                <p class="text-xs font-medium text-slate-400">
                                    <span id="editCharCount">0</span>/1000
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-100 bg-slate-50/50">
                        <button type="button" onclick="closeEditModal()"
                            class="px-5 py-2.5 text-sm font-bold text-slate-500 transition-all duration-200 rounded-xl hover:bg-slate-100 active:scale-95">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="deleteFeedbackModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            
            <div class="relative w-full max-w-sm overflow-hidden bg-white shadow-2xl rounded-3xl animate-fade-in-up">
                <div class="p-8 text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 bg-red-50 text-red-500 rounded-full">
                        <i class='bx bx-error-circle text-5xl animate-bounce'></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-slate-800">Hapus Feedback?</h3>
                    <p class="mb-8 text-sm text-slate-500">
                        Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus pesan feedback ini?
                    </p>
                    
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-6 py-3 text-sm font-bold text-slate-500 transition-all duration-200 rounded-2xl hover:bg-slate-100 active:scale-95">
                            Batal
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="flex-1 px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-red-500 rounded-2xl hover:bg-red-600 hover:shadow-lg hover:shadow-red-500/20 active:scale-95">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/peserta/feedback.js')
@endsection
