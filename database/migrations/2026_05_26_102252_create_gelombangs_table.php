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
        Schema::create('gelombangs', function (Blueprint $table) {

            $table->id();

            // IDENTITAS
            $table->string('nama_gelombang');
            $table->year('tahun_periode');

            // PENDAFTARAN
            $table->dateTime('pendaftaran_mulai')->nullable();
            $table->dateTime('pendaftaran_selesai')->nullable();

            // PRETEST
            $table->dateTime('pretest_mulai')->nullable();
            $table->dateTime('pretest_selesai')->nullable();

            // dalam menit
            $table->integer('durasi_pretest')->nullable();

            // WAWANCARA
            $table->dateTime('wawancara_mulai')->nullable();
            $table->dateTime('wawancara_selesai')->nullable();

            // PENGUMUMAN
            $table->dateTime('pengumuman_mulai')->nullable();

            // STATUS
            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombangs');
    }
};