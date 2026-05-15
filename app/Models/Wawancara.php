<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wawancara extends Model
{
    use HasFactory;

    protected $table = 'wawancaras';

    protected $fillable = [
        'pendaftaran_id',

        // Operator
        'nama_operator',
        'rekomendasi_operator',

        // Manajemen
        'nama_pewawancara_manajemen',
        'pendapatan_orangtua',
        'pelanggaran_berat',
        'kondisi_rumah',
        'tingkat_keduafaan',
        'catatan_manajemen',
        'nilai_manajemen',

        // SCC / Asrama
        'nama_pewawancara_scc',
        'merokok',
        'mengaji',
        'sholat',
        'catatan_scc',
        'nilai_scc',

        // Instruktur
        'nama_instruktur',
        'rencana_setelah_lulus',
        'level_pengetahuan_materi',
        'kemampuan_dasar',
        'motivasi_belajar',
        'catatan_instruktur',
        'nilai_instruktur',

        // Rekap Akhir
        'nilai_akhir',
        'rekomendasi_akhir',
        'status',
    ];

    protected $casts = [
        'nilai_manajemen' => 'decimal:2',
        'nilai_scc' => 'decimal:2',
        'nilai_instruktur' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public function getSemuaUnsurSelesaiAttribute()
    {
        return !empty($this->rekomendasi_operator)
            && !is_null($this->nilai_manajemen)
            && !is_null($this->nilai_scc)
            && !is_null($this->nilai_instruktur);
    }

    public function hitungNilaiAkhir()
    {
        $nilai = array_filter([
            $this->nilai_manajemen,
            $this->nilai_scc,
            $this->nilai_instruktur,
        ], fn ($item) => !is_null($item));

        if (count($nilai) === 0) {
            return null;
        }

        return round(array_sum($nilai) / count($nilai), 2);
    }

    public function tentukanRekomendasi()
    {
        $nilaiAkhir = $this->hitungNilaiAkhir();

        if (is_null($nilaiAkhir)) {
            return null;
        }

        if ($nilaiAkhir >= 80) {
            return 'lulus';
        }

        if ($nilaiAkhir >= 60) {
            return 'dipertimbangkan';
        }

        return 'tidak_lulus';
    }
}