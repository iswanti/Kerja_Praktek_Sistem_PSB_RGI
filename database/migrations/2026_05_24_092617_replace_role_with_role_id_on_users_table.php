<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Hapus column role lama
            $table->dropColumn('role');

        });

        Schema::table('users', function (Blueprint $table) {

            // Tambah role_id
            $table->foreignId('role_id')
                ->nullable()
                ->after('email')
                ->constrained('roles')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');

            $table->string('role')->default('siswa');

        });
    }
};
