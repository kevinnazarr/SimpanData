# SimpanData — Sistem Manajemen Peserta PKL/Magang

## 1. System Overview

**SimpanData** adalah aplikasi manajemen peserta PKL (Praktik Kerja Lapangan) dan Magang berbasis web yang dibangun menggunakan Laravel 12. Sistem ini dirancang untuk mengelola siklus hidup peserta secara penuh — mulai dari registrasi mandiri, absensi harian, pengumpulan laporan, hingga penilaian akhir oleh admin.

### Problem yang Diselesaikan

| Masalah | Solusi |
|---|---|
| Pencatatan absensi manual yang tidak terstruktur | Sistem absensi digital dengan mode WFO/WFA |
| Laporan harian peserta tidak terdokumentasi | Pengumpulan laporan PDF dengan approval workflow |
| Tidak ada mekanisme penilaian terstandar | Modul penilaian multi-kriteria oleh admin |
| File laporan dapat diakses publik | Penyimpanan pada private disk dengan gate authorization |
| Tidak ada jalur komunikasi peserta–admin | Modul feedback dua arah dengan rating |

### High-Level Architecture Summary

Aplikasi ini adalah **monolith MVC** menggunakan Laravel 12 dengan server-side rendering via Blade. Frontend dibangun dengan Tailwind CSS + Alpine.js, dikompilasi menggunakan Vite. Session disimpan di database. Queue listener berjalan sebagai proses terpisah untuk menangani pengiriman email OTP secara asynchronous.

---

## 2. Architecture Diagram (Textual)

```
Browser (User)
    │
    ▼
[Web Server: php artisan serve / Nginx]
    │
    ├─► [Route: web.php]
    │       │
    │       ├─► [Middleware: auth]
    │       ├─► [Middleware: role:admin | role:peserta]
    │       └─► [Throttle: 5 req/min (OTP endpoints)]
    │
    ▼
[Controller Layer]
    ├── Admin/  (Dashboard, Peserta, Absensi, Laporan, Penilaian, Partner, ...)
    ├── Peserta/ (Dashboard, Profil, Absensi, Laporan, Feedback, Settings, ...)
    └── Auth/   (Login, Register, OTP, Forgot Password)
    │
    ▼
[Service & Model Layer]
    ├── Eloquent ORM → SQLite (dev) / MySQL (production)
    ├── Storage::disk('secure') → storage/app/secure/ [PDF laporan]
    ├── Storage::disk('public') → storage/app/public/ [foto profil]
    └── Mail → SMTP (OTP emails via Queue)
    │
    ▼
[Queue Worker: php artisan queue:listen]
    └── Dispatch: OtpMail, ResetPasswordOtpMail
```

---

## 3. Tech Stack

| Layer          | Technology                          | Purpose                                                  |
|----------------|-------------------------------------|----------------------------------------------------------|
| Frontend       | Blade Templates + Tailwind CSS v3   | Server-side rendering, UI styling                        |
| JS Framework   | Alpine.js v3                        | Reactive UI components (modal, toggle, form)             |
| Charts         | Chart.js v4                         | Visualisasi data pada dashboard admin                    |
| Build Tool     | Vite v7 + laravel-vite-plugin       | Asset bundling & HMR development                         |
| Backend        | Laravel 12 (PHP 8.2)                | MVC framework, routing, middleware, ORM                  |
| Auth           | Laravel built-in Auth + OTP via Mail| Session-based auth + OTP email verification              |
| Database       | SQLite (dev) / MySQL (production)   | Penyimpanan data utama                                   |
| Cache          | Database driver                     | Cache store menggunakan tabel `cache`                    |
| Session        | Database driver                     | Session disimpan di tabel `sessions`                     |
| Queue          | Database driver                     | Antrian email OTP (tabel `jobs`)                         |
| File Storage   | Laravel Storage (local disk)        | `secure` disk untuk PDF laporan (private), `public` disk untuk foto |
| Export         | PhpSpreadsheet v5                   | Export data ke format Excel/CSV                          |
| HTTP Client    | Axios v1                            | Request AJAX dari frontend (cek username, OTP)           |

