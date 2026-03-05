<?php

namespace Database\Factories;

use App\Models\Peserta;
use App\Models\Laporan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    public function definition(): array
    {
        $tanggalLaporan = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'peserta_id' => Peserta::factory(),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(5),
            'file_path' => fake()->optional()->filePath(),
            'tanggal_laporan' => $tanggalLaporan,
            'status' => fake()->randomElement(['Dikirim', 'Disetujui', 'Revisi']),
        ];
    }
}
