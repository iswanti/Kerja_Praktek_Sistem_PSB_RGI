<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
        'cabang_id'
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
