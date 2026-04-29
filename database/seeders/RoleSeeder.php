<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'instruktur']);
        Role::firstOrCreate(['name' => 'manajemen']);
        Role::firstOrCreate(['name' => 'siswa']);
    }
}