---

## 4. Application Flow

### 4.1 Authentication Flow

#### Registrasi (OTP-Verified)
1. **User** mengisi form registrasi (username, email, password).
2. Frontend memvalidasi ketersediaan username/email secara real-time via AJAX ke `/check-username` dan `/check-email-availability`.
3. User klik **Kirim OTP** → `POST /send-otp` → throttle `5 req/menit`.
4. `AuthController@sendOtp` mem-validasi email unik dan username unik, lalu generate OTP 6 digit, simpan ke tabel `otp_codes` (TTL 5 menit), dispatch `OtpMail` via Queue.
5. User memasukkan OTP → `POST /verify-otp` → `AuthController@verifyOtp` mencocokkan kode dan TTL, set `session('otp_verified_email')`, hapus record OTP dari DB.
6. `POST /register` → `AuthController@register` memvalidasi `session('otp_verified_email') === email`, buat `User` + `Peserta` dalam satu DB transaction, redirect ke halaman login.

#### Login
1. `POST /login` → `AuthController@login`.
2. Deteksi input: jika berbentuk email → auth by `email`, selain itu auth by `username`.
3. `Auth::attempt([..., 'password' => ...], $remember)` → on success: `session()->regenerate()`.
4. Redirect berdasarkan `user->role`: `admin` → `/admin/dashboard`, `peserta` → `/peserta/dashboard`.
5. Setiap request dilindungi `middleware('auth')` yang mengandalkan Laravel's default `Authenticate` middleware.

#### Password Reset
1. `GET /forgot-password` → input email.
2. `POST /forgot-password` → `sendForgotPasswordOtp` → kirim OTP ke email jika terdaftar (respons selalu 200 untuk menghindari user enumeration).
3. `POST /verify-reset-otp` → verifikasi OTP, set `session('reset_verified' => true)`.
4. `POST /reset-password` → validasi sesi aktif + `reset_verified`, hash password baru, hapus OTP dari DB.
5. Admin account **tidak dapat** mereset password melalui fitur ini (proteksi eksplisit di controller).

#### Session & Middleware
- **`RoleMiddleware`**: Middleware kustom yang membaca `Auth::user()->role` dan mengembalikan `403` jika role tidak sesuai.
- Session lifetime default: **120 menit** (dapat dikonfigurasi via `SESSION_LIFETIME`).

---

### 4.2 Core Business Logic Flow

Contoh: Peserta mengumpulkan laporan harian.

```
1. Peserta → Browser → GET /peserta/laporan
2. Route → middleware(['auth', 'role:peserta'])
3. Peserta\LaporanController@index
4.   → Laporan::where('peserta_id', auth()->user()->peserta->id)
        ->orderByDesc('tanggal_laporan')->paginate(10)
5.   → return view('peserta.laporan.laporan', compact('laporans'))

6. Peserta isi form → POST /peserta/laporan
7. Request validation:
        judul: required|string|max:255
        deskripsi: required|string
        tanggal_laporan: required|date
        file: required|file|mimes:pdf|max:10240
8. Controller@store:
   a. Cek duplikasi tanggal (unique: peserta_id + tanggal_laporan)
   b. Store file ke Storage::disk('secure') di path "harian/{filename}"
   c. Laporan::create([...file_path, status='Dikirim'])
   d. Redirect back dengan flash success

9. Admin → GET /admin/laporan → AdminLaporanController@index
10.   → Laporan::with('peserta.user')->latest()->paginate()
11. Admin buka detail → GET /admin/laporan/{id}
12. Admin approve → PATCH /admin/laporan/{id}/approve
13.   → Laporan::find($id)->update(['status' => 'Disetujui'])
14. Admin revisi → PATCH /admin/laporan/{id}/revisi
15.   → Update: status='Revisi', catatan_admin='{pesan revisi}'
16. Peserta reload halaman → status card diperbarui
```

---

## 5. Feature Breakdown

