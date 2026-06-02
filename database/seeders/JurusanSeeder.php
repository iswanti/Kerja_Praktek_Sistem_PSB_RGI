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
        // Ambil semua cabang
        $depok = Cabang::where('nama_cabang', 'Sawangan, Depok')->firstOrFail();
        $magelang = Cabang::where('nama_cabang', 'Magelang, Jawa Tengah')->firstOrFail();
        $sentra = Cabang::where('nama_cabang', 'Sentra Primer, Jakarta Timur')->firstOrFail();
        $surabaya = Cabang::where('nama_cabang', 'Surabaya, Jawa Timur')->firstOrFail();
        $yogyakarta = Cabang::where('nama_cabang', 'Yogyakarta')->firstOrFail();
        $cilacap = Cabang::where('nama_cabang', 'Cilacap, Jawa Tengah')->firstOrFail();

        // Data jurusan per cabang
        $jurusanDepok = [
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan', 'kode_jurusan' => 'TKJ01'],
            ['nama_jurusan' => 'Tata Busana', 'kode_jurusan' => 'TB01'],
            ['nama_jurusan' => 'Fotografi dan Videografi', 'kode_jurusan' => 'FV01'],
            ['nama_jurusan' => 'Desain Grafis', 'kode_jurusan' => 'DG01'],
            ['nama_jurusan' => 'Otomotif', 'kode_jurusan' => 'TSM01'],
            ['nama_jurusan' => 'Aplikasi Perkantoran ( Konsentrasi Digital Office Specialist )', 'kode_jurusan' => 'AP01'],
        ];

        $jurusanMagelang = [
            ['nama_jurusan' => 'Desain Grafis', 'kode_jurusan' => 'DG02'],
        ];

        $jurusanSentra = [
            ['nama_jurusan' => 'Aplikasi Perkantoran ( Konsentrasi Digital Business Specialist )', 'kode_jurusan' => 'AP03'],
        ];

        $jurusanSurabaya = [
            ['nama_jurusan' => 'Tata Busana (Khusus Perempuan)', 'kode_jurusan' => 'TB04'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak (Khusus Laki-Laki)', 'kode_jurusan' => 'RPL04'],
        ];

        $jurusanYogyakarta = [
            ['nama_jurusan' => 'Kuliner Halal(Khusus Laki-Laki)', 'kode_jurusan' => 'KH05'],
        ];

        $jurusanCilacap = [
            ['nama_jurusan' => 'Tata Busana (Non Boarding)', 'kode_jurusan' => 'TB06'],
        ];

        // Fungsi helper untuk updateOrCreate
        $seedJurusan = function ($cabang, $jurusanList) {
            foreach ($jurusanList as $j) {
                $cabang->jurusans()->updateOrCreate(
                    [
                        'nama_jurusan' => $j['nama_jurusan'],
                        'cabang_id' => $cabang->id
                    ],
                    [
                        'kode_jurusan' => $j['kode_jurusan']
                    ]
                );
            }
        };

        // Jalankan untuk semua cabang
        $seedJurusan($depok, $jurusanDepok);
        $seedJurusan($magelang, $jurusanMagelang);
        $seedJurusan($sentra, $jurusanSentra);
        $seedJurusan($surabaya, $jurusanSurabaya);
        $seedJurusan($yogyakarta, $jurusanYogyakarta);
        $seedJurusan($cilacap, $jurusanCilacap);
    }
}