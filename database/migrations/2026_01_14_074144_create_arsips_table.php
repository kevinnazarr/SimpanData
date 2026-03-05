<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_id', 20);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('file_path')->nullable();
            $table->date('diarsipkan_pada');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique('peserta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
