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
        Schema::create('banksoal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->cascadeOnDelete();
            $table->enum('tipe', ['pilihan_ganda', 'essay']);
            $table->text('pertanyaan');
            $table->string('pilihan_a')->nullable();
            $table->string('pilihan_b')->nullable();
            $table->string('pilihan_c')->nullable();
            $table->string('pilihan_d')->nullable();
            $table->string('pilihan_e')->nullable();
            $table->text('jawaban_benar');
            $table->integer('bobot')->default(10);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banksoal');
    }
};
