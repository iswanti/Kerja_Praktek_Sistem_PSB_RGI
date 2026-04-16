<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'cabang_id',
        'jurusan_id',
        'kode_pendaftaran',
        'nik',
        'nkk',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'umur',
        'jenis_kelamin',
        'anak_ke',
        'alamat',
        'provinsi_nama',
        'kabupaten_nama',
        'kecamatan_nama',
        'kelurahan_nama',
        'id_alamat',
        'pendidikan',
        'sekolah',
        'cita_cita',
        'hobi',
        'no_hp',
        'penyakit',
        'facebook',
        'instagram',
        'nama_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'nohp_wali',

        'nama_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'nohp_ibu',

        'alamat_orangtua',
        'jml_keluarga',
        'pendapatan_keluarga',

        'status_rumah',
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

    protected static function booted()
    {
        static::creating(function ($pendaftaran) {

            // Ambil jurusan
            $jurusan = \App\Models\Jurusan::find($pendaftaran->jurusan_id);

            if (!$jurusan) {
                throw new \Exception('Jurusan tidak ditemukan');
            }

            // Ambil data terakhir berdasarkan jurusan
            $last = self::where('jurusan_id', $pendaftaran->jurusan_id)
                ->orderBy('id', 'desc')
                ->lockForUpdate() // 🔥 penting untuk menghindari duplikat
                ->first();

            $next = 1;

            if ($last && $last->kode_pendaftaran) {
                $lastNumber = (int) substr($last->kode_pendaftaran, -4);
                $next = $lastNumber + 1;
            }

            // Format 4 digit
            $nomor = str_pad($next, 4, '0', STR_PAD_LEFT);

            // Generate kode
            $pendaftaran->kode_pendaftaran = $jurusan->kode_jurusan . '-' . date('Y') . '-' . $nomor;
        });
    }
}

