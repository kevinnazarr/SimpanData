# SimpanData — Sistem Manajemen Peserta PKL/Magang

**SimpanData** adalah platform berbasis web yang dirancang untuk mengelola seluruh siklus hidup peserta Praktik Kerja Lapangan (PKL) dan Magang secara digital, aman, dan efisien. Dibangun menggunakan **Laravel 12**, sistem ini menangani proses dari registrasi terverifikasi OTP hingga penilaian akhir oleh administrator.

---

## 🚀 Fitur Utama

- **Otentikasi Aman**: Login berbasis session yang terenkripsi dengan verifikasi OTP (One-Time Password) via email untuk registrasi dan lupa password.
- **Monitoring Absensi**: Pencatatan kehadiran harian dengan mode kerja (WFO/WFA) dan status (Hadir, Izin, Sakit).
- **Manajemen Laporan**: Pengumpulan laporan harian dan laporan akhir dalam format PDF yang tersimpan secara privat (secure disk).
- **Dashboard Analitik**: Visualisasi tren kehadiran dan statistik peserta menggunakan Chart.js untuk admin.
- **Sistem Penilaian**: Penilaian terukur berdasarkan kriteria kedisiplinan, keterampilan, kerjasama, inisiatif, dan komunikasi.
- **Feedback & Rating**: Jalur komunikasi dua arah antara peserta dan admin beserta sistem rating kepuasan.
- **Auto-Archive & Data Purge**: Otomatisasi pengarsipan peserta yang sudah selesai dan pembersihan data lama untuk menjaga performa database.

---

## 🛠 Tech Stack

| Komponen | Teknologi |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 12 |
| **Frontend** | Blade, Tailwind CSS, Alpine.js |
| **Database** | PostgreSQL / SQLite / MySQL |
| **Build Tool** | Vite 7 |
| **Charts** | Chart.js |
| **Queue** | Database Driven (untuk email OTP) |

---

## 💻 Panduan Instalasi (Windows)

Ikuti langkah-langkah di bawah ini untuk menjalankan project di lingkungan lokal Windows:

### 1. Persiapan Lingkungan
Pastikan Anda sudah menginstal:
- **PHP 8.2+** (disarankan via Laragon atau XAMPP)
- **Composer**
- **Node.js & NPM** (versi terbaru)
- **PostgreSQL** (sesuai konfigurasi `.env`)

### 2. Clone Repository
```powershell
git clone https://github.com/kevinnazarr/SimpanData.git
cd SimpanData
```

### 3. Instalasi Dependency
```powershell
# Install library PHP
composer install

# Install library JavaScript
npm install
```

### 4. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```powershell
cp .env.example .env
```
Buka file `.env` dan sesuaikan pengaturan database dan mail server Anda.

### 5. Inisialisasi Project
```powershell
# Generate APP_KEY
php artisan key:generate

# Buat symbolic link untuk storage
php artisan storage:link

# Jalankan migrasi database
php artisan migrate
```

### 6. Menjalankan Aplikasi
Gunakan perintah kustom yang sudah disediakan untuk menjalankan semua proses (Server, Vite, Queue, Pail) sekaligus:
```powershell
composer run dev
```
Aplikasi akan dapat diakses di: `http://localhost:8000`

---

## ⚙️ Konfigurasi .env

Pastikan bagian berikut dikonfigurasi dengan benar agar fitur aplikasi berjalan maksimal:

### Database (PostgreSQL)
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_simpandata
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Keamanan Session
Aplikasi ini menggunakan enkripsi session untuk keamanan ekstra:
```env
SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_LIFETIME=60
```

### Email (SMTP Gmail)
Digunakan untuk mengirim kode OTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD="your_app_password"
MAIL_ENCRYPTION=tls
```

---

## 📂 Struktur Folder Utama

- `app/Http/Controllers/`: Logika aplikasi utama (Admin, Peserta, Auth).
- `app/Models/`: Definisi skema database dan relasi Eloquent.
- `resources/views/`: Template tampilan aplikasi (Blade).
- `routes/web.php`: Definisi seluruh endpoint URL.
- `storage/app/secure/`: Lokasi penyimpanan file PDF laporan (Privat).
- `app/Console/Commands/`: Perintah otomatisasi (seperti Auto-Archive).

---

## 🛡 Keamanan (Security)

- **Permissions-Policy**: Mengatur izin fitur browser seperti geolokasi agar hanya berjalan di domain tepercaya.
- **CSRF Protection**: Melindungi formulir dari serangan lintas situs.
- **OTP Hashing**: Kode OTP disimpan dalam bentuk hash di database.
- **Rate Limiting**: Membatasi percobaan login dan pengiriman OTP untuk mencegah brute force.
- **Access Control**: Otorisasi ketat untuk akses file laporan privat via `FileController`.

---

## 📝 Catatan Pengembangan

Aplikasi ini menggunakan sistem **Queue** untuk pengiriman email agar tidak menghambat performa UI pengguna. Jika menjalankan secara manual tanpa `composer run dev`, pastikan menjalankan:
```powershell
php artisan queue:listen
```

Dibuat dengan ❤️ oleh Tim Pengembang SimpanData.
