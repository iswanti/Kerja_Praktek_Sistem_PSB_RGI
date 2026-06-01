<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanPeserta extends Model
{
    protected $table = 'pretest_jawabans';

    protected $fillable = ['pendaftaran_id', 'soal_id', 'jawaban', 'is_benar','nilai'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
    
    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