### Registrasi & Autentikasi
- **Deskripsi**: Registrasi mandiri peserta dengan verifikasi email via OTP. Login menggunakan username atau email.
- **User Role Access**: Public (registrasi), Peserta & Admin (login).
- **Endpoints**: `POST /send-otp`, `POST /verify-otp`, `POST /register`, `POST /login`, `POST /logout`, `POST /forgot-password`, `POST /verify-reset-otp`, `POST /reset-password`.
- **Validation Rules**: Email unik di tabel `user`, username unik min. 3 karakter, password minimal 6 karakter dengan konfirmasi, OTP 6 digit.
- **Side Effects**: Dispatch `OtpMail` / `ResetPasswordOtpMail` via Queue. Log aktivitas dan error ke Laravel log.
- **Database impact**: Insert ke `user`, `peserta` (dalam transaksi), `otp_codes` (upsert). Hapus `otp_codes` setelah verifikasi.

---

### Manajemen Profil Peserta
- **Deskripsi**: Peserta melengkapi dan memperbarui data profil (nama, asal sekolah/universitas, jurusan, alamat, nomor telepon, foto, jenis kegiatan, tanggal mulai/selesai).
- **User Role Access**: Peserta (self), Admin (read-only via user management).
- **Endpoints**: `GET /peserta/profil`, `POST /peserta/profil`.
- **Validation Rules**: `foto` menerima `image|max:2048`, field wajib minimal tidak kosong atau bernilai `-`.
- **Side Effects**: Upload foto ke `storage/app/public/`. Accessor `is_lengkap` dihitung otomatis.
- **Database impact**: Update tabel `peserta` berdasarkan `user_id`.

---

### Absensi Harian
- **Deskripsi**: Peserta mencatat kehadiran harian dengan jenis absen (Masuk/Pulang), mode kerja (WFO/WFA), dan status (Hadir/Izin/Sakit). Admin dapat melihat rekap absensi seluruh peserta dengan filter tanggal.
- **User Role Access**: Peserta (input), Admin (read-only).
- **Endpoints**: `GET /peserta/absensi`, `POST /peserta/absensi` (peserta), `GET /admin/absensi` (admin).
- **Validation Rules**: `jenis_absen` enum, `status` enum, `mode_kerja` enum. Unique constraint pada `(peserta_id, jenis_absen, waktu_absen)`.
- **Side Effects**: Tidak ada event/queue. `waktu_absen` diisi otomatis oleh server.
- **Database impact**: Insert ke tabel `absensi`. Index pada `peserta_id` dan `waktu_absen`.

---

### Laporan Harian
- **Deskripsi**: Peserta mengumpulkan laporan harian dalam format PDF dengan judul, deskripsi, dan tanggal. Admin melakukan review dan dapat menyetujui atau mengembalikan untuk revisi.
- **User Role Access**: Peserta (CRUD own), Admin (read, approve, revisi).
- **Endpoints**:
  - `GET /peserta/laporan` — daftar laporan peserta (paginated)
  - `POST /peserta/laporan` — submit laporan baru
  - `GET /peserta/laporan/{id}` — detail laporan
  - `GET /peserta/laporan/{id}/edit` — form edit
  - `PUT /peserta/laporan/{id}` — update laporan
  - `DELETE /peserta/laporan/{id}` — hapus laporan
  - `GET /admin/laporan` — daftar semua laporan
  - `PATCH /admin/laporan/{id}/approve` — setujui
  - `PATCH /admin/laporan/{id}/revisi` — kembalikan revisi
- **Validation Rules**: `file` wajib ada, hanya `pdf`, maks 10 MB. `tanggal_laporan` unik per peserta.
- **Side Effects**: File disimpan ke `Storage::disk('secure')` (private). Akses file via `FileController@showReport` dengan gate auth.
- **Database impact**: Insert/update tabel `laporan`. Unique index `(peserta_id, tanggal_laporan)`.

---

