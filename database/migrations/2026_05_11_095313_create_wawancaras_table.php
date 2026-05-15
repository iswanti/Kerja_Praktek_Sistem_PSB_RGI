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
        Schema::create('wawancaras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendaftaran_id')
                ->constrained('pendaftarans')
                ->cascadeOnDelete();

            // Operator
            $table->string('nama_operator')->nullable();
            $table->string('rekomendasi_operator')->nullable();

            // Manajemen
            $table->string('nama_pewawancara_manajemen')->nullable();
            $table->string('pendapatan_orangtua')->nullable();
            $table->string('pelanggaran_berat')->nullable();
            $table->string('kondisi_rumah')->nullable();
            $table->string('tingkat_keduafaan')->nullable();
            $table->text('catatan_manajemen')->nullable();
            $table->decimal('nilai_manajemen', 5, 2)->nullable();

            // SCC / Asrama
            $table->string('nama_pewawancara_scc')->nullable();
            $table->string('merokok')->nullable();
            $table->string('mengaji')->nullable();
            $table->string('sholat')->nullable();
            $table->text('catatan_scc')->nullable();
            $table->decimal('nilai_scc', 5, 2)->nullable();

            // Instruktur
            $table->string('nama_instruktur')->nullable();
            $table->text('rencana_setelah_lulus')->nullable();
            $table->string('level_pengetahuan_materi')->nullable();
            $table->string('kemampuan_dasar')->nullable();
            $table->string('motivasi_belajar')->nullable();
            $table->text('catatan_instruktur')->nullable();
            $table->decimal('nilai_instruktur', 5, 2)->nullable();

            // Rekap Akhir
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('rekomendasi_akhir')->nullable();
            $table->string('status')->default('draft');

            $table->timestamps();

            $table->unique('pendaftaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wawancaras');
    }
};
