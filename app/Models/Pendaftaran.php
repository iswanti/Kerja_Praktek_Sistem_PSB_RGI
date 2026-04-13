<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'cabang_id',
        'jurusan_id',
        'nik',
        'nkk',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'umur',
        'jenis_kelamin',
        'anak_ke',
        'alamat',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'pendidikan',
        'sekolah',
        'berkas',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