### Laporan Akhir
- **Deskripsi**: Setiap peserta hanya dapat mengumpulkan **satu** laporan akhir dalam format PDF. Admin melakukan approval atau request revisi.
- **User Role Access**: Peserta (create & update own), Admin (read, approve, revisi).
- **Endpoints**:
  - `GET /peserta/laporan/laporan-akhir` — form & status
  - `POST /peserta/laporan/laporan-akhir` — submit pertama
  - `GET /peserta/laporan/laporan-akhir/{id}` — detail
  - `PUT /peserta/laporan/laporan-akhir/{id}` — update (jika revisi)
  - `GET /admin/laporan/laporan-akhir` — list semua
  - `PATCH /admin/laporan/laporan-akhir/{id}/approve` — setujui
  - `PATCH /admin/laporan/laporan-akhir/{id}/revisi` — revisi
- **Validation Rules**: `file` wajib ada, hanya `pdf`, maks 10 MB. Unique `peserta_id` di tabel `laporan_akhir`.
- **Side Effects**: File disimpan ke `Storage::disk('secure')`.
- **Database impact**: Insert/update tabel `laporan_akhir`. Unique index pada `peserta_id`.

---

### Penilaian
- **Deskripsi**: Admin menilai peserta berdasarkan 5 kriteria: kedisiplinan, keterampilan, kerjasama, inisiatif, dan komunikasi. `nilai_akhir` dihitung sebagai rata-rata. Peserta dapat melihat nilai mereka sendiri.
- **User Role Access**: Admin (CRUD), Peserta (read own).
- **Endpoints**:
  - `GET /admin/penilaian` — list peserta (grid)
  - `GET /admin/penilaian/{id}` — detail
  - `POST /admin/penilaian` — buat penilaian
  - `PUT /admin/penilaian/{id}` — update penilaian
  - `GET /peserta/penilaian` — view nilai sendiri
- **Validation Rules**: Setiap kriteria bernilai integer 1–100 (`unsignedTinyInteger`).
- **Side Effects**: Tidak ada.
- **Database impact**: Insert/update tabel `penilaian`. Relasi ke `peserta` dan `user` (admin yang menilai).

---

### Feedback
- **Deskripsi**: Komunikasi dua arah antara peserta dan admin. Peserta dapat memberi feedback dan rating. Admin dapat membalas. Setiap feedback memiliki flag `tampilkan` (publish) dan `dibaca`.
- **User Role Access**: Peserta (create & view own), Admin (read all, reply).
- **Endpoints**: `GET /peserta/feedback`, `POST /peserta/feedback`.
- **Validation Rules**: `pesan` required, `rating` optional integer 1–5.
- **Side Effects**: Tidak ada queue/event.
- **Database impact**: Insert ke tabel `feedback`. `dibaca` dan `tampilkan` di-update oleh admin.

---

### Manajemen Peserta (Admin)
- **Deskripsi**: Admin dapat membuat, mengedit, melihat detail, dan menonaktifkan akun peserta. Termasuk filter berdasarkan status dan kelengkapan profil.
- **User Role Access**: Admin only.
- **Endpoints**: `GET|POST|PUT|DELETE /admin/peserta/{...}` (Laravel resource controller).
- **Validation Rules**: Field wajib pada form create/edit.
- **Side Effects**: Pembuatan peserta oleh admin juga membuat akun `user` secara bersamaan.
- **Database impact**: Insert/update tabel `user` dan `peserta`.

---

### Manajemen Partner
- **Deskripsi**: Admin mengelola daftar mitra/perusahaan yang ditampilkan di halaman landing page.
- **User Role Access**: Admin only.
- **Endpoints**: `GET|POST|PUT|DELETE /admin/partners/{...}` (Laravel resource controller).
- **Database impact**: Insert/update/delete tabel `partners`.

---

### Akses File Laporan (Secure)
- **Deskripsi**: File PDF laporan disimpan di luar `public/` pada disk `secure`. Akses hanya melalui `FileController` yang memverifikasi autentikasi dan otorisasi kepemilikan file.
- **User Role Access**: Auth required (admin dan peserta dengan kepemilikan yang sesuai).
- **Endpoint**: `GET /files/reports/{type}/{filename}`.
- **Side Effects**: Tidak ada write. Return `file()` response dari path private.
- **Database impact**: Tidak ada write ke DB, hanya query model untuk otorisasi.

---

## 6. Database Schema Overview

### Tables

