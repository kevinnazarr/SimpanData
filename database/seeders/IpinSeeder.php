<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Absensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class IpinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'ipinipin@gmail.com'],
            [
                'username' => 'ipin',
                'password' => Hash::make('ipin 123'),
                'role' => 'peserta',
            ]
        );

        $peserta = Peserta::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => 'Ipin',
                'asal_sekolah_universitas' => 'Universitas Durian Runtuh',
                'jurusan' => 'Teknik Goreng Ayam',
                'alamat' => 'Kampung Durian Runtuh',
                'no_telepon' => '081234567899',
                'jenis_kegiatan' => 'Magang',
                'tanggal_mulai' => Carbon::now()->subMonths(6)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addMonths(6)->toDateString(),
                'status' => 'Aktif',
            ]
        );

        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            // Skip weekends (Saturday and Sunday)
            if ($date->isWeekend()) {
                continue;
            }

            // 90% chance of attendance for Ipin
            if (rand(1, 100) > 10) {
                $status = (rand(1, 100) > 90) ? fake()->randomElement(['Izin', 'Sakit']) : 'Hadir';

                // Randomize times slightly for realism
                // Masuk: 07:15 - 08:15
                $jamMasuk = $date->copy()->setTime(7, rand(15, 59));
                if (rand(1, 10) > 8) { // 20% chance of being slightly late
                    $jamMasuk = $date->copy()->setTime(8, rand(0, 15));
                }

                // Pulang: 16:30 - 18:00
                $jamPulang = $date->copy()->setTime(16, rand(30, 59));
                if (rand(1, 10) > 5) {
                    $jamPulang = $date->copy()->setTime(17, rand(0, 30));
                }

                // Determine Mode Kerja (mostly WFO)
                $modeKerja = (rand(1, 10) > 1) ? 'WFO' : 'WFA';

                // Create 'Masuk' record
                Absensi::firstOrCreate(
                    [
                        'peserta_id' => $peserta->id,
                        'jenis_absen' => 'Masuk',
                        'waktu_absen' => $jamMasuk,
                    ],
                    [
                        'mode_kerja' => $modeKerja,
                        'status' => $status,
                        'wa_pengirim' => $peserta->no_telepon,
                    ]
                );

                if ($status === 'Hadir') {
                    // Create 'Pulang' record
                    Absensi::firstOrCreate(
                        [
                            'peserta_id' => $peserta->id,
                            'jenis_absen' => 'Pulang',
                            'waktu_absen' => $jamPulang,
                        ],
                        [
                            'mode_kerja' => $modeKerja,
                            'status' => 'Hadir',
                            'wa_pengirim' => $peserta->no_telepon,
                        ]
                    );
                }
            }
        }
    }
}
