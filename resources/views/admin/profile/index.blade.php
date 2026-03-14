@extends('layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="px-6 py-6 mx-auto max-w-7xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profil Saya</h1>
        <p class="text-sm font-medium text-slate-600">Kelola informasi profil akun administrator Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="p-6 bg-white border border-gray-100 shadow-soft rounded-2xl">
                <div class="flex flex-col items-center text-center">
                    <div class="relative group mb-4">
                        <div class="w-24 h-24 overflow-hidden rounded-full shadow-lg bg-gradient-to-br from-primary to-blue-600 ring-4 ring-blue-50 flex items-center justify-center">
                            @if($user->photo_profile)
                                <img src="{{ asset('storage/' . $user->photo_profile) }}" class="object-cover w-full h-full">
                            @else
                                <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                            @endif
                        </div>
                        <label for="foto" class="absolute bottom-0 right-0 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-primary shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                            <i class='bx bx-camera'></i>
                        </label>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $user->username }}</h2>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-4">{{ $user->role }}</p>
                    <div class="w-full pt-4 border-t border-gray-50 text-left">
                        <div class="flex items-center gap-3 mb-3 text-sm">
                            <i class='bx bx-envelope text-lg text-primary'></i>
                            <span class="text-slate-600">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="p-8 bg-white border border-gray-100 shadow-soft rounded-2xl">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="foto" id="foto" class="hidden" onchange="this.form.submit()">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="username" class="text-sm font-bold text-slate-700">Username</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors group-focus-within:text-primary text-slate-400">
                                    <i class='text-xl bx bx-user'></i>
                                </div>
                                <input type="text" name="username" id="username" 
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-700 font-medium" 
                                    value="{{ old('username', $user->username) }}" required>
                            </div>
                            @error('username')
                                <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-bold text-slate-700">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors group-focus-within:text-primary text-slate-400">
                                    <i class='text-xl bx bx-envelope'></i>
                                </div>
                                <input type="email" name="email" id="email" 
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-700 font-medium" 
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')
                                <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-primary rounded-xl hover:bg-primary-dark focus:ring-4 focus:ring-primary/20 transition-all shadow-lg shadow-primary/20">
                            <i class='mr-2 text-lg bx bx-save'></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
