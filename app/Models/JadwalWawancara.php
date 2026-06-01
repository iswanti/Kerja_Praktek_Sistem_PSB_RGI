<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalWawancara extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}