<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - SimpanData</title>
    <meta name="description" content="Kebijakan Privasi SimpanData - Sistem Pengelolaan PKL & Magang">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo/logo_simpandata.webp') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                        'text-primary': '#1e293b',
                        'text-secondary': '#64748b',
                        border: '#e2e8f0',
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .section-card {
            border-left: 4px solid #3b82f6;
        }
        html { scroll-behavior: smooth; }
    </style>
</head>

<body class="font-inter bg-slate-50 text-text-primary">

    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-border shadow-sm">
        <div class="flex items-center justify-between px-6 mx-auto max-w-7xl py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline">
                <img src="{{ asset('storage/logo/logo_simpandata.webp') }}" alt="SimpanData Logo"
                    class="object-contain w-9 h-9 border-2 rounded-lg border-primary">
                <span class="text-xl font-extrabold text-text-primary">SimpanData</span>
            </a>
            <a href="{{ auth()->check() ? url()->previous() : url('/') }}"
                class="flex items-center gap-2 text-sm font-medium text-text-secondary hover:text-primary transition-colors duration-200 no-underline">
                <i class='bx bx-arrow-back'></i>
                {{ auth()->check() ? 'Kembali' : 'Kembali ke Beranda' }}
            </a>
        </div>
    </nav>

    <section class="pt-32 pb-16 bg-gradient-to-b from-blue-50 to-slate-50">
        <div class="px-6 mx-auto max-w-4xl text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 text-sm font-medium bg-blue-100 rounded-full text-primary">
                <i class='bx bx-shield-alt'></i>
                Legal
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-text-primary">
                Kebijakan <span class="gradient-text">Privasi</span>
            </h1>
            <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                Kami berkomitmen untuk melindungi privasi dan keamanan data Anda. Halaman ini menjelaskan bagaimana SimpanData mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.
            </p>
            <p class="mt-4 text-sm text-text-secondary">
                Terakhir diperbarui: <span class="font-semibold">1 Maret 2025</span>
            </p>
        </div>
    </section>

    <section class="py-8 bg-white border-b border-border">
        <div class="px-6 mx-auto max-w-4xl">
            <h2 class="text-sm font-semibold text-text-secondary uppercase tracking-wider mb-4">Daftar Isi</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ([
                    ['1. Informasi yang Kami Kumpulkan', '#section-1'],
                    ['2. Cara Penggunaan Data', '#section-2'],
                    ['3. Penyimpanan & Keamanan', '#section-3'],
                    ['4. Berbagi Data', '#section-4'],
                    ['5. Hak Pengguna', '#section-5'],
                    ['6. Cookie & Sesi', '#section-6'],
                    ['7. Perubahan Kebijakan', '#section-7'],
                    ['8. Hubungi Kami', '#section-8'],
                ] as [$label, $href])
                    <a href="{{ $href }}" class="text-sm text-primary hover:underline no-underline">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <main class="py-16">
        <div class="px-6 mx-auto max-w-4xl space-y-12">

            <div id="section-1" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">1</span>
                    Informasi yang Kami Kumpulkan
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    SimpanData mengumpulkan informasi yang diperlukan untuk menyediakan layanan pengelolaan PKL dan magang secara efektif. Informasi tersebut meliputi:
                </p>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Data Identitas:</strong> Nama lengkap, username, alamat email, dan nomor identitas peserta yang digunakan untuk autentikasi dan identifikasi akun.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Data Kegiatan:</strong> Informasi terkait program PKL atau magang yang diikuti, termasuk nama institusi/perusahaan, tanggal mulai dan berakhir, serta status kegiatan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Data Absensi:</strong> Catatan kehadiran harian beserta keterangan dan waktu pencatatan secara otomatis oleh sistem.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Laporan & Dokumen:</strong> File laporan harian, laporan akhir, dan dokumen pendukung lainnya yang diunggah oleh peserta.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Feedback & Penilaian:</strong> Ulasan, rating, dan komentar yang diberikan oleh peserta terhadap sistem.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Data Teknis:</strong> Alamat IP, jenis browser, dan log aktivitas yang digunakan untuk keperluan keamanan dan pemeliharaan sistem.</span>
                    </li>
                </ul>
            </div>

            <div id="section-2" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">2</span>
                    Cara Penggunaan Data
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Data yang dikumpulkan digunakan semata-mata untuk mendukung operasional platform SimpanData, yaitu:
                </p>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-right-arrow-alt text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mengelola akun peserta dan admin serta proses autentikasi yang aman.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-right-arrow-alt text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mencatat dan memantau kehadiran serta perkembangan kegiatan PKL/magang peserta.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-right-arrow-alt text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Menghasilkan laporan dan rekap data untuk keperluan evaluasi dan administrasi institusi.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-right-arrow-alt text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mengirimkan notifikasi dan email penting terkait akun, OTP, dan pengingat kegiatan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-right-arrow-alt text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Meningkatkan kualitas layanan berdasarkan feedback dan analisis penggunaan sistem.</span>
                    </li>
                </ul>
            </div>

            <div id="section-3" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">3</span>
                    Penyimpanan & Keamanan Data
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Keamanan data Anda adalah prioritas utama kami. SimpanData menerapkan langkah-langkah teknis berikut:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class='bx bx-lock-alt text-primary text-xl'></i>
                            <span class="font-semibold text-text-primary">Enkripsi Password</span>
                        </div>
                        <p class="text-sm text-text-secondary">Kata sandi disimpan menggunakan algoritma hashing bcrypt yang aman dan tidak dapat dibaca secara langsung.</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class='bx bx-shield text-primary text-xl'></i>
                            <span class="font-semibold text-text-primary">Autentikasi OTP</span>
                        </div>
                        <p class="text-sm text-text-secondary">Proses registrasi dan reset password dilindungi dengan verifikasi One-Time Password (OTP) melalui email.</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class='bx bx-file-blank text-primary text-xl'></i>
                            <span class="font-semibold text-text-primary">Akses File Aman</span>
                        </div>
                        <p class="text-sm text-text-secondary">Dokumen dan laporan yang diunggah disimpan di disk privat dan hanya dapat diakses oleh pengguna yang berwenang.</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class='bx bx-user-check text-primary text-xl'></i>
                            <span class="font-semibold text-text-primary">Kontrol Akses Peran</span>
                        </div>
                        <p class="text-sm text-text-secondary">Sistem menggunakan role-based access control (RBAC) untuk memastikan setiap pengguna hanya dapat mengakses data yang relevan.</p>
                    </div>
                </div>
            </div>

            <div id="section-4" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">4</span>
                    Berbagi Data dengan Pihak Ketiga
                </h2>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 flex items-start gap-3">
                    <i class='bx bx-info-circle text-amber-500 text-xl mt-0.5 flex-shrink-0'></i>
                    <p class="text-sm text-amber-800">
                        <strong>SimpanData tidak menjual, menyewakan, atau memperdagangkan data pribadi Anda kepada pihak ketiga manapun untuk tujuan komersial.</strong>
                    </p>
                </div>
                <p class="text-text-secondary leading-relaxed">
                    Data Anda hanya dapat dibagikan dalam kondisi berikut:
                </p>
                <ul class="mt-4 space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-chevron-right text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Kepada Admin Institusi:</strong> Data peserta dapat dilihat oleh admin yang berwenang dalam lingkup institusi atau perusahaan tempat Anda melaksanakan PKL/magang.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-chevron-right text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Kewajiban Hukum:</strong> Jika diwajibkan oleh peraturan perundang-undangan yang berlaku di Indonesia.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-chevron-right text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Layanan Email:</strong> Alamat email Anda digunakan oleh layanan pengiriman email untuk mengirimkan OTP dan notifikasi sistem.</span>
                    </li>
                </ul>
            </div>

            <div id="section-5" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">5</span>
                    Hak Pengguna
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Sebagai pengguna SimpanData, Anda memiliki hak-hak berikut atas data pribadi Anda:
                </p>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Hak Akses:</strong> Anda dapat melihat dan mengunduh data kegiatan, absensi, serta laporan yang tersimpan di akun Anda.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Hak Koreksi:</strong> Anda dapat menghubungi admin untuk memperbaiki data yang tidak akurat.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Hak Penghapusan:</strong> Setelah masa kegiatan berakhir, Anda dapat mengajukan permintaan penghapusan akun kepada admin.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span><strong class="text-text-primary">Hak Keberatan:</strong> Anda berhak mengajukan keberatan atas cara pengolahan data Anda melalui kontak yang tersedia.</span>
                    </li>
                </ul>
            </div>

            <div id="section-6" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">6</span>
                    Cookie & Sesi
                </h2>
                <p class="text-text-secondary leading-relaxed">
                    SimpanData menggunakan cookie dan sesi browser untuk keperluan teknis semata, yaitu menjaga status login Anda selama menggunakan aplikasi dan memvalidasi token CSRF untuk keamanan formulir. Kami tidak menggunakan cookie untuk pelacakan iklan atau analitik pihak ketiga. Sesi akan berakhir secara otomatis setelah Anda keluar atau setelah periode tidak aktif.
                </p>
            </div>

            <div id="section-7" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">7</span>
                    Perubahan Kebijakan Privasi
                </h2>
                <p class="text-text-secondary leading-relaxed">
                    Kami berhak memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan yang signifikan akan diinformasikan melalui notifikasi pada platform atau email yang terdaftar. Dengan terus menggunakan SimpanData setelah perubahan berlaku, Anda dianggap telah menyetujui kebijakan yang diperbarui. Kami menyarankan Anda untuk meninjau halaman ini secara berkala.
                </p>
            </div>

            <div id="section-8" class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-white/20 rounded-xl text-base font-bold">8</span>
                    Hubungi Kami
                </h2>
                <p class="text-blue-100 leading-relaxed mb-6">
                    Jika Anda memiliki pertanyaan, kekhawatiran, atau permintaan terkait kebijakan privasi dan pengelolaan data Anda, jangan ragu untuk menghubungi kami.
                </p>
                <a href="mailto:simpandata067@gmail.com"
                    class="inline-flex items-center gap-3 px-6 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-blue-50 transition-colors duration-200 no-underline">
                    <i class='bx bx-envelope text-xl'></i>
                    simpandata067@gmail.com
                </a>
            </div>

        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-8 mt-8">
        <div class="px-6 mx-auto max-w-4xl flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm">&copy; {{ date('Y') }} <span class="text-white font-semibold">SimpanData</span>. All rights reserved.</p>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('privacy.policy') }}" class="text-primary no-underline font-medium">Kebijakan Privasi</a>
                <a href="{{ route('terms.of.service') }}" class="hover:text-white no-underline transition-colors">Syarat Layanan</a>
                <a href="{{ route('help') }}" class="hover:text-white no-underline transition-colors">Bantuan</a>
            </div>
        </div>
    </footer>

</body>
</html>
