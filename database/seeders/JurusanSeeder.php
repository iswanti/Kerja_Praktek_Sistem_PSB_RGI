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
        $depok = Cabang::where('nama_cabang', 'Sawangan, Depok')->firstOrFail();
        $magelang = Cabang::where('nama_cabang', 'Magelang, Jawa Tengah')->firstOrFail();
        $sentra = Cabang::where('nama_cabang', 'Sentra Primer, Jakarta Timur')->firstOrFail();
        $surabaya = Cabang::where('nama_cabang', 'Surabaya, Jawa Timur')->firstOrFail();
        $yogyakarta = Cabang::where('nama_cabang', 'Yogyakarta')->firstOrFail();
        $cilacap = Cabang::where('nama_cabang', 'Cilacap, Jawa Tengah')->firstOrFail();

        $depok->jurusans()->createMany([
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan', 'kode_jurusan' => 'TKJ01'],
            ['nama_jurusan' => 'Tata Busana', 'kode_jurusan' => 'TB01'],
            ['nama_jurusan' => 'Fotografi dan Videografi', 'kode_jurusan' => 'FV01'],
            ['nama_jurusan' => 'Desain Grafis', 'kode_jurusan' => 'DG01'],
            ['nama_jurusan' => 'Otomotif', 'kode_jurusan' => 'TSM01'],
            ['nama_jurusan' => 'Aplikasi Perkantoran ( Konsentrasi Digital Office Specialist )', 'kode_jurusan' => 'AP01'],
        ]);

        $magelang->jurusans()->createMany([
            ['nama_jurusan' => 'Desain Grafis', 'kode_jurusan' => 'DG02'],
        ]);

        $sentra->jurusans()->createMany([
            ['nama_jurusan' => 'Aplikasi Perkantoran ( Konsentrasi Digital Business Specialist )', 'kode_jurusan' => 'AP03'],
        ]);

        $surabaya->jurusans()->createMany([
            ['nama_jurusan' => 'Tata Busana (Khusus Perempuan)', 'kode_jurusan' => 'TB04'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak (Khusus Laki-Laki)', 'kode_jurusan' => 'RPL04'],
        ]);

        $yogyakarta->jurusans()->createMany([
            ['nama_jurusan' => 'Kuliner Halal(Khusus Laki-Laki)', 'kode_jurusan' => 'KH05'],
        ]);

        $cilacap->jurusans()->createMany([
            ['nama_jurusan' => 'Tata Busana (Non Boarding)', 'kode_jurusan' => 'TB06'],
        ]);

    }
}
