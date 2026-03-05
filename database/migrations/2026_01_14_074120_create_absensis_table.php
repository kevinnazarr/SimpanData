<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_id', 20);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->enum('jenis_absen', ['Masuk', 'Pulang'])->nullable();
            $table->dateTime('waktu_absen')->nullable();
            $table->enum('mode_kerja', ['WFO', 'WFA'])
                ->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit']);
            $table->string('wa_pengirim')->nullable();
            $table->timestamps();
            $table->unique(['peserta_id', 'jenis_absen', 'waktu_absen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
