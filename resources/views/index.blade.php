<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpanData - Sistem Pengelolaan PKL & Magang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo/logo_simpandata.webp') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#1e40af',
                        secondary: '#64748b',
                        background: '#ffffff',
                        surface: '#f8fafc',
                        'text-primary': '#1e293b',
                        'text-secondary': '#64748b',
                        border: '#e2e8f0',
                    },
                    boxShadow: {
                        custom: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
                        'custom-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
                    },

                    animation: {
                        'scroll-left': 'scroll-left 80s linear infinite',
                        'scroll-right': 'scroll-right 80s linear infinite',
                        'blob': 'blob 7s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },

                    keyframes: {
                        'scroll-left': {
                            '0%': {
                                transform: 'translateX(0)'
                            },
                            '100%': {
                                transform: 'translateX(-50%)'
                            },
                        },

                        'scroll-right': {
                            '0%': {
                                transform: 'translateX(-50%)'
                            },
                            '100%': {
                                transform: 'translateX(0)'
                            },
                        },

                        blob: {
                            '0%, 100%': {
                                transform: 'translate(0,0) scale(1)'
                            },
                            '33%': {
                                transform: 'translate(30px,-50px) scale(1.1)'
                            },
                            '66%': {
                                transform: 'translate(-20px,20px) scale(0.9)'
                            },
                        },

                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                    },
                },
            },
        }
    </script>


    @vite(['resources/css/landing.css'])
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            animation: marquee 80s linear infinite;
        }
    </style>
</head>

