<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::insert([
        ['nama_cabang' => 'Magelang'],
        ['nama_cabang' => 'Depok'],
        ['nama_cabang' => 'Sentra'],
        ['nama_cabang' => 'Surabaya'],
        ['nama_cabang' => 'Yogyakarta'],
        ['nama_cabang' => 'Cilacap'],
    ]);
    }
}