| Tabel          | Deskripsi                                                                  |
|----------------|----------------------------------------------------------------------------|
| `user`         | Akun autentikasi (username, email, password, role: admin/peserta)          |
| `peserta`      | Profil peserta (nama, sekolah, jurusan, status, jenis kegiatan, tanggal)   |
| `absensi`      | Rekaman absensi harian (jenis, mode kerja, status, waktu)                  |
| `laporan`      | Laporan harian peserta (judul, deskripsi, file PDF, status, catatan admin) |
| `laporan_akhir`| Laporan akhir peserta — satu per peserta (judul, file PDF, status)         |
| `penilaian`    | Nilai peserta 5 kriteria + nilai_akhir (oleh admin)                        |
| `feedback`     | Pesan feedback dua arah peserta–admin (pesan, rating, dibaca, tampilkan)   |
| `arsip`        | Arsip data peserta setelah selesai                                         |
| `otp_codes`    | Kode OTP sementara untuk registrasi & reset password (TTL 5 menit)         |
| `partners`     | Data mitra/perusahaan untuk landing page                                   |
| `log_aktivitas`| Log aktivitas sistem                                                       |
| `cache`        | Laravel cache store (database driver)                                      |
| `sessions`     | Laravel session store (database driver)                                    |
| `jobs`         | Laravel queue jobs (database driver)                                       |

### Relasi Antar Tabel

```
user (1) ──────────── (1) peserta
                            │
                ┌───────────┼────────────────┐───────────────┐
               (N)         (N)              (N)             (1)
            absensi      laporan          feedback       penilaian
                            │                             │
                           (1)                          (1)
                        laporan_akhir                  user (admin penilai)
```

| Relasi | Keterangan |
|---|---|
| `user` (1) → (1) `peserta` | `peserta.user_id` FK ke `user.id`, cascade delete |
| `peserta` (1) → (N) `absensi` | `absensi.peserta_id` FK ke `peserta.id` |
| `peserta` (1) → (N) `laporan` | `laporan.peserta_id` FK ke `peserta.id` |
| `peserta` (1) → (1) `laporan_akhir` | Unique constraint pada `peserta_id` |
| `peserta` (1) → (N) `feedback` | `feedback.peserta_id` FK ke `peserta.id` |
| `peserta` (1) → (1) `penilaian` | Dinilai oleh admin (`user_id` → user admin) |
| `peserta` (1) → (1) `arsip` | Data arsip setelah selesai |

---

## 7. Folder Structure

```
SimpanData/
├── app/
│   ├── Exports/               # PhpSpreadsheet export classes
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller untuk seluruh fitur admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PesertaController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── LaporanController.php
│   │   │   │   ├── PenilaianController.php
│   │   │   │   ├── PartnerController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   └── SettingsController.php
│   │   │   ├── Peserta/       # Controller untuk seluruh fitur peserta
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProfilController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── LaporanController.php
│   │   │   │   ├── PenilaianController.php
│   │   │   │   ├── FeedbackController.php
│   │   │   │   └── SettingsController.php
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php  # Login, register, OTP, reset password
│   │   │   ├── FileController.php      # Serve file PDF laporan (private)
│   │   │   └── IndexController.php     # Landing page
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php      # Cek role: admin | peserta
│   │   └── Requests/                   # Form request validation classes
│   ├── Mail/
│   │   ├── OtpMail.php                 # Mailable untuk OTP registrasi
│   │   └── ResetPasswordOtpMail.php    # Mailable untuk OTP reset password
│   ├── Models/
│   │   ├── User.php
│   │   ├── Peserta.php
│   │   ├── Absensi.php
│   │   ├── Laporan.php
│   │   ├── LaporanAkhir.php
│   │   ├── Penilaian.php
│   │   ├── Feedback.php
│   │   ├── Arsip.php
│   │   ├── OtpCode.php
│   │   ├── Partner.php
│   │   └── LogAktivitas.php
│   ├── Providers/
│   └── Services/                       # Business logic layer (jika diperlukan)
├── database/
│   ├── factories/                      # Model factories untuk seeding & testing
│   ├── migrations/                     # 14 migration files
│   └── seeders/                        # DatabaseSeeder, IpinSeeder, dst.
├── public/                             # Web root (index.php, compiled assets)
├── resources/
│   ├── css/                            # CSS source (Tailwind directives)
│   ├── js/                             # Alpine.js, Chart.js entry points
│   └── views/
│       ├── admin/                      # Blade views untuk panel admin
│       ├── peserta/                    # Blade views untuk panel peserta
│       ├── auth/                       # Login, register, OTP, reset password views
│       ├── legal/                      # Privacy policy, ToS, Help
│       └── layouts/                    # Layout utama (app.blade.php)
├── routes/
│   ├── web.php                         # Semua route aplikasi
│   └── console.php                     # Artisan scheduled commands
├── storage/
│   ├── app/
│   │   ├── public/                     # File foto profil (accessible via symlink)
│   │   └── secure/                     # File PDF laporan (PRIVATE, tidak diakses langsung)
│   └── logs/                           # Laravel log files
├── tests/
│   ├── Feature/                        # Feature tests (HTTP, flow, integration)
│   └── Unit/                           # Unit tests (model, service logic)
├── .env.example                        # Template environment variables
├── composer.json                       # PHP dependencies
├── package.json                        # Node.js dependencies
├── tailwind.config.js                  # Tailwind CSS konfigurasi
└── vite.config.js                      # Vite build konfigurasi
```

