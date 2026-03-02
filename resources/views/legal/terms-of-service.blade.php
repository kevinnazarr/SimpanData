<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan Layanan - SimpanData</title>
    <meta name="description" content="Syarat dan Ketentuan Penggunaan SimpanData - Sistem Pengelolaan PKL & Magang">
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
                <i class='bx bx-file-blank'></i>
                Legal
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-text-primary">
                Syarat & <span class="gradient-text">Ketentuan Layanan</span>
            </h1>
            <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                Dengan menggunakan SimpanData, Anda menyetujui syarat dan ketentuan berikut. Mohon baca dengan seksama sebelum menggunakan platform ini.
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
                    ['1. Penerimaan Syarat', '#section-1'],
                    ['2. Deskripsi Layanan', '#section-2'],
                    ['3. Ketentuan Akun', '#section-3'],
                    ['4. Kewajiban Pengguna', '#section-4'],
                    ['5. Konten & Hak Cipta', '#section-5'],
                    ['6. Batasan Layanan', '#section-6'],
                    ['7. Penghentian Akun', '#section-7'],
                    ['8. Perubahan Layanan', '#section-8'],
                    ['9. Hukum & Yurisdiksi', '#section-9'],
                    ['10. Hubungi Kami', '#section-10'],
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
                    Penerimaan Syarat
                </h2>
                <p class="text-text-secondary leading-relaxed">
                    Dengan mendaftar, masuk, atau menggunakan platform SimpanData dalam bentuk apapun, Anda secara otomatis menyatakan telah membaca, memahami, dan menyetujui Syarat & Ketentuan ini secara penuh. Jika Anda tidak menyetujui ketentuan ini, harap tidak menggunakan layanan SimpanData. Pengguna yang berusia di bawah 17 tahun harus mendapatkan izin dari orang tua atau wali sebelum menggunakan platform ini.
                </p>
            </div>

            <div id="section-2" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">2</span>
                    Deskripsi Layanan
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    SimpanData adalah platform manajemen digital yang dirancang khusus untuk mendukung pengelolaan program Praktik Kerja Lapangan (PKL) dan Magang. Fitur utama yang disediakan meliputi:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-calendar-check text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Manajemen Absensi</p>
                            <p class="text-text-secondary text-sm">Pencatatan kehadiran harian peserta secara digital dan terpusat.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-file text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Laporan Digital</p>
                            <p class="text-text-secondary text-sm">Upload dan pengelolaan laporan harian maupun laporan akhir kegiatan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-user-circle text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Manajemen Peserta</p>
                            <p class="text-text-secondary text-sm">Pengelolaan data profil dan status kegiatan seluruh peserta PKL/magang.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-bar-chart text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Laporan & Rekap</p>
                            <p class="text-text-secondary text-sm">Dashboard dan laporan ringkasan untuk keperluan evaluasi dan administrasi.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-star text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Feedback & Penilaian</p>
                            <p class="text-text-secondary text-sm">Sistem ulasan dan rating dari peserta untuk evaluasi kualitas layanan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                        <i class='bx bx-buildings text-primary text-xl mt-0.5 flex-shrink-0'></i>
                        <div>
                            <p class="font-semibold text-text-primary text-sm">Manajemen Partner</p>
                            <p class="text-text-secondary text-sm">Pengelolaan data perusahaan dan institusi yang bermitra dalam program PKL/magang.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="section-3" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">3</span>
                    Ketentuan Akun
                </h2>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Setiap pengguna hanya boleh memiliki satu akun aktif. Pembuatan akun ganda tidak diperbolehkan dan dapat mengakibatkan penangguhan seluruh akun terkait.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Anda bertanggung jawab sepenuhnya untuk menjaga kerahasiaan kredensial login (username dan password) akun Anda. Jangan bagikan informasi ini kepada siapapun.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Registrasi akun memerlukan verifikasi email melalui kode OTP. Gunakan alamat email yang valid dan aktif untuk proses ini.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Seluruh aktivitas yang terjadi di bawah akun Anda menjadi tanggung jawab Anda. Segera laporkan kepada admin jika Anda mencurigai adanya akses tidak sah.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-primary mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Akun peserta dibuat dan dikelola oleh admin. Peserta tidak dapat mendaftar secara mandiri kecuali melalui mekanisme yang telah ditentukan oleh admin institusi.</span>
                    </li>
                </ul>
            </div>

            <div id="section-4" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">4</span>
                    Kewajiban & Larangan Pengguna
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">Pengguna <strong class="text-text-primary">wajib</strong> untuk:</p>
                <ul class="space-y-2 text-text-secondary mb-6">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Menggunakan platform sesuai dengan kebijakan institusi atau perusahaan yang bersangkutan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mengisi data absensi dan laporan secara jujur, akurat, dan tepat waktu.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check text-green-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mengunggah dokumen yang sesuai dengan ketentuan format dan ukuran file yang berlaku.</span>
                    </li>
                </ul>
                <p class="text-text-secondary leading-relaxed mb-4">Pengguna <strong class="text-red-500">dilarang</strong> untuk:</p>
                <ul class="space-y-2 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-x text-red-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Memanipulasi, memalsukan, atau mengubah data absensi dan laporan milik pengguna lain.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-x text-red-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mencoba mengakses data atau fitur yang bukan merupakan hak akses peran (role) Anda.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-x text-red-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Melakukan serangan siber seperti injeksi SQL, XSS, brute force, atau aktivitas berbahaya lainnya terhadap sistem.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-x text-red-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Mengunggah file yang mengandung malware, virus, atau konten ilegal.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-x text-red-500 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Menggunakan sistem untuk tujuan komersial, promosi, atau di luar konteks PKL/magang tanpa izin tertulis.</span>
                    </li>
                </ul>
            </div>

            <div id="section-5" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">5</span>
                    Konten & Hak Cipta
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Konten yang Anda unggah ke SimpanData (laporan, dokumen, dll.) tetap menjadi milik Anda. Namun, dengan mengunggah konten tersebut, Anda memberikan SimpanData izin terbatas untuk menyimpan dan menampilkan konten tersebut kepada pihak yang berwenang (misalnya, admin institusi) dalam konteks pengelolaan program PKL/magang.
                </p>
                <p class="text-text-secondary leading-relaxed">
                    Seluruh elemen platform SimpanData, termasuk desain antarmuka, logo, kode sumber, dan aset lainnya adalah kekayaan intelektual dari pengembang SimpanData dan dilindungi oleh hak cipta yang berlaku. Dilarang keras untuk mereproduksi, mendistribusikan, atau menggunakan aset tersebut tanpa izin tertulis.
                </p>
            </div>

            <div id="section-6" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">6</span>
                    Batasan Tanggung Jawab Layanan
                </h2>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 flex items-start gap-3">
                    <i class='bx bx-error-circle text-amber-500 text-xl mt-0.5 flex-shrink-0'></i>
                    <p class="text-sm text-amber-800">
                        SimpanData disediakan "sebagaimana adanya" (<em>as-is</em>). Kami berupaya menjaga ketersediaan layanan 24/7, namun tidak memberikan garansi layanan tanpa gangguan.
                    </p>
                </div>
                <ul class="space-y-3 text-text-secondary">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-info-circle text-blue-400 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>SimpanData tidak bertanggung jawab atas kerugian yang timbul akibat kelalaian pengguna dalam menjaga keamanan akun mereka.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-info-circle text-blue-400 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Kami tidak bertanggung jawab atas kehilangan data yang disebabkan oleh force majeure (bencana alam, kegagalan infrastruktur pihak ketiga, dll).</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-info-circle text-blue-400 mt-0.5 text-lg flex-shrink-0'></i>
                        <span>Pemeliharaan sistem (maintenance) dapat dilakukan sewaktu-waktu dengan atau tanpa pemberitahuan terlebih dahulu.</span>
                    </li>
                </ul>
            </div>

            <div id="section-7" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">7</span>
                    Penghentian Akun
                </h2>
                <p class="text-text-secondary leading-relaxed mb-4">
                    Admin berhak untuk menangguhkan atau menghapus akun pengguna tanpa pemberitahuan sebelumnya apabila ditemukan pelanggaran terhadap Syarat & Ketentuan ini. Akun peserta juga dapat dinonaktifkan secara otomatis setelah masa kegiatan PKL/magang berakhir.
                </p>
                <p class="text-text-secondary leading-relaxed">
                    Pengguna yang ingin menghapus akunnya secara sukarela dapat mengajukan permintaan kepada admin melalui email yang terdaftar. Setelah penghapusan akun dikonfirmasi, seluruh data terkait akun tersebut akan dihapus dari sistem sesuai dengan kebijakan retensi data yang berlaku.
                </p>
            </div>

            <div id="section-8" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">8</span>
                    Perubahan Layanan
                </h2>
                <p class="text-text-secondary leading-relaxed">
                    SimpanData berhak untuk mengubah, menambah, atau menghentikan fitur-fitur tertentu dari layanan kapan saja. Kami akan berusaha untuk memberitahukan perubahan signifikan melalui notifikasi di platform. Penggunaan platform yang berlanjut setelah perubahan berlaku dianggap sebagai persetujuan Anda terhadap perubahan tersebut.
                </p>
            </div>

            <div id="section-9" class="bg-white rounded-2xl p-8 shadow-sm border border-border section-card">
                <h2 class="text-2xl font-bold text-text-primary mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-blue-100 text-primary rounded-xl text-base font-bold">9</span>
                    Hukum & Yurisdiksi
                </h2>
                <p class="text-text-secondary leading-relaxed">
                    Syarat & Ketentuan ini tunduk pada dan diinterpretasikan sesuai dengan hukum yang berlaku di Negara Kesatuan Republik Indonesia. Setiap sengketa yang timbul dari atau berkaitan dengan penggunaan SimpanData akan diselesaikan melalui musyawarah mufakat terlebih dahulu. Apabila tidak tercapai kesepakatan, penyelesaian akan dilakukan melalui lembaga penyelesaian sengketa yang berwenang di wilayah Indonesia.
                </p>
            </div>

            <div id="section-10" class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 bg-white/20 rounded-xl text-base font-bold">10</span>
                    Hubungi Kami
                </h2>
                <p class="text-blue-100 leading-relaxed mb-6">
                    Jika Anda memiliki pertanyaan tentang Syarat & Ketentuan ini, ingin melaporkan pelanggaran, atau memerlukan klarifikasi, silakan hubungi kami melalui email berikut.
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
                <a href="{{ route('privacy.policy') }}" class="hover:text-white no-underline transition-colors">Kebijakan Privasi</a>
                <a href="{{ route('terms.of.service') }}" class="text-primary no-underline font-medium">Syarat Layanan</a>
                <a href="{{ route('help') }}" class="hover:text-white no-underline transition-colors">Bantuan</a>
            </div>
        </div>
    </footer>

</body>
</html>
