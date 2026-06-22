<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PendaftaranExport implements FromCollection, WithHeadings
{
    protected $cabangId;
    protected $status;
    protected $gelombangId;

    public function __construct($cabangId = null, $status = null, $gelombangId = null)
    {
        $this->cabangId = $cabangId;
        $this->status = $status;
        $this->gelombangId = $gelombangId;
    }

    public function collection()
    {
        $user = auth()->user();

        $query = Pendaftaran::with(['cabang', 'jurusan', 'gelombang']);

        // 🔒 AUTO LOCK CABANG UNTUK ADMIN NON-SUPERADMIN
        if ($user->role?->nama !== 'Superadmin') {
            $query->where('cabang_id', $user->cabang_id);
        }

        if (!empty($this->cabangId)) {
            $query->where('cabang_id', $this->cabangId);
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->gelombangId)) {
            $query->where('gelombang_id', $this->gelombangId);
        }
        
        return $query->get()->map(function ($item) {
            return [
                'gelombang'        => $item->gelombang->nama_gelombang ?? '-',
                'cabang'           => $item->cabang->nama_cabang ?? '-',
                'jurusan'          => $item->jurusan->nama_jurusan ?? '-',     // nama jurusan
                'nik'                => $item->nik,
                'nkk'                => $item->nkk,
                'nama'               => $item->nama,
                'tempat_lahir'       => $item->tempat_lahir,
                'tgl_lahir'          => $item->tgl_lahir,
                'umur'               => $item->umur,
                'jenis_kelamin'      => $item->jenis_kelamin,
                'anak_ke'            => $item->anak_ke,
                'alamat'             => $item->alamat,
                'provinsi_nama'      => $item->provinsi_nama,
                'kabupaten_nama'     => $item->kabupaten_nama,
                'kecamatan_nama'     => $item->kecamatan_nama,
                'kelurahan_nama'     => $item->kelurahan_nama,
                'pendidikan'         => $item->pendidikan,
                'sekolah'            => $item->sekolah,
                'cita_cita'          => $item->cita_cita,
                'hobi'               => is_array($item->hobi) ? implode(',', $item->hobi) : $item->hobi,
                'no_hp'              => $item->no_hp,
                'penyakit'           => $item->penyakit,
                'facebook'           => $item->facebook,
                'instagram'          => $item->instagram,
                'nama_wali'          => $item->nama_wali,
                'pendidikan_wali'    => $item->pendidikan_wali,
                'pekerjaan_wali'     => $item->pekerjaan_wali,
                'nohp_wali'          => $item->nohp_wali,
                'nama_ibu'           => $item->nama_ibu,
                'pendidikan_ibu'     => $item->pendidikan_ibu,
                'pekerjaan_ibu'      => $item->pekerjaan_ibu,
                'nohp_ibu'           => $item->nohp_ibu,
                'alamat_orangtua'    => $item->alamat_orangtua,
                'jml_keluarga'       => $item->jml_keluarga,
                'pendapatan_keluarga'=> $item->pendapatan_keluarga,
                'status_rumah'       => $item->status_rumah,
                'motivasi'           => $item->motivasi,
                'alasan'             => $item->alasan,
                'pengenalan'         => is_array($item->pengenalan) ? implode(',', $item->pengenalan) : $item->pengenalan,
                'rekomendasi'        => $item->rekomendasi,
                'status'             => $item->status,     // nama status
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Gelombang', 'Cabang', 'Jurusan', 'NIK', 'NKK', 'Nama', 'Tempat Lahir',
            'Tanggal Lahir', 'Umur', 'Jenis Kelamin', 'Anak Ke', 'Alamat', 'Provinsi',
            'Kabupaten', 'Kecamatan', 'Kelurahan', 'Pendidikan', 'Sekolah', 'Cita-Cita',
            'Hobi', 'No HP', 'Penyakit', 'Facebook', 'Instagram', 'Nama Wali', 'Pendidikan Wali',
            'Pekerjaan Wali', 'No HP Wali', 'Nama Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu',
            'No HP Ibu', 'Alamat Orang Tua', 'Jumlah Keluarga', 'Pendapatan Keluarga',
            'Status Rumah', 'Motivasi', 'Alasan', 'Pengenalan', 'Rekomendasi', 'Status',
        ];
    }
}