**Tanggung jawab folder:**

| Folder | Tanggung Jawab |
|---|---|
| `app/Http/Controllers/Admin/` | Handle request HTTP untuk semua fitur yang hanya dapat diakses admin |
| `app/Http/Controllers/Peserta/` | Handle request HTTP untuk semua fitur yang diakses peserta |
| `app/Http/Controllers/Auth/` | Alur autentikasi lengkap (login/register/OTP/reset) |
| `app/Http/Middleware/` | Guard tambahan berbasis role setelah auth middleware |
| `app/Models/` | Eloquent models, relasi, scope query, dan accessor |
| `app/Mail/` | Mailable classes untuk pengiriman email OTP via queue |
| `app/Exports/` | Logic export data ke format Excel menggunakan PhpSpreadsheet |
| `app/Services/` | Business logic yang tidak terikat ke layer HTTP |
| `database/migrations/` | Definisi skema database setiap tabel |
| `resources/views/` | Blade templates untuk rendering HTML |
| `storage/app/secure/` | File PDF laporan yang harus diakses melalui controller (private) |

---

## 8. Environment Configuration

### File `.env` Minimal

```env
# Aplikasi
APP_NAME=SimpanData
APP_ENV=local
APP_KEY=                          # Generate dengan: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite              # Ganti ke mysql untuk production
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=simpandata
# DB_USERNAME=root
# DB_PASSWORD=secret

# Session & Cache (database driver)
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Queue (database driver)
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=local

# Email (untuk OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io        # Ganti dengan SMTP provider produksi
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="no-reply@simpandata.id"
MAIL_FROM_NAME="SimpanData"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug                   # Ganti ke 'error' di production
```

### Penjelasan Variable Penting

| Variable | Fungsi |
|---|---|
| `APP_KEY` | Kunci enkripsi untuk session, cookie, dan kriptografi Laravel. Wajib di-generate sekali. |
| `APP_ENV` | Menentukan mode aplikasi. `production` menonaktifkan stack trace dan error page detail. |
| `APP_DEBUG` | Jika `true`, exception ditampilkan di browser. Wajib `false` di production. |
| `DB_CONNECTION` | Driver database: `sqlite` (dev), `mysql` / `pgsql` (prod). |
| `SESSION_DRIVER=database` | Session disimpan di tabel `sessions`. Mendukung multi-server. |
| `QUEUE_CONNECTION=database` | Queue jobs (email OTP) disimpan di tabel `jobs`. Proses via queue listener. |
| `MAIL_MAILER` | Driver email. Gunakan `log` untuk dev (tulis ke log), `smtp` untuk prod. |
| `FILESYSTEM_DISK=local` | Default disk untuk file upload. Disk `secure` dikonfigurasi terpisah di `config/filesystems.php`. |
| `BCRYPT_ROUNDS` | Jumlah round hashing bcrypt. Default `12`, naikkan ke `13`–`14` untuk prod jika perlu. |
| `LOG_LEVEL` | Level minimal log yang dicatat. `debug` di dev, `warning`/`error` di prod. |

