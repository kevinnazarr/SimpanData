<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->foreign('id')
                ->references('id')
                ->on('user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->string('asal_sekolah_universitas');
            $table->string('jurusan');
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->enum('jenis_kegiatan', ['PKL', 'Magang']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Aktif', 'Selesai', 'Arsip'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
