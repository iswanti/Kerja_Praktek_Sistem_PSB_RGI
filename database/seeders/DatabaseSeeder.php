<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Cabang;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CabangSeeder::class,
            JurusanSeeder::class,
            RoleSeeder::class,
            MenuSeeder::class,
            AdminPermissionSeeder::class,

            \Laravolt\Indonesia\Seeds\ProvincesSeeder::class,
            \Laravolt\Indonesia\Seeds\CitiesSeeder::class,
            \Laravolt\Indonesia\Seeds\DistrictsSeeder::class,
            \Laravolt\Indonesia\Seeds\VillagesSeeder::class,
        ]);

        $adminRole = Role::where('nama', 'Superadmin')->first();

        $cabang = Cabang::first();

        User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name' => 'Superadmin',
                'phone' => '081234567890',
                'role_id' => $adminRole?->id,
                'cabang_id' => $cabang?->id,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );
    }
}
