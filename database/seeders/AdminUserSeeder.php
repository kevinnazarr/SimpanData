<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Helpers\IdGenerator;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $id = IdGenerator::generate('admin');

        DB::table('user')->updateOrInsert(
            ['email' => 'adminsimpandata@gmail.com'],
            [
                'id' => $id,
                'username' => 'admin SimpanData',
                'password' => Hash::make('SimpanData123!'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
