<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nama' => 'Superadmin',
                'deskripsi' => 'Administrator Sistem Utama',
            ],
            [
                'nama' => 'Admin',
                'deskripsi' => 'Administrator Sistem Percabang',
            ],
            [
                'nama' => 'Tim Wawancara',
                'deskripsi' => 'Tim Seleksi Wawancara',
            ],
            [
                'nama' => 'Siswa',
                'deskripsi' => 'Peserta Didik',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nama' => $role['nama']],
                [
                    'deskripsi' => $role['deskripsi'],
                    'is_active' => true,
                ]
            );
        }
    }
}
