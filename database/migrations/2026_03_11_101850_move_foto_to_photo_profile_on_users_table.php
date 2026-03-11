<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('photo_profile')->nullable()->after('role');
        });

        if (Schema::hasColumn('peserta', 'foto')) {
            $pesertas = \Illuminate\Support\Facades\DB::table('peserta')->whereNotNull('foto')->get();
            foreach ($pesertas as $peserta) {
                \Illuminate\Support\Facades\DB::table('user')
                    ->where('id', $peserta->id)
                    ->update(['photo_profile' => $peserta->foto]);
            }

            Schema::table('peserta', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->string('foto')->nullable();
        });

        $users = \Illuminate\Support\Facades\DB::table('user')->whereNotNull('photo_profile')->get();
        foreach ($users as $user) {
            \Illuminate\Support\Facades\DB::table('peserta')
                ->where('id', $user->id)
                ->update(['foto' => $user->photo_profile]);
        }

        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('photo_profile');
        });
    }
};
