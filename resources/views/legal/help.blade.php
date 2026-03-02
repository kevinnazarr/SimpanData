<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - SimpanData</title>
    <meta name="description" content="Pusat Bantuan SimpanData - Panduan penggunaan dan FAQ sistem pengelolaan PKL & Magang">
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
        html { scroll-behavior: smooth; }

        /* FAQ Accordion */
        .faq-item .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding 0.35s ease;
        }
        .faq-item.open .faq-answer {
            max-height: 500px;
        }
        .faq-item .faq-icon {
            transition: transform 0.35s ease;
        }
        .faq-item.open .faq-icon {
            transform: rotate(45deg);
        }
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
            <a href="{{ url('/') }}"
                class="flex items-center gap-2 text-sm font-medium text-text-secondary hover:text-primary transition-colors duration-200 no-underline">
                <i class='bx bx-arrow-back'></i>
                Kembali ke Beranda
            </a>
        </div>
    </nav>

    <section class="pt-32 pb-16 bg-gradient-to-b from-blue-50 to-slate-50">
        <div class="px-6 mx-auto max-w-4xl text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 text-sm font-medium bg-blue-100 rounded-full text-primary">
                <i class='bx bx-help-circle'></i>
                Bantuan
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-text-primary">
                Pusat <span class="gradient-text">Bantuan</span>
            </h1>
            <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                Temukan jawaban atas pertanyaan umum, panduan penggunaan fitur, dan cara menghubungi kami jika Anda membutuhkan bantuan lebih lanjut.
            </p>
        </div>
    </section>

    <section class="py-12 bg-white border-b border-border">
        <div class="px-6 mx-auto max-w-4xl">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#getting-started" class="flex flex-col items-center gap-3 p-5 bg-slate-50 rounded-2xl border border-border hover:border-primary hover:bg-blue-50 transition-all duration-200 no-underline group">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-primary rounded-xl text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class='bx bx-rocket'></i>
                    </div>
                    <span class="text-sm font-semibold text-text-primary text-center">Memulai</span>
                </a>
                <a href="#peserta-guide" class="flex flex-col items-center gap-3 p-5 bg-slate-50 rounded-2xl border border-border hover:border-primary hover:bg-blue-50 transition-all duration-200 no-underline group">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-xl text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class='bx bx-user'></i>
                    </div>
                    <span class="text-sm font-semibold text-text-primary text-center">Panduan Peserta</span>
                </a>
                <a href="#admin-guide" class="flex flex-col items-center gap-3 p-5 bg-slate-50 rounded-2xl border border-border hover:border-primary hover:bg-blue-50 transition-all duration-200 no-underline group">
                    <div class="flex items-center justify-center w-12 h-12 bg-purple-100 text-purple-600 rounded-xl text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class='bx bx-shield-alt'></i>
                    </div>
                    <span class="text-sm font-semibold text-text-primary text-center">Panduan Admin</span>
                </a>
                <a href="#faq" class="flex flex-col items-center gap-3 p-5 bg-slate-50 rounded-2xl border border-border hover:border-primary hover:bg-blue-50 transition-all duration-200 no-underline group">
                    <div class="flex items-center justify-center w-12 h-12 bg-orange-100 text-orange-500 rounded-xl text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class='bx bx-message-square-dots'></i>
                    </div>
                    <span class="text-sm font-semibold text-text-primary text-center">FAQ</span>
                </a>
            </div>
        </div>
    </section>

    <main class="py-16">
        <div class="px-6 mx-auto max-w-4xl space-y-16">

            <section id="getting-started">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 text-primary rounded-xl text-xl">
                        <i class='bx bx-rocket'></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-primary">Memulai dengan SimpanData</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mb-4">1</div>
                        <h3 class="font-bold text-text-primary mb-2">Aktivasi Akun</h3>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Akun Anda dibuat oleh admin. Setelah menerima email undangan, klik tautan atau gunakan kredensial yang diberikan untuk masuk pertama kali.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mb-4">2</div>
                        <h3 class="font-bold text-text-primary mb-2">Verifikasi & Login</h3>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Masuk menggunakan username dan password Anda. Jika ini pertama kalinya, Anda mungkin diminta untuk memverifikasi email melalui kode OTP.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mb-4">3</div>
                        <h3 class="font-bold text-text-primary mb-2">Mulai Gunakan</h3>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Setelah masuk, akses dashboard Anda. Peserta dapat langsung mengisi absensi dan laporan, sementara admin dapat mengelola seluruh data.
                        </p>
                    </div>
                </div>
            </section>

            <section id="peserta-guide">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-600 rounded-xl text-xl">
                        <i class='bx bx-user'></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-primary">Panduan untuk Peserta</h2>
                </div>

                <div class="space-y-4">

                    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-border bg-green-50">
                            <div class="flex items-center gap-3">
                                <i class='bx bx-calendar-check text-green-600 text-xl'></i>
                                <h3 class="font-bold text-text-primary">Mengisi Absensi Harian</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <ol class="space-y-3 text-text-secondary text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">1</span>
                                    <span>Masuk ke dashboard Anda dan pilih menu <strong class="text-text-primary">Absensi</strong>.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">2</span>
                                    <span>Klik tombol <strong class="text-text-primary">Isi Absensi Hari Ini</strong>.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">3</span>
                                    <span>Pilih status kehadiran (Hadir / Izin / Sakit) dan tambahkan keterangan jika diperlukan.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">4</span>
                                    <span>Klik <strong class="text-text-primary">Simpan</strong>. Absensi hanya dapat diisi sekali per hari.</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-border bg-green-50">
                            <div class="flex items-center gap-3">
                                <i class='bx bx-file-blank text-green-600 text-xl'></i>
                                <h3 class="font-bold text-text-primary">Mengunggah Laporan Harian</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <ol class="space-y-3 text-text-secondary text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">1</span>
                                    <span>Pilih menu <strong class="text-text-primary">Laporan Harian</strong> di sidebar.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">2</span>
                                    <span>Klik <strong class="text-text-primary">Unggah Laporan Baru</strong>, pilih file dari perangkat Anda (format PDF, Word, atau gambar).</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">3</span>
                                    <span>Pastikan laporan sudah lengkap dan sesuai ketentuan institusi sebelum mengunggah.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">4</span>
                                    <span>Klik <strong class="text-text-primary">Simpan</strong>. Laporan yang sudah diunggah dapat dilihat di daftar laporan.</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-border bg-green-50">
                            <div class="flex items-center gap-3">
                                <i class='bx bx-star text-green-600 text-xl'></i>
                                <h3 class="font-bold text-text-primary">Memberikan Feedback</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <ol class="space-y-3 text-text-secondary text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">1</span>
                                    <span>Pilih menu <strong class="text-text-primary">Feedback</strong>.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">2</span>
                                    <span>Berikan rating bintang (1–5) sesuai pengalaman Anda menggunakan SimpanData.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">3</span>
                                    <span>Tulis komentar atau saran Anda pada kolom yang tersedia, lalu klik <strong class="text-text-primary">Kirim</strong>.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 bg-green-100 text-green-700 rounded-full text-xs font-bold">4</span>
                                    <span>Feedback Anda dapat muncul di halaman beranda sebagai testimoni pengguna.</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                </div>
            </section>

            <section id="admin-guide">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-xl text-xl">
                        <i class='bx bx-shield-alt'></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-primary">Panduan untuk Admin</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-user-plus text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Manajemen Peserta</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Admin dapat menambahkan, mengedit, atau menonaktifkan akun peserta melalui menu <strong>Data Peserta</strong>. Isi semua informasi yang diperlukan termasuk nama, email, dan jenis kegiatan (PKL/Magang).
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-calendar text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Monitoring Absensi</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Pantau kehadiran seluruh peserta melalui menu <strong>Data Absensi</strong>. Admin dapat memfilter data berdasarkan tanggal dan nama peserta, serta mencetak rekap absensi.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-file text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Verifikasi Laporan</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Tinjau laporan harian dan laporan akhir yang diunggah oleh peserta. Admin dapat mengunduh, menilai, dan memberikan status pada setiap laporan.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-bar-chart-alt-2 text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Laporan & Rekap</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Akses menu <strong>Laporan</strong> untuk membuat laporan harian dan laporan akhir berbasis data sistem. Laporan dapat diekspor dan dicetak untuk keperluan administrasi.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-buildings text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Manajemen Partner</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Tambah dan kelola data perusahaan atau institusi mitra melalui menu <strong>Partner</strong>. Data partner akan tampil di halaman beranda website.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-border shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <i class='bx bx-cog text-purple-600 text-xl'></i>
                            <h3 class="font-bold text-text-primary">Pengaturan Sistem</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Kelola pengaturan umum sistem seperti periode aktif PKL/magang, logo institusi, dan konfigurasi notifikasi melalui menu <strong>Pengaturan</strong>.
                        </p>
                    </div>
                </div>
            </section>

            <section id="faq">
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center justify-center w-10 h-10 bg-orange-100 text-orange-500 rounded-xl text-xl">
                        <i class='bx bx-message-square-dots'></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-primary">Pertanyaan yang Sering Diajukan (FAQ)</h2>
                </div>

                <div class="space-y-3" id="faqContainer">

                    @foreach ([
                        [
                            'q' => 'Bagaimana cara mendaftar ke SimpanData?',
                            'a' => 'Pendaftaran dilakukan oleh admin. Anda tidak dapat mendaftar secara mandiri. Admin institusi atau perusahaan tempat Anda PKL/magang akan membuat akun dan memberikan kredensial login kepada Anda.',
                        ],
                        [
                            'q' => 'Saya lupa password, apa yang harus dilakukan?',
                            'a' => 'Di halaman login, klik tautan "Lupa Password?". Masukkan alamat email yang terdaftar, dan kami akan mengirimkan kode OTP untuk mereset password Anda. Pastikan untuk memeriksa folder spam/junk jika email tidak masuk.',
                        ],
                        [
                            'q' => 'Apakah saya bisa mengisi absensi lebih dari sekali dalam sehari?',
                            'a' => 'Tidak. Sistem hanya memperbolehkan satu pengisian absensi per hari per peserta. Jika Anda melakukan kesalahan, hubungi admin untuk koreksi data.',
                        ],
                        [
                            'q' => 'Format file apa yang didukung untuk mengunggah laporan?',
                            'a' => 'Sistem mendukung format file PDF, DOC, DOCX, JPG, JPEG, dan PNG. Ukuran maksimum per file adalah 10 MB. Pastikan file laporan Anda tidak melebihi batas ukuran tersebut.',
                        ],
                        [
                            'q' => 'Apakah laporan yang sudah diunggah bisa dihapus atau diubah?',
                            'a' => 'Peserta tidak dapat menghapus laporan yang sudah diunggah secara mandiri. Untuk perubahan atau penghapusan, silakan hubungi admin dengan menyebutkan tanggal laporan dan alasan perubahan.',
                        ],
                        [
                            'q' => 'Mengapa akun saya tidak bisa masuk padahal password sudah benar?',
                            'a' => 'Kemungkinan penyebabnya: (1) Akun Anda mungkin dinonaktifkan oleh admin, (2) Anda menggunakan email yang salah, atau (3) ada masalah sementara pada sistem. Coba reset password terlebih dahulu, atau hubungi admin jika masalah berlanjut.',
                        ],
                        [
                            'q' => 'Bagaimana cara melihat riwayat absensi saya?',
                            'a' => 'Masuk ke dashboard, lalu pilih menu "Absensi" atau "Riwayat Absensi". Di sana Anda dapat melihat seluruh riwayat kehadiran beserta statusnya dari awal hingga saat ini.',
                        ],
                        [
                            'q' => 'Apakah data saya aman di SimpanData?',
                            'a' => 'Ya. SimpanData menggunakan enkripsi password, token CSRF untuk keamanan formulir, verifikasi OTP, serta akses file yang dikontrol secara ketat. Dokumen dan laporan Anda disimpan di disk privat yang tidak dapat diakses publik.',
                        ],
                        [
                            'q' => 'Bagaimana cara menghapus akun saya?',
                            'a' => 'Kirim permintaan penghapusan akun melalui email ke simpandata067@gmail.com dengan subject "Permintaan Hapus Akun" dari email yang terdaftar. Tim admin akan memproses permintaan Anda dalam 3-5 hari kerja.',
                        ],
                    ] as $faq)
                    <div class="faq-item bg-white rounded-2xl border border-border shadow-sm overflow-hidden cursor-pointer">
                        <button class="w-full flex items-center justify-between p-6 text-left" onclick="toggleFaq(this)">
                            <span class="font-semibold text-text-primary pr-4">{{ $faq['q'] }}</span>
                            <i class='bx bx-plus faq-icon text-primary text-xl flex-shrink-0'></i>
                        </button>
                        <div class="faq-answer">
                            <div class="px-6 pb-6 text-text-secondary text-sm leading-relaxed border-t border-border pt-4">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </section>

            <section id="contact" class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-10 text-white text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl text-3xl mx-auto mb-4">
                    <i class='bx bx-support'></i>
                </div>
                <h2 class="text-2xl font-bold mb-3">Masih Butuh Bantuan?</h2>
                <p class="text-blue-100 leading-relaxed mb-6 max-w-xl mx-auto">
                    Tidak menemukan jawaban yang Anda cari? Tim kami siap membantu. Kirimkan pertanyaan atau laporan masalah Anda melalui email di bawah ini.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="mailto:simpandata067@gmail.com"
                        class="inline-flex items-center gap-3 px-7 py-3.5 bg-white text-primary font-semibold rounded-xl hover:bg-blue-50 transition-colors duration-200 no-underline">
                        <i class='bx bx-envelope text-xl'></i>
                        simpandata067@gmail.com
                    </a>
                </div>
                <p class="mt-5 text-blue-200 text-sm">Waktu respons: 1–2 hari kerja</p>
            </section>

        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-8 mt-8">
        <div class="px-6 mx-auto max-w-4xl flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm">&copy; {{ date('Y') }} <span class="text-white font-semibold">SimpanData</span>. All rights reserved.</p>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('privacy.policy') }}" class="hover:text-white no-underline transition-colors">Kebijakan Privasi</a>
                <a href="{{ route('terms.of.service') }}" class="hover:text-white no-underline transition-colors">Syarat Layanan</a>
                <a href="{{ route('help') }}" class="text-primary no-underline font-medium">Bantuan</a>
            </div>
        </div>
    </footer>

    <script>
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));

            if (!isOpen) {
                item.classList.add('open');
            }
        }
    </script>

</body>
</html>
