<?php

namespace App\Exports;

use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class AlumniTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function array(): array
    {
        // Contoh baris pertama (user bisa ganti)
        $jurusanPertama = Jurusan::orderBy('nama_jurusan')->value('nama_jurusan');

        return [
            ['Budi Santoso', $jurusanPertama ?? 'Nama Jurusan', '2022'],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'jurusan', 'angkatan'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ambil semua jurusan
                $jurusans = Jurusan::select('nama_jurusan')
                    ->groupBy('nama_jurusan')
                    ->orderBy('nama_jurusan')
                    ->pluck('nama_jurusan')
                    ->toArray();

                // Letakkan jurusan di kolom tersembunyi Z1:Z100
                $hiddenColumn = 'Z';
                foreach ($jurusans as $i => $jurusan) {
                    $sheet->setCellValue($hiddenColumn . ($i + 1), $jurusan);
                }

                // Sembunyikan kolom Z
                $sheet->getColumnDimension($hiddenColumn)->setVisible(false);

                // Atur dropdown di kolom B untuk 100 baris
                $highestRow = 100;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $validation = $sheet->getCell("B$row")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input salah');
                    $validation->setError('Jurusan tidak valid, pilih dari dropdown.');
                    $validation->setPromptTitle('Pilih Jurusan');
                    $validation->setPrompt('Silakan pilih jurusan dari dropdown.');
                    $validation->setFormula1(sprintf('=%s$1:%s$%d', $hiddenColumn, $hiddenColumn, count($jurusans)));
                }

                // Atur lebar kolom agar lebih nyaman
                $sheet->getColumnDimension('A')->setWidth(25); // nama
                $sheet->getColumnDimension('B')->setWidth(40); // jurusan
                $sheet->getColumnDimension('C')->setWidth(10); // angkatan
            },
        ];
    }
}