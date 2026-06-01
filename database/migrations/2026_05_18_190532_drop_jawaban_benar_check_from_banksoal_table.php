<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE banksoal DROP CONSTRAINT IF EXISTS banksoal_jawaban_benar_check');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE banksoal ADD CONSTRAINT banksoal_jawaban_benar_check CHECK (jawaban_benar IN ('A', 'B', 'C', 'D', 'E'))");
    }
};