<body class="font-inter">
    <nav id="navbar"
        class="fixed top-0 left-0 right-0 z-50 py-4 transition-all duration-300 bg-white/70 backdrop-blur-sm">
        <div class="flex items-center justify-between px-6 mx-auto max-w-7xl">
            <a href="#home" class="flex items-center gap-3 no-underline">
                <img src="{{ asset('storage/logo/logo_simpandata.webp') }}" alt="SimpanData Logo"
                    class="object-contain w-10 h-10 border-2 rounded-lg border-primary">
                <span class="text-xl font-extrabold text-text-primary">SimpanData</span>
            </a>

            <ul class="hidden gap-8 list-none md:flex">
                <li><a href="#home"
                        class="no-underline text-text-secondary font-medium transition-colors duration-300 relative hover:text-primary after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">Beranda</a>
                </li>
                <li><a href="#features"
                        class="no-underline text-text-secondary font-medium transition-colors duration-300 relative hover:text-primary after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">Fitur</a>
                </li>
                <li><a href="#partners"
                        class="no-underline text-text-secondary font-medium transition-colors duration-300 relative hover:text-primary after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">Partner</a>
                </li>
                <li><a href="#feedback"
                        class="no-underline text-text-secondary font-medium transition-colors duration-300 relative hover:text-primary after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all after:duration-300 hover:after:w-full">Testimoni</a>
                </li>
            </ul>

            <div class="items-center hidden gap-4 md:flex">
                <a href="{{ route('auth') }}"
                    class="px-5 py-2 rounded-lg font-medium no-underline transition-all duration-300 text-white bg-gradient-to-br from-primary to-primary-dark shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/40 text-sm">Masuk</a>
            </div>

            <button id="mobileMenuBtn"
                class="text-2xl bg-transparent border-none cursor-pointer md:hidden text-text-primary">
                <i class='bx bx-menu'></i>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu">
            <ul class="p-0 list-none">
                <li class="mb-2"><a href="#home" class="block p-3 no-underline text-text-primary">Beranda</a></li>
                <li class="mb-2"><a href="#features" class="block p-3 no-underline text-text-primary">Fitur</a></li>
                <li class="mb-2"><a href="#partners" class="block p-3 no-underline text-text-primary">Partner</a>
                </li>
                <li class="mb-2"><a href="#feedback" class="block p-3 no-underline text-text-primary">Testimoni</a>
                </li>
            </ul>
            <div class="flex flex-col gap-3 pt-4 mt-4 border-t border-border">
                <a href="{{ route('auth') }}"
                    class="px-5 py-2 font-medium text-center no-underline transition-all duration-300 bg-transparent border rounded-lg border-border text-text-primary hover:bg-surface hover:border-primary hover:text-primary">Masuk</a>
                <a href="{{ route('auth') }}"
                    class="px-5 py-2 rounded-lg font-medium no-underline transition-all duration-300 text-white bg-gradient-to-br from-primary to-primary-dark shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/40 text-center">Daftar
                    Sekarang</a>
            </div>
        </div>
    </nav>

    <div class="scroll-progress" id="scrollProgress"></div>

    <section id="home" class="relative flex items-center justify-center min-h-screen pt-20 overflow-hidden">
        <div class="hero-bg" id="heroBg"></div>
        <div class="hero-overlay"></div>

        <div class="relative z-10 w-full max-w-4xl px-6 mx-auto">
            <div class="flex flex-col items-center text-center hero-content">
                <h1
                    class="mb-6 text-5xl font-extrabold leading-tight reveal-scale md:text-6xl lg:text-7xl text-text-primary">
                    Kelola PKL & Magang
                    <span class="block mt-2 gradient-text">Jadi Lebih Mudah</span>
                </h1>

                <p class="max-w-3xl mb-8 text-lg leading-relaxed reveal-scale md:text-xl text-text-secondary">
                    SimpanData menghilangkan cara kerja manual yang membuat data peserta tercecer.
                    Semua informasi penting dikumpulkan dalam satu sistem yang konsisten, terstruktur,
                    dan dapat diandalkan.
                </p>

                <div class="flex flex-col gap-4 mb-12 reveal-scale sm:flex-row">
                    <a href="{{ route('auth') }}"
                        class="flex items-center justify-center px-8 py-4 text-base font-semibold text-white no-underline transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-br from-primary to-primary-dark shadow-blue-500/30 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/50 group">
                        Mulai Sekarang
                        <i class='ml-2 transition-transform bx bx-right-arrow-alt group-hover:translate-x-1'></i>
                    </a>
                    <a href="#features"
                        class="px-8 py-4 text-base font-semibold no-underline transition-all duration-300 border-2 rounded-lg bg-white/80 backdrop-blur-sm border-primary/30 text-primary hover:bg-primary hover:text-white hover:border-primary hover:shadow-lg hover:shadow-primary/30">Pelajari
                        Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <div class="py-10 bg-white border-b border-gray-100 marquee-section">
        <div class="mb-8 text-center marquee-header">
            <p class="text-sm font-bold tracking-wider text-gray-500 uppercase">DiDukung oleh</p>
        </div>
        <div class="relative flex overflow-hidden group">
            <div class="flex min-w-full gap-16 py-4 pr-16 animate-scroll-left whitespace-nowrap shrink-0">
                @for ($i = 0; $i < 10; $i++)
                    <div
                        class="flex items-center justify-center w-64 h-32 transition-all duration-300 transform opacity-100 cursor-pointer hover:scale-110 hover:drop-shadow-lg">
                        <img src="{{ asset('storage/logo/Logo_GI.png') }}" alt="Logo Partner"
                            class="object-contain w-full h-full">
                    </div>
                @endfor
            </div>
            <div class="flex min-w-full gap-16 py-4 pr-16 animate-scroll-left whitespace-nowrap shrink-0"
                aria-hidden="true">
                @for ($i = 0; $i < 10; $i++)
                    <div
                        class="flex items-center justify-center w-64 h-32 transition-all duration-300 transform opacity-100 cursor-pointer hover:scale-110 hover:drop-shadow-lg">
                        <img src="{{ asset('storage/logo/Logo_GI.png') }}" alt="Logo Partner"
                            class="object-contain w-full h-full">
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <section id="features" class="py-20 bg-white">
        <div class="px-6 mx-auto max-w-7xl">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 text-sm font-medium bg-blue-100 rounded-full reveal text-primary">
                    <i class='bx bx-bolt'></i>
                    Fitur Unggulan
                </span>
                <h2 class="mb-4 text-4xl font-bold reveal text-text-primary">
                    Mengapa Memilih <span class="gradient-text">SimpanData</span>?
                </h2>
                <p class="max-w-2xl mx-auto text-lg reveal text-text-secondary">
                    SimpanData dirancang untuk menghilangkan cara kerja manual yang selama ini membuat
                    data peserta tercecer, status kegiatan tidak jelas, dan proses administrasi makan waktu.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mb-16 md:grid-cols-2 lg:grid-cols-3">
                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl transition-transform duration-300 bg-blue-100 w-14 h-14 rounded-xl text-primary group-hover:scale-110">
                        <i class='bx bx-data'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Sistem Pusat Terintegrasi</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Semua informasi penting peserta dikumpulkan dalam satu sistem yang konsisten.
                        Tidak ada lagi kebingungan soal data mana yang benar. Data terstruktur, rapi,
                        dan selalu dapat diakses kapan saja.
                    </p>
                </div>

                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl text-green-500 transition-transform duration-300 bg-green-100 w-14 h-14 rounded-xl group-hover:scale-110">
                        <i class='bx bx-shield-alt'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Kontrol Penuh untuk Admin</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Admin tidak perlu lagi mengecek absensi satu per satu atau menanyakan laporan lewat chat.
                        Sistem otomatis mencatat kehadiran, menyimpan laporan, dan memperbarui status peserta.
                    </p>
                </div>

                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl text-purple-500 transition-transform duration-300 bg-purple-100 w-14 h-14 rounded-xl group-hover:scale-110">
                        <i class='bx bx-user'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Kejelasan untuk Peserta</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Peserta bisa melihat status kegiatan mereka sendiri, riwayat absensi, serta kewajiban
                        yang harus dipenuhi tanpa harus terus bertanya ke admin.
                    </p>
                </div>

                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl transition-transform duration-300 bg-orange-100 w-14 h-14 rounded-xl text-amber-500 group-hover:scale-110">
                        <i class='bx bx-search'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Jejak Data yang Dapat Diaudit</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Setiap absensi, laporan, dan feedback tersimpan dengan waktu dan identitas yang jelas.
                        Sistem ini adalah alat pembuktian yang objektif.
                    </p>
                </div>

                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl text-pink-500 transition-transform duration-300 bg-pink-100 w-14 h-14 rounded-xl group-hover:scale-110">
                        <i class='bx bx-cog'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Sederhana</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Tidak berisik secara tampilan, tidak ribet secara alur, namun kuat secara logika.
                        Pengelolaan PKL dan magang tidak perlu bergantung pada banyak aplikasi terpisah.
                    </p>
                </div>

                <div
                    class="reveal bg-white rounded-2xl p-8 border border-border transition-all duration-300 hover:-translate-y-1 hover:shadow-custom-lg hover:border-primary/30 relative overflow-hidden before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-1 before:bg-gradient-to-r before:from-primary before:to-primary-dark before:scale-x-0 before:transition-transform before:duration-300 hover:before:scale-x-100">
                    <div
                        class="flex items-center justify-center mb-6 text-2xl text-indigo-500 transition-transform duration-300 bg-indigo-100 w-14 h-14 rounded-xl group-hover:scale-110">
                        <i class='bx bx-check-shield'></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-text-primary">Sistem yang Dapat Diandalkan</h3>
                    <p class="leading-relaxed text-text-secondary">
                        Ini bukan project hiasan, tapi sistem yang memecahkan masalah nyata dengan aturan,
                        otomasi, dan struktur data yang jelas. Dibangun untuk dipakai, diuji, dan dipertanggungjawabkan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="partners" class="py-20 overflow-hidden bg-white">
        <div class="px-6 mx-auto max-w-7xl">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 text-sm font-medium bg-blue-100 rounded-full reveal text-primary">
                    <i class='bx bx-buildings'></i>
                    Partner Kami
                </span>
                <h2 class="mb-4 text-4xl font-bold reveal text-text-primary">
                    Institusi yang <span class="gradient-text">Bekerja Sama</span>
                </h2>
                <p class="max-w-2xl mx-auto text-lg reveal text-text-secondary">
                    Kami bangga dapat bekerja sama dengan berbagai institusi pendidikan terkemuka di Indonesia.
                </p>
            </div>

        </div>
        </div>

        @if ($partners->count() > 0)
            <div
                class="relative py-10 group overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
                <div class="flex">
                    <div
                        class="flex shrink-0 animate-marquee items-center group-hover:[animation-play-state:paused] gap-8 pr-8">
                        @foreach ($partners as $partner)
                            <div class="relative flex-shrink-0 w-32 h-32 md:w-36 md:h-36">
                                <div
                                    class="absolute inset-0 z-10 flex overflow-hidden transition-all duration-500 ease-out bg-white shadow-sm cursor-pointer rounded-2xl border-2 border-slate-200 group/card hover:w-[85vw] hover:-translate-x-1/2 hover:left-1/2 md:hover:w-[32rem] md:hover:-translate-x-16 md:hover:left-0 hover:z-50 hover:shadow-2xl hover:border-primary">
                                    <div
                                        class="flex items-center justify-center flex-shrink-0 w-32 h-32 p-6 transition-all duration-300 bg-white md:w-36 md:h-36">
                                        @if ($partner->logo)
                                            <img src="{{ asset('storage/' . $partner->logo) }}"
                                                alt="{{ $partner->nama }}"
                                                class="object-contain w-full h-full transition-all duration-300 opacity-100">
                                        @else
                                            <i class='text-4xl text-gray-300 bx bx-buildings'></i>
                                        @endif
                                    </div>
                                    <div
                                        class="flex-1 min-w-[200px] p-6 flex flex-col justify-center opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 delay-100 bg-gray-50/50 border-l border-gray-100">
                                        <h4 class="mb-2 text-lg font-bold text-gray-900 line-clamp-1 title"
                                            title="{{ $partner->nama }}">{{ $partner->nama }}</h4>
                                        <div class="space-y-2">
                                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                                <i class='mt-1 bx bx-buildings text-primary'></i>
                                                <span
                                                    class="font-semibold leading-snug line-clamp-2">{{ $partner->nama }}</span>
                                            </div>
                                            @if ($partner->alamat)
                                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                                    <i class='mt-1 bx bx-map text-primary'></i>
                                                    <span
                                                        class="leading-snug line-clamp-2">{{ $partner->alamat }}</span>
                                                </div>
                                            @endif
                                            @if ($partner->deskripsi)
                                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                                    <i class='mt-1 bx bx-info-circle text-primary'></i>
                                                    <span
                                                        class="leading-snug line-clamp-3">{{ $partner->deskripsi }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex shrink-0 animate-marquee items-center group-hover:[animation-play-state:paused] gap-8 pr-8"
                        aria-hidden="true">
                        @foreach ($partners as $partner)
                            <div class="relative flex-shrink-0 w-32 h-32 md:w-36 md:h-36">
                                <div
                                    class="absolute inset-0 z-10 flex overflow-hidden transition-all duration-500 ease-out bg-white shadow-sm cursor-pointer rounded-2xl border-2 border-slate-200 group/card hover:w-[85vw] hover:-translate-x-1/2 hover:left-1/2 md:hover:w-[32rem] md:hover:-translate-x-16 md:hover:left-0 hover:z-50 hover:shadow-2xl hover:border-primary">

                                    <div
                                        class="flex items-center justify-center flex-shrink-0 w-32 h-32 p-6 transition-all duration-300 bg-white md:w-36 md:h-36">
                                        @if ($partner->logo)
                                            <img src="{{ asset('storage/' . $partner->logo) }}"
                                                alt="{{ $partner->nama }}"
                                                class="object-contain w-full h-full transition-all duration-300 opacity-100">
                                        @else
                                            <i class='text-4xl text-gray-300 bx bx-buildings'></i>
                                        @endif
                                    </div>

                                    <div
                                        class="flex-1 min-w-[200px] p-6 flex flex-col justify-center opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 delay-100 bg-gray-50/50 border-l border-gray-100">
                                        <h4 class="mb-2 text-lg font-bold text-gray-900 line-clamp-1 title"
                                            title="{{ $partner->nama }}">{{ $partner->nama }}</h4>

                                        <div class="space-y-2">
                                            <div class="flex items-start gap-2 text-sm text-gray-600">
                                                <i class='mt-1 bx bx-buildings text-primary'></i>
                                                <span
                                                    class="font-semibold leading-snug line-clamp-2">{{ $partner->nama }}</span>
                                            </div>
                                            @if ($partner->alamat)
                                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                                    <i class='mt-1 bx bx-map text-primary'></i>
                                                    <span
                                                        class="leading-snug line-clamp-2">{{ $partner->alamat }}</span>
                                                </div>
                                            @endif

                                            @if ($partner->deskripsi)
                                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                                    <i class='mt-1 bx bx-info-circle text-primary'></i>
                                                    <span
                                                        class="leading-snug line-clamp-3">{{ $partner->deskripsi }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="py-12 text-center text-gray-400 reveal">
                <i class='mb-4 text-6xl opacity-50 bx bx-buildings'></i>
                <p>Belum ada data partner yang ditampilkan.</p>
            </div>
        @endif
        </div>
    </section>

    <section id="feedback" class="py-20 overflow-hidden bg-gradient-to-b from-white to-slate-50">
        <div class="px-6 mx-auto max-w-7xl">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 text-sm font-medium bg-blue-100 rounded-full reveal text-primary">
                    <i class='bx bx-star'></i>
                    Testimoni Pengguna
                </span>
                <h2 class="mb-4 text-4xl font-bold reveal text-text-primary">
                    Apa Kata Mereka tentang <span class="gradient-text">SimpanData</span>?
                </h2>
                <p class="max-w-2xl mx-auto text-lg reveal text-text-secondary">
                    Banyak pengguna yang sudah merasakan kemudahan dalam mengelola program PKL dan magang
                    dengan SimpanData. Berikut adalah beberapa testimoni dari mereka.
                </p>

                @if ($totalReviews > 0)
                    <div class="flex items-center justify-center gap-2 mt-6 reveal">
                        <div class="flex text-xl text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($averageRating))
                                    <i class='bx bxs-star'></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class='bx bxs-star-half'></i>
                                @else
                                    <i class='bx bx-star'></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-lg font-bold text-text-primary">{{ $averageRating }}</span>
                        <span class="text-sm text-text-secondary">({{ $totalReviews }} ulasan)</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($feedbacks && $feedbacks->count() > 0)
            <div class="relative py-8 overflow-hidden feedback-container" id="feedbackContainer1">
                <div class="feedback-track animate-scroll-right"
                    style="animation-duration: 80s; animation-direction: reverse;" id="feedbackTrack1">
                    @foreach ($feedbacks as $feedback)
                        <div
                            class="flex-shrink-0 w-full max-w-sm p-6 transition-all duration-300 bg-white border cursor-pointer rounded-2xl shadow-custom border-border hover:scale-105 hover:shadow-xl hover:z-10 hover:border-primary/30">
                            <div class="flex items-center gap-4 mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-full bg-gradient-to-br from-primary to-primary-dark">
                                    {{ $feedback->peserta ? strtoupper(substr($feedback->peserta->nama, 0, 1)) : 'U' }}
                                </div>
                                <div>
                                    <h4 class="m-0 font-bold text-text-primary">
                                        {{ $feedback->peserta ? $feedback->peserta->nama : 'Pengguna' }}</h4>
                                    <p class="m-0 text-sm text-text-secondary">
                                        {{ $feedback->peserta ? $feedback->peserta->jenis_kegiatan : 'Peserta' }}</p>
                                </div>
                            </div>
                            @if ($feedback->rating)
                                <div class="flex mb-2 text-sm text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class='bx bxs-star'></i>
                                        @else
                                            <i class='text-gray-200 bx bx-star'></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            <p class="text-text-secondary line-clamp-3">{{ $feedback->pesan }}</p>
                        </div>
                    @endforeach
                    @foreach ($feedbacks as $feedback)
                        <div
                            class="flex-shrink-0 w-full max-w-sm p-6 transition-all duration-300 bg-white border cursor-pointer rounded-2xl shadow-custom border-border hover:scale-105 hover:shadow-xl hover:z-10 hover:border-primary/30">
                            <div class="flex items-center gap-4 mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-full bg-gradient-to-br from-primary to-primary-dark">
                                    {{ $feedback->peserta ? strtoupper(substr($feedback->peserta->nama, 0, 1)) : 'U' }}
                                </div>
                                <div>
                                    <h4 class="m-0 font-bold text-text-primary">
                                        {{ $feedback->peserta ? $feedback->peserta->nama : 'Pengguna' }}</h4>
                                    <p class="m-0 text-sm text-text-secondary">
                                        {{ $feedback->peserta ? $feedback->peserta->jenis_kegiatan : 'Peserta' }}</p>
                                </div>
                            </div>
                            @if ($feedback->rating)
                                <div class="flex mb-2 text-sm text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class='bx bxs-star'></i>
                                        @else
                                            <i class='text-gray-200 bx bx-star'></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            <p class="text-text-secondary line-clamp-3">{{ $feedback->pesan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative py-8 mt-8 overflow-hidden feedback-container" id="feedbackContainer2">
                <div class="feedback-track animate-scroll-left"
                    style="animation-duration: 80s; animation-direction: reverse;" id="feedbackTrack2">
                    @foreach ($feedbacks as $feedback)
                        <div
                            class="flex-shrink-0 w-full max-w-sm p-6 transition-all duration-300 bg-white border cursor-pointer rounded-2xl shadow-custom border-border hover:scale-105 hover:shadow-xl hover:z-10 hover:border-primary/30">
                            <div class="flex items-center gap-4 mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-full bg-gradient-to-br from-primary to-primary-dark">
                                    {{ $feedback->peserta ? strtoupper(substr($feedback->peserta->nama, 0, 1)) : 'U' }}
                                </div>
                                <div>
                                    <h4 class="m-0 font-bold text-text-primary">
                                        {{ $feedback->peserta ? $feedback->peserta->nama : 'Pengguna' }}</h4>
                                    <p class="m-0 text-sm text-text-secondary">
                                        {{ $feedback->peserta ? $feedback->peserta->jenis_kegiatan : 'Peserta' }}</p>
                                </div>
                            </div>
                            @if ($feedback->rating)
                                <div class="flex mb-2 text-sm text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class='bx bxs-star'></i>
                                        @else
                                            <i class='text-gray-200 bx bx-star'></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            <p class="text-text-secondary line-clamp-3">{{ $feedback->pesan }}</p>
                        </div>
                    @endforeach
                    @foreach ($feedbacks as $feedback)
                        <div
                            class="flex-shrink-0 w-full max-w-sm p-6 transition-all duration-300 bg-white border cursor-pointer rounded-2xl shadow-custom border-border hover:scale-105 hover:shadow-xl hover:z-10 hover:border-primary/30">
                            <div class="flex items-center gap-4 mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-full bg-gradient-to-br from-primary to-primary-dark">
                                    {{ $feedback->peserta ? strtoupper(substr($feedback->peserta->nama, 0, 1)) : 'U' }}
                                </div>
                                <div>
                                    <h4 class="m-0 font-bold text-text-primary">
                                        {{ $feedback->peserta ? $feedback->peserta->nama : 'Pengguna' }}</h4>
                                    <p class="m-0 text-sm text-text-secondary">
                                        {{ $feedback->peserta ? $feedback->peserta->jenis_kegiatan : 'Peserta' }}</p>
                                </div>
                            </div>
                            @if ($feedback->rating)
                                <div class="flex mb-2 text-sm text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class='bx bxs-star'></i>
                                        @else
                                            <i class='text-gray-200 bx bx-star'></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            <p class="text-text-secondary line-clamp-3">{{ $feedback->pesan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center justify-center w-full min-h-[300px]">
                <div class="text-center empty-testimonial">
                    <div class="mx-auto empty-testimonial-icon">
                        <i class='bx bx-message-square-dots'></i>
                    </div>
                    <p class="empty-testimonial-text">Belum Ada Testimoni</p>
                    <p class="empty-testimonial-subtext">Jadilah yang pertama untuk memberikan testimoni!</p>
                </div>
            </div>
        @endif
    </section>

    <footer class="text-white bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <div class="px-6 mx-auto max-w-7xl">
            <div class="py-16">
                <div class="grid grid-cols-1 gap-12 mb-12 md:grid-cols-2 lg:grid-cols-4">
                    <div class="lg:col-span-1">
                        <a href="#home" class="flex items-center gap-3 mb-6 no-underline group">
                            <img src="{{ asset('storage/logo/logo_simpandata.webp') }}" alt="SimpanData Logo"
                                class="object-contain w-12 h-12 transition-transform duration-300 border-2 rounded-lg border-primary group-hover:scale-110">
                            <span class="text-2xl font-extrabold text-white">SimpanData</span>
                        </a>

                        <p class="mb-6 text-sm leading-relaxed text-slate-300">
                            Sistem pengelolaan kegiatan PKL dan magang yang rapi, terstruktur,
                            dan dapat diandalkan untuk institusi pendidikan dan perusahaan.
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-6 text-lg font-bold text-white">Tautan Cepat</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#home"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Beranda</span>
                                </a>
                            </li>
                            <li>
                                <a href="#features"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Fitur</span>
                                </a>
                            </li>
                            <li>
                                <a href="#feedback"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Testimoni</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('auth') }}"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Masuk</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-6 text-lg font-bold text-white">Legal</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('privacy.policy') }}"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Privacy Policy</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('terms.of.service') }}"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Terms of Service</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('help') }}"
                                    class="flex items-center gap-2 text-sm no-underline transition-all duration-300 text-slate-300 hover:text-primary hover:translate-x-1">
                                    <i class='bx bx-chevron-right'></i>
                                    <span>Help</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-700">
                    <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                        <p class="text-sm text-slate-400">
                            &copy; {{ date('Y') }} <span class="font-semibold text-white">SimpanData</span>. All
                            rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @vite('resources/js/landing.js')
</body>

</html>
