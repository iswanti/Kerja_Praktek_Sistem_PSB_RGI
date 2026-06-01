<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'banksoal';

    protected $fillable = [
        'jurusan_id',
        'tipe_soal',
        'tipe',
        'pertanyaan',
        'deskripsi',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'jawaban_benar',
        'bobot',
        'urutan',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}