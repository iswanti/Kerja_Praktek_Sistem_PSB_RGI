<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = [
        'nama_jurusan',
        'cabang_id'
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
