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
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_id', 20);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('file_path')->nullable();
            $table->enum('status', ['Dikirim', 'Disetujui', 'Revisi'])
                ->default('Dikirim');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir');
    }
};
