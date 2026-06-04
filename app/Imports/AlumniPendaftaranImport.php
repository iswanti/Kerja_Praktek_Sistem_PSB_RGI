<?php

namespace App\Imports;

use App\Models\AlumniPendaftaran;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class AlumniPendaftaranImport implements ToCollection
{
    public int $success = 0;
    public array $errors = [];

    private array $requiredHeader = ['nama', 'jurusan', 'angkatan'];

    public function collection(Collection $rows)
    {
        $header = null;

        // Ambil semua jurusan unik dari DB (group by nama)
        $jurusansDB = Jurusan::select('id', 'nama_jurusan')
            ->groupBy('nama_jurusan', 'id')
            ->get();

        foreach ($rows as $index => $line) {
            $row = $index + 1;

            $line = $line->toArray();
            $line = array_map(fn($v) => trim((string)$v), $line);

            // 🔹 Skip baris kosong (semua cell kosong)
            if (count(array_filter($line, fn($v) => $v !== '')) === 0) {
                continue;
            }

            // 🔹 Ambil header dari baris pertama yang tidak kosong
            if ($header === null) {
                $header = array_map(fn($v) => strtolower(trim($v)), $line);
                $header = array_slice($header, 0, count($this->requiredHeader));

                if ($header !== $this->requiredHeader) {
                    $this->errors[] = "Baris {$row}: format header harus: nama, jurusan, angkatan";
                    return;
                }
                continue;
            }

            $line = array_slice($line, 0, count($header));
            $data = array_combine($header, $line);

            // 🔹 Ubah string kosong menjadi null
            $data = array_map(fn($v) => $v === '' ? null : $v, $data);

            // 🔹 Skip baris yang semuanya null
            if (count(array_filter($data)) === 0) {
                continue;
            }

            // 🔹 Validator
            $validator = Validator::make($data, [
                'nama'     => 'required|string|max:255',
                'jurusan'  => 'required|string|max:255',
                'angkatan' => 'required|digits:4|integer',
            ], [
                'nama.required'     => 'nama wajib diisi',
                'jurusan.required'  => 'jurusan wajib diisi',
                'angkatan.required' => 'angkatan wajib diisi',
                'angkatan.digits'   => 'angkatan harus 4 digit, contoh: 2022',
                'angkatan.integer'  => 'angkatan harus berupa angka',
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Baris {$row}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // 🔹 Cocokkan jurusan unik dari DB
            $jurusanNama = preg_replace('/\s+/', ' ', strtolower(trim($data['jurusan'])));
            $jurusan = $jurusansDB->first(function ($item) use ($jurusanNama) {
                $namaDb = preg_replace('/\s+/', ' ', strtolower($item->nama_jurusan));
                return $namaDb === $jurusanNama;
            });

            if (!$jurusan) {
                $this->errors[] = "Baris {$row}: jurusan '{$data['jurusan']}' tidak ditemukan.";
                continue;
            }

            // 🔹 Simpan data
            AlumniPendaftaran::create([
                'nama'       => $data['nama'],
                'jurusan_id' => $jurusan->id,
                'angkatan'   => $data['angkatan'],
            ]);

            $this->success++;
        }
    }
}