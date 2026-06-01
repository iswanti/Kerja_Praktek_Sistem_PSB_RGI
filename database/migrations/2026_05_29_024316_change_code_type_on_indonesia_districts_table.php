<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE indonesia_districts
            ALTER COLUMN code TYPE VARCHAR(7)
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE indonesia_districts
            ALTER COLUMN code TYPE CHAR(7)
        ");
    }
};