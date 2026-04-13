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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            // RELASI UTAMA
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');

            // DATA DIRI
            $table->string('nik')->unique();
            $table->string('nkk');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->integer('umur');
            $table->string('jenis_kelamin');
            $table->integer('anak_ke');

            // ALAMAT
            $table->text('alamat');

            // WILAYAH (RELATIONAL ID)
            $table->string('provinsi_id')->nullable();
            $table->string('kabupaten_id')->nullable();
            $table->string('kecamatan_id')->nullable();
            $table->string('kelurahan_id')->nullable();

            // PENDIDIKAN
            $table->string('pendidikan');
            $table->string('sekolah');

            // BERKAS
            $table->string('berkas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
