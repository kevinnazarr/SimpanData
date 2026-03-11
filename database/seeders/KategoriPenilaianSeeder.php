<?php

namespace Database\Seeders;

use App\Models\KategoriPenilaian;
use Illuminate\Database\Seeder;

class KategoriPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Kedisiplinan',
                'deskripsi' => 'Menilai ketepatan waktu hadir dan kepatuhan terhadap peraturan perusahaan.',
            ],
            [
                'nama' => 'Kerjasama Tim',
                'deskripsi' => 'Kemampuan berinteraksi dan berkontribusi secara positif dalam kelompok.',
            ],
            [
                'nama' => 'Kualitas Pekerjaan',
                'deskripsi' => 'Ketelitian, kerapihan, dan akurasi hasil pekerjaan yang diberikan.',
            ],
            [
                'nama' => 'Inisiatif',
                'deskripsi' => 'Kemampuan untuk mengambil tindakan tanpa harus menunggu instruksi berlebih.',
            ],
            [
                'nama' => 'Sikap',
                'deskripsi' => 'Kesantunan, integritas, dan profesionalisme selama menjalankan program.',
            ],
        ];

        foreach ($categories as $category) {
            KategoriPenilaian::updateOrCreate(
                ['nama' => $category['nama']],
                ['deskripsi' => $category['deskripsi']]
            );
        }
    }
}
