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
                        <h4 class="text-lg font-bold uppercase text-slate-800">Riwayat Pesan</h4>
                    </div>

                    <div class="space-y-4">
                        @foreach($feedbacks as $index => $fb)
                            @if($fb->pengirim === 'Peserta')
                                <div class="flex justify-end animate-fade-in" style="animation-delay: {{ ($index * 50) + 100 }}ms">
                                    <div class="max-w-[80%] md:max-w-[65%]">
                                        <div class="p-4 text-white shadow-md bg-primary rounded-2xl rounded-br-md">
                                            <p class="text-sm leading-relaxed">{{ $fb->pesan }}</p>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 mt-1.5 mr-1">
                                            <span class="text-[10px] font-medium text-slate-400">
                                                {{ $fb->created_at->format('d M Y, H:i') }}
                                            </span>
                                            @if($fb->dibaca)
                                                <i class='text-xs text-blue-500 bx bx-check-double' title="Dibaca"></i>
                                            @else
                                                <i class='text-xs bx bx-check text-slate-400' title="Terkirim"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-start animate-fade-in" style="animation-delay: {{ ($index * 50) + 100 }}ms">
                                    <div class="max-w-[80%] md:max-w-[65%]">
                                        <div class="flex items-center gap-2 mb-1.5 ml-1">
                                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-indigo-600 bg-indigo-100 rounded-full">
                                                <i class='text-sm bx bx-shield-quarter'></i>
                                            </div>
                                            <span class="text-xs font-bold text-slate-500">Admin</span>
                                        </div>
                                        <div class="p-4 border shadow-sm bg-slate-50 rounded-2xl rounded-bl-md border-slate-100">
                                            <p class="text-sm leading-relaxed text-slate-700">{{ $fb->pesan }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1.5 ml-1">
                                            <span class="text-[10px] font-medium text-slate-400">
                                                {{ $fb->created_at->format('d M Y, H:i') }}
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
@endsection

@section('scripts')
    @vite('resources/js/peserta/feedback.js')
@endsection
