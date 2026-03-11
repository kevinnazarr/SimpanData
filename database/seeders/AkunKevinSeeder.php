<?php

namespace Database\Seeders;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Helpers\IdGenerator;

class AkunKevinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'kevinnazar76@gmail.com')->first();
        if (!$user) {
            $user = User::create([
                'id' => IdGenerator::generate('peserta', 'PKL'),
                'username' => 'kevin',
                'email' => 'kevinnazar76@gmail.com',
                'password' => Hash::make('kevinnazar'),
                'role' => 'peserta'
            ]);
        } else {
            $user->update([
                'username' => 'kevin',
                'password' => Hash::make('kevinnazar'),
                'role' => 'peserta'
            ]);
        }

        Peserta::updateOrCreate(
            ['id' => $user->id],
            [
                'nama' => 'Kevin Nazar Mufadhol',
                'asal_sekolah_universitas' => 'SMK Negeri 1 Wonosobo',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'alamat' => 'patak banteng kejajar wonosobo',
                'latitude' => '-7.178950',
                'longitude' => '109.912345',
                'no_telepon' => '087793507497',
                'jenis_kegiatan' => 'PKL',
                'status' => 'Aktif',
                'tanggal_mulai' => '2026-01-05',
                'tanggal_selesai' => '2026-03-30',
                'nim_nis' => '0073370885',
                'tugas' => 'membuat website pengelolaan pkl dan magang',
            ]
        );

        $this->command->info('Akun Kevin berhasil ditambahkan!');
    }
}