---

## 9. Installation & Setup

### Prerequisites

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 20.x + npm
- SQLite (dev) atau MySQL 8.x (prod)
- Ekstensi PHP: `pdo_sqlite` / `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `bcmath`

### Step-by-Step Setup

**1. Clone repository**
```bash
git clone https://github.com/your-org/SimpanData.git
cd SimpanData
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node.js dependencies**
```bash
npm install
```

**4. Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` sesuai konfigurasi lokal Anda (database, mail, dst.).

**5. Buat storage symlink (untuk foto profil public)**
```bash
php artisan storage:link
```

**6. Jalankan database migration**
```bash
# SQLite: pastikan file database ada terlebih dahulu
touch database/database.sqlite

php artisan migrate
```

**7. (Opsional) Jalankan seeder untuk data dummy**
```bash
php artisan db:seed
```

**8. Build frontend assets**
```bash
npm run build
```

**9. Jalankan server development (semua proses sekaligus)**
```bash
composer run dev
```
Perintah ini menjalankan secara bersamaan:
- `php artisan serve` — web server
- `php artisan queue:listen` — worker untuk email OTP
- `php artisan pail` — log viewer
- `npm run dev` — Vite HMR

Server akan berjalan di `http://localhost:8000`.

**10. Jalankan tests**
```bash
composer run test
# atau
php artisan test
```

---

## 10. Security Considerations

### Authentication
- Autentikasi menggunakan **Laravel built-in Auth** (`Auth::attempt`) dengan password di-hash menggunakan **bcrypt** (default 12 rounds).
- Login mendukung identifier ganda: email atau username.
- Registrasi dilindungi **OTP email verification** — akun hanya dibuat jika OTP diverifikasi terlebih dahulu.

### Authorization
- **Role-Based Access Control (RBAC)** menggunakan `RoleMiddleware` kustom yang diterapkan per route group.
- Dua role: `admin` dan `peserta`. Setiap endpoint secara eksplisit memproteksi dengan `middleware('role:admin')` atau `middleware('role:peserta')`.
- File laporan PDF dilindungi via `FileController` — tidak ada path publik yang mengarah langsung ke file.

### Input Validation
- Semua form request divalidasi di sisi server menggunakan `$request->validate()` atau Form Request classes (`app/Http/Requests/`).
- Upload file laporan dibatasi hanya tipe `pdf` dan maksimal **10 MB**.
- Upload foto profil dibatasi tipe `image` dan maksimal **2 MB**.

### CSRF & XSS
- **CSRF**: Semua form POST/PUT/PATCH/DELETE dilindungi Laravel's built-in CSRF protection (`@csrf` directive di Blade).
- **XSS**: Output di Blade menggunakan `{{ }}` (HTML escaped by default). Hindari penggunaan `{!! !!}` kecuali pada konten yang sudah disanitasi.

### Rate Limiting
- Endpoint sensitif (OTP, login check) dibatasi dengan `throttle:5,1` (5 request per menit per IP).
- Proteksi terhadap **user enumeration** pada endpoint forgot password: respons selalu `200 OK` terlepas dari keberadaan email.

### Penyimpanan File
- File PDF laporan disimpan di luar web root (`storage/app/secure/`) — tidak dapat diakses langsung via URL.
- Akses file melalui `FileController@showReport` yang memvalidasi autentikasi sebelum streaming file.

---

## 11. Scalability Notes

### Bottleneck yang Mungkin Terjadi

| Komponen | Potensi Bottleneck | Catatan |
|---|---|---|
| Queue Worker | Database queue tidak efisien untuk volume tinggi | Migrasi ke Redis queue untuk load besar |
| Session | Database session bisa lambat jika concurrent users tinggi | Migrasi ke Redis session driver |
| Cache | Database cache kurang efisien untuk hot data | Migrasi ke Redis atau Memcached |
| File Serving | `FileController` stream file = I/O intensive | Gunakan CDN atau Nginx X-Accel-Redirect |
| Export Excel | PhpSpreadsheet berjalan synchronous | Pindahkan ke queue job dengan notifikasi selesai |

