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
            $table->string('kode_pendaftaran')->unique();
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

            // WILAYAH (RELATIONAL ID)
            $table->text('alamat');
            $table->string('provinsi_nama');
            $table->string('kabupaten_nama');
            $table->string('kecamatan_nama');
            $table->string('kelurahan_nama');
            $table->string('id_alamat');

            // PENDIDIKAN
            $table->string('pendidikan');
            $table->string('sekolah');
            $table->string('cita_cita');
            $table->json('hobi')->nullable();
            $table->string('no_hp');
            $table->string('penyakit')->nullable();

            $table->string('facebook');
            $table->string('instagram');

            $table->string('nama_wali');
            $table->string('pendidikan_wali');
            $table->string('pekerjaan_wali');
            $table->string('nohp_wali');

            // Ibu
            $table->string('nama_ibu');
            $table->string('pendidikan_ibu');
            $table->string('pekerjaan_ibu');
            $table->string('nohp_ibu');

            // Keluarga
            $table->text('alamat_orangtua');
            $table->integer('jml_keluarga');
            $table->string('pendapatan_keluarga');

            // 🔥 Status rumah (1 field saja)
            $table->string('status_rumah');
            $table->text('motivasi');
            $table->json('pengenalan')->nullable();
            $table->text('alasan');
            $table->text('rekomendasi')->nullable();

            // BERKAS
            $table->string('pas_foto');
            $table->string('foto_kk');
            $table->string('foto_ktp');
            $table->string('foto_ijazah');
            $table->string('sktm');
            $table->string('surat_sehat');
            $table->string('foto_rumah');

            $table->string('status')->default('menunggu_verifikasi');
            $table->text('alasan_ditolak')->nullable();

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
