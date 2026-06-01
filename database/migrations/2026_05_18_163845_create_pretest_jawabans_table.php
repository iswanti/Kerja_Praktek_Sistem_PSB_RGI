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
        Schema::create('pretest_jawabans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel pendaftarans
            $table->foreignId('pendaftaran_id')
                ->constrained('pendaftarans')
                ->cascadeOnDelete();

            // Relasi ke tabel soals
            $table->foreignId('soal_id')
                ->constrained('banksoal')
                ->cascadeOnDelete();

            // Jawaban peserta
            $table->text('jawaban')->nullable();

            // Benar / salah (untuk pilihan ganda)
            $table->boolean('is_benar')->nullable();

            // Satu pendaftaran hanya boleh memiliki satu jawaban untuk satu soal
            $table->unique(['pendaftaran_id', 'soal_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pretest_jawabans');
    }
};
