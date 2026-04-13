<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $depok = Cabang::where('nama_cabang', 'Depok')->firstOrFail();
        $magelang = Cabang::where('nama_cabang', 'Magelang')->firstOrFail();
        $sentra = Cabang::where('nama_cabang', 'Sentra')->firstOrFail();
        $surabaya = Cabang::where('nama_cabang', 'Surabaya')->firstOrFail();
        $yogyakarta = Cabang::where('nama_cabang', 'Yogyakarta')->firstOrFail();
        $cilacap = Cabang::where('nama_cabang', 'Cilacap')->firstOrFail();

        $depok->jurusans()->createMany([
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_jurusan' => 'Tata Busana'],
            ['nama_jurusan' => 'Fotografi dan Videografi'],
            ['nama_jurusan' => 'Desain Grafis'],
            ['nama_jurusan' => 'Otomotif'],
            ['nama_jurusan' => 'Aplikasi Perkantoran'],
        ]);

        $magelang->jurusans()->createMany([
            ['nama_jurusan' => 'Desain Grafis'],
        ]);

        $sentra->jurusans()->createMany([
            ['nama_jurusan' => 'Aplikasi Perkantoran'],
        ]);

        $surabaya->jurusans()->createMany([
            ['nama_jurusan' => 'Tata Busana'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
        ]);

        $yogyakarta->jurusans()->createMany([
            ['nama_jurusan' => 'Kuliner Halal'],
        ]);

        $cilacap->jurusans()->createMany([
            ['nama_jurusan' => 'Tata Busana'],
        ]);

    }
}
