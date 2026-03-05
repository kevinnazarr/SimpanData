<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PesertaDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $totalData = 300;
        $magangCount = (int) ($totalData * 0.70); // 210
        $pklCount = $totalData - $magangCount; // 90

        $insertedPesertas = 0;

        $this->command->info("Membuat $totalData data peserta ($magangCount Magang, $pklCount PKL)...");

        for ($i = 0; $i < $totalData; $i++) {
            $jenisKegiatan = $i < $magangCount ? 'Magang' : 'PKL';

            // Menentukan Tanggal Mulai dan Selesai Berdasarkan Jenis Kegiatan
            if ($jenisKegiatan === 'Magang') {
                $startMin = Carbon::create(2025, 7, 1);
                $startMax = Carbon::create(2025, 10, 31);
                $tanggalMulai = Carbon::instance($faker->dateTimeBetween($startMin, $startMax));
                $durasiBulan = $faker->numberBetween(3, 9);
                $tanggalSelesai = (clone $tanggalMulai)->addMonths($durasiBulan);
            } else {
                $startMin = Carbon::create(2025, 1, 1);
                $startMax = Carbon::create(2025, 4, 30);
                $tanggalMulai = Carbon::instance($faker->dateTimeBetween($startMin, $startMax));
                $durasiBulan = $faker->numberBetween(4, 6);
                $tanggalSelesai = (clone $tanggalMulai)->addMonths($durasiBulan);
            }

            // Menentukan Status Peserta
            $now = Carbon::now();
            if ($now->greaterThan($tanggalSelesai)) {
                $status = $faker->randomElement(['Selesai', 'Arsip']);
            } elseif ($now->lessThan($tanggalMulai)) {
                $status = 'Aktif';
            } else {
                $status = 'Aktif'; // Sedang berjalan
            }

            // --- USER ---
            $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $faker->unique()->userName)) . $faker->numberBetween(100, 9999);
            $userId = DB::table('user')->insertGetId([
                'username' => substr($username, 0, 191),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- PESERTA ---
            $univList = ['Universitas Gadjah Mada', 'Universitas Negeri Yogyakarta', 'Universitas Islam Indonesia', 'Universitas Atma Jaya Yogyakarta', 'Universitas Muhammadiyah Yogyakarta', 'Universitas Amikom Yogyakarta', 'UPN Veteran Yogyakarta', 'Universitas Sanata Dharma', 'Institut Seni Indonesia Yogyakarta', 'Universitas Teknologi Yogyakarta', 'Universitas Ahmad Dahlan', 'Universitas Mercu Buana Yogyakarta', 'Universitas Kristen Duta Wacana'];
            $smkList = ['SMKN 2 Depok Sleman', 'SMKN 2 Yogyakarta', 'SMKN 3 Yogyakarta', 'SMKN 1 Bantul', 'SMK Telkom Sandhy Putra Pusat', 'SMK N 1 Wonosari', 'SMK N 1 Pengasih', 'SMK N 1 Depok', 'SMK Pelita Harapan', 'SMKN 1 Kasihan', 'SMKN 1 Tempel'];
            
            $namaSekolah = $jenisKegiatan === 'Magang' 
                ? $faker->randomElement($univList)
                : $faker->randomElement($smkList);

            $jurusanList = $jenisKegiatan === 'Magang'
                ? ['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer', 'Manajemen', 'Akuntansi', 'Ilmu Komunikasi', 'Desain Komunikasi Visual']
                : ['Rekayasa Perangkat Lunak', 'Teknik Komputer dan Jaringan', 'Multimedia', 'Otomatisasi Tata Kelola Perkantoran', 'Akuntansi', 'Teknik Mesin'];
            
            $pesertaId = DB::table('peserta')->insertGetId([
                'user_id' => $userId,
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'asal_sekolah_universitas' => $namaSekolah,
                'jurusan' => $faker->randomElement($jurusanList),
                'alamat' => $faker->address,
                'no_telepon' => substr($faker->unique()->e164PhoneNumber, 0, 20),
                'jenis_kegiatan' => $jenisKegiatan,
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $insertedPesertas++;

            // --- AKTIVITAS: ABSENSI, LAPORAN, LOG ---
            // Hanya buat data absensi jika peserta sudah mulai
            if ($now->greaterThan($tanggalMulai)) {
                $absenEnd = $now->lessThan($tanggalSelesai) ? $now : clone $tanggalSelesai;
                $absenStart = clone $tanggalMulai;
                
                $absensiData = [];
                $laporanData = [];
                $logAktivitasData = [];
                
                $daysToGenerate = min($absenStart->diffInDays($absenEnd), 25); 
                $generatedDates = []; 
                $datesToProcess = [];

                // Pastikan ada absensi untuk HARI INI jika statusnya masih aktif
                if ($now->between($absenStart, $absenEnd)) {
                    $datesToProcess[] = clone $now;
                }

                if ($daysToGenerate > 0) {
                    for ($d = 0; $d < $daysToGenerate; $d++) {
                        $randomDays = $faker->numberBetween(0, max(0, $absenStart->diffInDays($absenEnd)));
                        $datesToProcess[] = (clone $absenStart)->addDays($randomDays);
                    }
                } elseif (empty($datesToProcess)) {
                    $datesToProcess[] = clone $absenStart; // Guaranty at least one day
                }

                foreach ($datesToProcess as $currentDate) {
                    $dateString = $currentDate->format('Y-m-d');
                    
                    if (in_array($dateString, $generatedDates)) {
                        continue;
                    }
                    $generatedDates[] = $dateString;
                    
                    $statusAbsen = $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Sakit', 'Izin']);
                    
                    $waktuMasuk = $currentDate->copy()->setTime($faker->numberBetween(7, 9), $faker->numberBetween(0, 59), 0);
                    
                    $absensiData[] = [
                        'peserta_id' => $pesertaId,
                        'jenis_absen' => 'Masuk',
                        'waktu_absen' => $waktuMasuk->format('Y-m-d H:i:s'),
                        'mode_kerja' => $statusAbsen === 'Hadir' ? $faker->randomElement(['WFO', 'WFA']) : null,
                        'status' => $statusAbsen,
                        'wa_pengirim' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if ($statusAbsen === 'Hadir' && $faker->boolean(85)) {
                        $waktuPulang = $currentDate->copy()->setTime($faker->numberBetween(16, 18), $faker->numberBetween(0, 59), 0);
                        $absensiData[] = [
                            'peserta_id' => $pesertaId,
                            'jenis_absen' => 'Pulang',
                            'waktu_absen' => $waktuPulang->format('Y-m-d H:i:s'),
                            'mode_kerja' => null,
                            'status' => 'Hadir',
                            'wa_pengirim' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if ($statusAbsen === 'Hadir' && $faker->boolean(75)) { 
                        $judulLaporan = $faker->randomElement([
                            'Mengerjakan fitur ' . $faker->word,
                            'Fixing bug pada modul ' . $faker->word,
                            'Rapat evaluasi mingguan',
                            'Testing aplikasi',
                            'Membuat dokumentasi sistem',
                            'Desain UI/UX halaman',
                            'Setup server dan database',
                            'Belajar teknologi baru',
                            'Membantu rekap data harian'
                        ]);
                        
                        $laporanData[] = [
                            'peserta_id' => $pesertaId,
                            'judul' => $judulLaporan,
                            'deskripsi' => "Hari ini saya " . strtolower($judulLaporan) . " dan berdiskusi dengan tim. Aktivitas berjalan lancar tanpa kendala berarti. " . $faker->sentence(),
                            'file_path' => null,
                            'tanggal_laporan' => $dateString,
                            'status' => $faker->randomElement(['Dikirim', 'Disetujui', 'Disetujui', 'Disetujui', 'Revisi']),
                            'catatan_admin' => $faker->boolean(15) ? 'Tolong perjelas detail kegiatannya.' : null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    
                    if ($faker->boolean(40) && count($logAktivitasData) < 4) {
                        $logAktivitasData[] = [
                            'user_id' => $userId,
                            'aksi' => 'Absensi ' . $statusAbsen,
                            'target_tabel' => 'absensi',
                            'target_id' => null, 
                            'deskripsi' => "User melakukan absensi $statusAbsen pada aplikasi",
                            'ip_address' => $faker->ipv4,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($absensiData)) {
                    DB::table('absensi')->insert($absensiData);
                }
                if (!empty($laporanData)) {
                    DB::table('laporan')->insert($laporanData);
                }
                if (!empty($logAktivitasData)) {
                    DB::table('log_aktivitas')->insert($logAktivitasData);
                }
            }

            // --- PENYELESAIAN ---
            if ($status === 'Selesai' || $status === 'Arsip') {
                DB::table('laporan_akhir')->insert([
                    'peserta_id' => $pesertaId,
                    'judul' => 'Laporan Akhir: Pengalaman ' . $jenisKegiatan . '',
                    'deskripsi' => $faker->paragraphs(3, true),
                    'file_path' => null,
                    'status' => $faker->randomElement(['Dikirim', 'Disetujui', 'Revisi']),
                    'catatan_admin' => $faker->boolean(30) ? 'Bagus, silakan perbaiki format selengkapnya.' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $feedbackVariations = [
                    "Selama menjalankan $jenisKegiatan, saya banyak mendapatkan pelajaran baru. Lingkungan kerja sangat suportif.",
                    "Pengalaman $jenisKegiatan di sini sangat berharga. Pembimbing sabar dan fasilitas memadai.",
                    "Sangat seru! Banyak wawasan praktis yang saya dapatkan selama $jenisKegiatan yang tidak diajarkan di kampus.",
                    "Terima kasih atas bimbingannya selama $jenisKegiatan. Timnya komunikatif dan sangat membantu proses belajar.",
                    "Banyak ilmu baru yang didapat, terutama terkait implementasi riil di industri. Lingkungan kerja asik banget!",
                    "Mantap! Pengalaman $jenisKegiatan di sini membuat skill teknis dan soft skill saya meningkat drastis.",
                    "Secara keseluruhan sangat memuaskan. Pembimbing sangat terbuka untuk diskusi dan memberikan arahan yang jelas.",
                    "Saya merasa sangat terbantu dengan adanya program $jenisKegiatan ini. Sangat direkomendasikan untuk pelajar lain.",
                    "Lingkungan kerja yang ramah dan profesional. Membuat saya betah dan semangat belajar hal baru setiap hari."
                ];

                DB::table('feedback')->insert([
                    'peserta_id' => $pesertaId,
                    'pengirim' => 'Peserta',
                    'pesan' => $faker->randomElement($feedbackVariations),
                    'tampilkan' => $faker->boolean(60),
                    'rating' => $faker->numberBetween(4, 5),
                    'dibaca' => $faker->boolean,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($status === 'Arsip') {
                    DB::table('arsip')->insert([
                        'peserta_id' => $pesertaId,
                        'file_path' => null,
                        'diarsipkan_pada' => $tanggalSelesai->format('Y-m-d'),
                        'catatan' => 'Peserta telah menyelesaikan kegiatan secara penuh.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($insertedPesertas % 50 === 0) {
                $this->command->info("$insertedPesertas data peserta telah diverifikasi & disisipkan...");
            }
        }

        $this->command->info("Seeder selesai! Total data dibuat: $insertedPesertas Data Realistis!");
    }
}
