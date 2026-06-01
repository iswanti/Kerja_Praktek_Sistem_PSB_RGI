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
        Schema::create('jadwal_wawancaras', function (Blueprint $table) {
            $table->id();
            // GELOMBANG
            $table->foreignId('gelombang_id')
                ->constrained('gelombangs')
                ->cascadeOnDelete();

            // CABANG
            $table->foreignId('cabang_id')
                ->constrained('cabangs')
                ->cascadeOnDelete();

            // JURUSAN
            $table->foreignId('jurusan_id')
                ->nullable()
                ->constrained('jurusans')
                ->nullOnDelete();

            // UNSUR WAWANCARA
            $table->enum('unsur', [
                'operator',
                'manajemen',
                'scc_asrama',
                'instruktur',
            ]);

            // WAKTU
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();

            // LINK
            $table->text('link_wawancara')->nullable();

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
        Schema::dropIfExists('jadwal_wawancaras');
    }
};
