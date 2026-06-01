<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'pendaftaran_mulai' => 'datetime',
        'pendaftaran_selesai' => 'datetime',

        'pretest_mulai' => 'datetime',
        'pretest_selesai' => 'datetime',

        'wawancara_mulai' => 'datetime',
        'wawancara_selesai' => 'datetime',

        'pengumuman_mulai' => 'datetime',

        'is_active' => 'boolean',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}

