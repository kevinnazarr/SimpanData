<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_id', 20)->nullable();
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();

            $table->string('peserta_id', 20);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
                
            $table->string('user_id', 20);
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian_details', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('penilaian_id')
                  ->constrained('penilaian')
                  ->cascadeOnDelete();
                  
            $table->foreignId('kategori_penilaian_id')
                  ->constrained('kategori_penilaians')
                  ->restrictOnDelete();
                  
            $table->unsignedTinyInteger('nilai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_details');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('kategori_penilaians');
    }
};
