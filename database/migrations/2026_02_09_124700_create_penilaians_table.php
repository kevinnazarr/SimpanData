<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

            $table->unsignedTinyInteger('kedisiplinan');
            $table->unsignedTinyInteger('keterampilan');
            $table->unsignedTinyInteger('kerjasama');
            $table->unsignedTinyInteger('inisiatif');
            $table->unsignedTinyInteger('komunikasi');
            $table->unsignedTinyInteger('nilai_akhir');

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
