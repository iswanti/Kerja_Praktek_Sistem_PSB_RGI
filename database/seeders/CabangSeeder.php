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
        ['nama_cabang' => 'Sawangan, Depok'],
        ['nama_cabang' => 'Magelang, Jawa Tengah'],
        ['nama_cabang' => 'Sentra Primer, Jakarta Timur'],
        ['nama_cabang' => 'Surabaya, Jawa Timur'],
        ['nama_cabang' => 'Yogyakarta'],
        ['nama_cabang' => 'Cilacap, Jawa Tengah'],
    ]);
    }
}
