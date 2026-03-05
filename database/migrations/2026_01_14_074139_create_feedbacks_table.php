<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_id', 20);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->enum('pengirim', ['Peserta', 'Admin']);
            $table->text('pesan');
            $table->boolean('tampilkan')->default(false);
            $table->integer('rating')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