### Caching Strategy
- Saat ini menggunakan **database cache driver** — cocok untuk deployment awal.
- Untuk scale-up: konfigurasi `CACHE_STORE=redis` dan tambah koneksi Redis.
- Data yang layak di-cache: list partner (landing page), statistik dashboard, data profil yang jarang berubah.

### Horizontal Scaling
- Aplikasi **stateless di level kode** jika `SESSION_DRIVER` dan `QUEUE_CONNECTION` dimigrasi ke Redis (shared state antar server).
- File storage harus menggunakan **shared filesystem** (NFS) atau **object storage** (S3-compatible) jika deploy multi-server.
- Database cache dan session wajib dipindah ke Redis sebelum deploy ke multiple app server.

### Queue Usage
- Queue saat ini digunakan untuk pengiriman email OTP (registrasi & reset password).
- Worker berjalan via `php artisan queue:listen`. Di production, gunakan `Supervisor` untuk menjaga proses worker tetap berjalan.
- Tambahkan queue untuk proses berat lainnya: export Excel, pengiriman notifikasi massal.

---

## 12. Testing Strategy

### Struktur Test

```
tests/
├── Feature/      # Test HTTP end-to-end (request → response)
└── Unit/         # Test logika terisolasi (model, service, helper)
```

### Unit Tests

Fokus pada:
- Model accessor & scope (`Peserta@getIsLengkapAttribute`, `scopeTerisi`, `scopeBelumTerisi`)
- `OtpCode@isExpired()` — validasi TTL OTP
- Kalkulasi `nilai_akhir` di model `Penilaian`
- `Laporan@getSecureUrlAttribute` — pembentukan URL aman

```bash
php artisan test --testsuite=Unit
```

### Feature Tests

Fokus pada:
- Alur registrasi + OTP verification (happy path & edge case: OTP expired, OTP salah)
- Alur login dengan username/email + redirect berdasarkan role
- Submit laporan harian: validation error, duplicate date, file type enforcement
- Admin approve/revisi laporan: perubahan status & catatan
- Akses file via `FileController`: auth required, unauthorized access (403)
- Rate limiting pada endpoint OTP

```bash
php artisan test --testsuite=Feature
```

### Integration Tests

- Pastikan queue job `OtpMail` terkirim saat register (gunakan `Mail::fake()`)
- Test database transaction pada `AuthController@register` — rollback jika `Peserta::create` gagal
- End-to-end flow penilaian oleh admin dapat dibaca oleh peserta

```bash
php artisan test
```

### Coverage Target

| Area | Target Coverage |
|---|---|
| Auth flow (register, login, OTP, reset) | ≥ 90% |
| Laporan submit & approval workflow | ≥ 85% |
| Model accessor & scope | ≥ 95% |
| File access authorization | ≥ 90% |
| Overall | ≥ 80% |

Jalankan report coverage (membutuhkan Xdebug):
```bash
php artisan test --coverage --min=80
```

---

## Appendix

### Artisan Commands Berguna

```bash
# Development server (all-in-one)
composer run dev

# Jalankan queue worker manual
php artisan queue:listen --tries=3

# Clear semua cache
php artisan optimize:clear

# Lihat semua route terdaftar
php artisan route:list

# Fresh migration + seed
php artisan migrate:fresh --seed

# Jalankan test suite
php artisan test --parallel
```

### Konvensi Kode

- **Controller naming**: `{NamespaceArea}{Feature}Controller` — e.g., `Admin\LaporanController`, `Peserta\LaporanController`.
- **Route naming**: `{area}.{resource}.{action}` — e.g., `admin.laporan.harian.approve`, `peserta.laporan.store`.
- **Status enum laporan**: `Draft` → `Dikirim` → `Disetujui` / `Revisi` (peserta dapat upload ulang).
- **Status enum peserta**: `Aktif` → `Selesai` → `Arsip`.
