<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniPendaftaran extends Model
{
    protected $fillable = [
        'nama',
        'jurusan_id',
        'angkatan',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
