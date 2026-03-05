<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)
                ->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('aksi');
            $table->string('target_tabel')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('ip_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
