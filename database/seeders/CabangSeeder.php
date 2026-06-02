<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            'Sawangan, Depok',
            'Magelang, Jawa Tengah',
            'Sentra Primer, Jakarta Timur',
            'Surabaya, Jawa Timur',
            'Yogyakarta',
            'Cilacap, Jawa Tengah',
        ];

        foreach ($cabangs as $namaCabang) {
            Cabang::updateOrCreate(
                ['nama_cabang' => $namaCabang],
                ['nama_cabang' => $namaCabang]
            );
        }
    }
}