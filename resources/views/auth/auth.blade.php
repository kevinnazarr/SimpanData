@extends('layouts.auth')

@section('title', 'SimpanData Login - Register')

@section('content')

    <div class="w-full max-w-md animate-fade-in-up">
        <div id="loginForm" class="p-8 bg-white border-2 border-gray-300 shadow-lg rounded-xl glass shadow-gray-500">

            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center mb-4 rounded-full w-14 h-14 bg-primary-light">
                    <i class="text-xl fas fa-lock text-primary"></i>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-gray-900">Selamat Datang</h1>
                <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if (session('success'))
                <div class="p-4 mb-6 text-green-700 border border-green-200 rounded-lg bg-green-50 animate-fade-in">
                    <div class="flex items-center">
                        <i class="mr-2 fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-6 text-red-700 border border-red-200 rounded-lg bg-red-50 animate-fade-in">
                    <div class="flex items-center mb-2">
                        <i class="mr-2 fas fa-exclamation-circle"></i>
                        <span class="font-medium">Perhatian</span>
                    </div>
                    <ul class="space-y-1 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label for="login" class="block text-sm font-medium text-gray-700">
                        Email atau Username
                    </label>
                    <input type="text" id="login" name="login" value="{{ old('login') }}"
                        placeholder="Email atau Username"
                        class="w-full px-4 py-3 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                        required autocomplete="username">
                </div>

                <div class="space-y-2">
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password" placeholder="••••••••"
                            class="w-full px-4 py-3 pr-10 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                            required autocomplete="current-password">
                        <button type="button" onclick="togglePassword('loginPassword', this)"
                            class="absolute text-gray-400 transition-colors transform -translate-y-1/2 right-3 top-1/2 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div
                                class="w-4 h-4 transition-colors border border-gray-400 rounded peer-checked:bg-primary peer-checked:border-primary">
                            </div>
                            <i
                                class="absolute text-xs text-white transition-opacity transform -translate-x-1/2 -translate-y-1/2 opacity-0 fas fa-check top-1/2 left-1/2 peer-checked:opacity-100"></i>
                        </div>
                        <span class="text-sm text-gray-600">Ingatkan saya</span>
                    </label>

                    <a href="{{ route('forgot.password.form') }}"
                        class="text-sm font-medium transition-colors text-primary hover:text-primary/80">
                        Lupa password?
                    </a>
                </div>

                <button type="submit"
                    class="w-full py-3 font-medium text-white transition-shadow duration-200 rounded-lg shadow-md shadow-gray-600 bg-primary hover:bg-primary/90 active:bg-primary/80 btn-transition focus:outline-none focus:ring-2 focus:ring-primary-light hover:shadow-lg">
                    Masuk ke Sistem
                </button>

                <div class="pt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <button type="button" onclick="showRegister()"
                            class="ml-1 font-medium transition-colors text-primary hover:text-primary/80">
                            Daftar Sekarang
                        </button>
                    </p>
                </div>

                <div class="flex justify-center mb-6">
                    <a href="{{ route('index') }}"
                            class="ml-1 font-medium transition-colors text-primary hover:text-primary/80">
                        Batal
                    </a>
                </div>

            </form>
        </div>

        <div id="registerForm"
            class="hidden p-8 bg-white border-2 border-gray-300 shadow-lg rounded-xl glass shadow-gray-500">
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center mb-4 rounded-full w-14 h-14 bg-primary-light">
                    <i class="text-xl fas fa-user-plus text-primary"></i>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
                <p class="text-gray-600">Bergabung dengan kami dalam beberapa langkah mudah</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div id="step1" class="space-y-5">
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                            placeholder="Buat Username"
                            class="w-full px-4 py-3 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                            required>
                        <div id="usernameStatus" class="mt-1 text-sm min-h-[20px]"></div>
                    </div>

                    <div class="space-y-2">
                        <label for="emailInput" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input type="email" id="emailInput" name="email" value="{{ old('email') }}"
                            placeholder="simpandata@gmail.com"
                            class="w-full px-4 py-3 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                            required>
                        <div id="emailStatus" class="mt-1 text-sm min-h-[20px]"></div>
                    </div>

                    <button type="button" onclick="sendOtp()" id="sendOtpBtn"
                        class="w-full py-3 font-medium text-white transition-shadow duration-200 rounded-lg shadow-sm bg-primary hover:bg-primary/90 active:bg-primary/80 btn-transition focus:outline-none focus:ring-2 focus:ring-primary-light hover:shadow">
                        Kirim Kode Verifikasi
                    </button>
                </div>

                <div id="step2" class="hidden space-y-5">
                    <div class="mb-4 text-center">
                        <h3 class="mb-1 text-lg font-semibold text-gray-900">Verifikasi Email</h3>
                        <p class="text-sm text-gray-600">
                            Kode verifikasi telah dikirim ke
                            <span id="emailDisplay" class="font-medium text-primary"></span>
                        </p>
                    </div>

                    <div>
                        <div class="flex justify-center gap-2 mb-6">
                            <input class="otp-input" maxlength="1">
                            <input class="otp-input" maxlength="1">
                            <input class="otp-input" maxlength="1">
                            <input class="otp-input" maxlength="1">
                            <input class="otp-input" maxlength="1">
                            <input class="otp-input" maxlength="1">
                        </div>

                        <input type="hidden" name="otp" id="otpHidden">

                        <div id="otpStatus" class="text-center text-sm mb-3 min-h-[20px]"></div>

                        <button type="button" onclick="verifyOtpManual()" id="verifyOtpBtn"
                            disabled
                            class="w-full py-3 font-medium text-white transition-shadow duration-200 rounded-lg shadow-sm bg-primary hover:bg-primary/90 active:bg-primary/80 btn-transition focus:outline-none focus:ring-2 focus:ring-primary-light hover:shadow disabled:opacity-50 disabled:cursor-not-allowed">
                            Verifikasi Email
                        </button>

                        <div class="pt-4 text-center">
                            <div class="mb-2">
                                <span id="countdownText" class="text-sm text-gray-600">Kirim ulang OTP dalam 60 detik</span>
                            </div>
                            <button type="button" onclick="resendOtp()" id="resendOtpBtn"
                                disabled
                                class="px-4 py-2 text-sm border rounded-lg text-primary border-primary hover:bg-primary/5 btn-transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Kirim ulang OTP
                            </button>
                        </div>
                    </div>
                </div>

                <div id="step3" class="hidden space-y-5">
                    <div class="space-y-2">
                        <label for="registerPassword" class="block text-sm font-medium text-gray-700">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" id="registerPassword" name="password" placeholder="••••••••"
                                class="w-full px-4 py-3 pr-10 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                                required autocomplete="new-password" oninput="checkRegisterPassword()">
                            <button type="button" onclick="togglePassword('registerPassword', this)"
                                class="absolute text-gray-400 transition-colors transform -translate-y-1/2 right-3 top-1/2 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div id="passwordStrength" class="mt-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-600">Kekuatan password:</span>
                                <span id="strengthText" class="text-xs font-medium">-</span>
                            </div>
                            <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strengthBar" class="w-0 h-full transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="registerPasswordConfirm" class="block text-sm font-medium text-gray-700">
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password" id="registerPasswordConfirm" name="password_confirmation"
                                placeholder="••••••••"
                                class="w-full px-4 py-3 pr-10 transition duration-200 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-light hover:border-gray-400"
                                required autocomplete="new-password" oninput="checkPasswordMatch()">
                            <button type="button" onclick="togglePassword('registerPasswordConfirm', this)"
                                class="absolute text-gray-400 transition-colors transform -translate-y-1/2 right-3 top-1/2 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-1 text-sm min-h-[20px]"></div>
                    </div>

                    <button type="submit" id="registerBtn" disabled
                        class="w-full py-3 font-medium text-white transition-shadow duration-200 rounded-lg shadow-sm bg-primary hover:bg-primary/90 active:bg-primary/80 btn-transition focus:outline-none focus:ring-2 focus:ring-primary-light hover:shadow disabled:opacity-50 disabled:cursor-not-allowed">
                        Buat Akun Sekarang
                    </button>

                </div>

                <div class="pt-4 text-center border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <button type="button" onclick="showLogin()"
                            class="ml-1 font-medium transition-colors text-primary hover:text-primary/80">
                            Login di sini
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.routes = {
            sendOtp: "{{ route('send.otp') }}",
            verifyOtp: "{{ route('verify.otp') }}",
            checkUsername: "{{ route('check.username') }}",
            checkEmailAvailability: "{{ route('check.email.availability') }}"
        };
        window.authConfig = {
            hasRegisterErrors: {{ $errors->has('username') || $errors->has('email') || $errors->has('password') ? 'true' : 'false' }}
        };
    </script>
    @vite(['resources/js/auth.js'])
@endsection
