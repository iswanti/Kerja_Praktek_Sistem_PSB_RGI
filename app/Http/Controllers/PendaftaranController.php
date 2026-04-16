<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PendaftaranController extends Controller
{
    public function index (){
        $cabangs = Cabang::with(['jurusans' => function ($q) {
            $q->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

    
        return view('pendaftaran.index', compact('cabangs'));
    }

    // public function store (Request $request)
    // {
    //     $request->validate([
    //         'cabang_id' => 'required|exists:cabangs,id',
    //         'jurusan_id' => 'required|exists:jurusans,id',
    //         'nik' => 'required|numeric|digits:16',
    //         'nkk' => 'required|numeric|digits:16',
    //         'nama' => 'required',
    //         'tempat_lahir' => 'required',
    //         'tgl_lahir' => 'required',
    //         'umur' => 'required',
    //         'jenis_kelamin' => 'required',
    //         'anak_ke' => 'required',
    //         'alamat' => 'required',
    //         'provinsi_nama' => 'required',
    //         'kabupaten_nama' => 'required',
    //         'kecamatan_nama' => 'required',
    //         'kelurahan_nama' => 'required',
    //         'id_alamat' => 'required',
    //         'pendidikan' => 'required',
    //         'sekolah' => 'required',
    //         'cita_cita' => 'required',
    //         'hobi' => 'hobi',
    //         'no_hp' => 'required',
    //         'penyakit' =>'nullable',
    //         'facebook' => ['required','regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'],
    //         'instagram' => ['required','regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'],
    //         'nama_wali' => 'required',
    //         'pendidikan_wali' => 'required',
    //         'pekerjaan_wali' => 'required',
    //         'nohp_wali' => 'required',
    //         'nama_ibu' => 'required',
    //         'pendidikan_ibu' => 'required',
    //         'pekerjaan_ibu' => 'required',
    //         'nohp_ibu' => 'required',
    //         'alamat_orangtua' => 'required',
    //         'jml_keluarga' => 'required',
    //         'pendapatan_keluarga' => 'required',
    //         'status_rumah' => 'required|in:milik_sendiri,sewa,milik_kerabat,lainnya',
            
    //         'status_lainnya' => ['required_if:status_rumah,lainnya','nullable', 'string','max:50'],
    //         'berkas' => 'nullable|mimes:pdf,jpg,png|max:2048',
    //     ]);

    //     if ($request->status_rumah !== 'lainnya') { 
    //         $request->merge(['status_lainnya' => null]); 
    //     }

    //     DB::transaction(function () use ($request) {
    //         Pendaftaran::create([
    //             'cabang_id' => $request->cabang_id,
    //             'jurusan_id' => $request->jurusan_id,
    //             'nik' => $request->nik,
    //             'nkk' => $request->nkk,
    //             'nama' => $request->nama,
    //             'tempat_lahir' => $request->tempat_lahir,
    //             'tgl_lahir' => $request->tgl_lahir,
    //             'umur' => $request->umur,
    //             'jenis_kelamin' => $request->jenis_kelamin,
    //             'anak_ke' => $request->anak_ke,
    //             'alamat' => $request->alamat,
    //             'provinsi_nama' => $request->provinsi_nama,
    //             'kabupaten_nama' => $request->kabupaten_nama,
    //             'kecamatan_nama' => $request->kecamatan_nama,
    //             'kelurahan_nama' => $request->kelurahan_nama,
    //             'id_alamat' => $request->id_alamat,
    //             'pendidikan' => $request->pendidikan,
    //             'sekolah' => $request->sekolah,
    //             'cita_cita' => $request->cita_cita,
    //             'hobi' => $request->hobi,
    //             'no_hp' => $request->no_hp,
    //             'penyakit' => $request->penyakit,
    //             'facebook' => $request->facebook,
    //             'instagram' => $request->instagram,
    //             'nama_wali' => $request->nama_wali,
    //             'pendidikan_wali' => $request->pendidikan_wali,
    //             'pekerjaan_wali' => $request->pekerjaan_wali,
    //             'nohp_wali' => $request->nohp_wali,
    //             'nama_ibu' => $request->nama_ibu,
    //             'pendidikan_ibu' => $request->pendidikan_ibu,
    //             'pekerjaan_ibu' => $request->pekerjaan_ibu,
    //             'nohp_ibu' => $request->nohp_ibu,
    //             'alamat_orangtua' => $request->alamat_orangtua,
    //             'jml_keluarga' => $request->jml_keluarga,
    //             'pendapatan_keluarga' => $request->pendapatan_keluarga,
    //             'status_rumah' => $request->status_rumah,
    //             'status_lainnya' => $request->status_lainnya,
    //             'berkas' => $request->file('berkas')?->store('berkas', 'public'),
    //         ]);

    //     });

    //     // if (Pendaftaran::where('user_id', auth()->id())->exists()) {
    //     //     return redirect()->back()
    //     //         ->with('error', 'Anda sudah melakukan pendaftaran');
    //     // }
    //     return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil!');
    // }
    public function store(Request $request)
    {
        // 1. VALIDASI
        $validated = $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'jurusan_id' => 'required|exists:jurusans,id',
            'nik' => 'required|numeric|digits:16',
            'nkk' => 'required|numeric|digits:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'umur' => 'required|numeric',
            'jenis_kelamin' => 'required',
            'anak_ke' => 'required|numeric',
            'alamat' => 'required|string',
            'provinsi_nama' => 'required',
            'kabupaten_nama' => 'required',
            'kecamatan_nama' => 'required',
            'kelurahan_nama' => 'required',
            'id_alamat' => 'required',

            'pendidikan' => 'required',
            'sekolah' => 'required',
            'cita_cita' => 'required',
            'hobi' => 'nullable|string|max:100',
            'no_hp' => 'required',
            'penyakit' => 'nullable|string',

            'facebook' => [
                'required',
                'regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'
            ],
            'instagram' => [
                'required',
                'regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'
            ],

            'nama_wali' => 'required',
            'pendidikan_wali' => 'required',
            'pekerjaan_wali' => 'required',
            'nohp_wali' => 'required',

            'nama_ibu' => 'required',
            'pendidikan_ibu' => 'required',
            'pekerjaan_ibu' => 'required',
            'nohp_ibu' => 'required',

            'alamat_orangtua' => 'required',
            'jml_keluarga' => 'required|numeric',
            'pendapatan_keluarga' => 'required',

            'status_rumah' => 'required|in:milik_sendiri,sewa,milik_kerabat,lainnya',
            'status_lainnya' => 'nullable|required_if:status_rumah,lainnya|string|max:50',

            'berkas' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ]);

        // 2. GABUNGKAN JADI 1 FIELD
        $status_rumah = $validated['status_rumah'] === 'lainnya'
            ? $validated['status_lainnya']
            : $validated['status_rumah'];

        // 3. UPLOAD FILE
        if ($request->hasFile('berkas')) {
            $validated['berkas'] = $request->file('berkas')->store('berkas', 'public');
        }

        // 4. SIMPAN
        $pendaftaran = DB::transaction(function () use ($validated, $status_rumah){
            return Pendaftaran::create([
                'cabang_id' => $validated['cabang_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'nik' => $validated['nik'],
                'nkk' => $validated['nkk'],
                'nama' => $validated['nama'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tgl_lahir' => $validated['tgl_lahir'],
                'umur' => $validated['umur'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'anak_ke' => $validated['anak_ke'],
                'alamat' => $validated['alamat'],
                'provinsi_nama' => $validated['provinsi_nama'],
                'kabupaten_nama' => $validated['kabupaten_nama'],
                'kecamatan_nama' => $validated['kecamatan_nama'],
                'kelurahan_nama' => $validated['kelurahan_nama'],
                'id_alamat' => $validated['id_alamat'],

                'pendidikan' => $validated['pendidikan'],
                'sekolah' => $validated['sekolah'],
                'cita_cita' => $validated['cita_cita'],
                'hobi' => $validated['hobi'] ?? null,
                'no_hp' => $validated['no_hp'],
                'penyakit' => $validated['penyakit'] ?? null,

                'facebook' => $validated['facebook'],
                'instagram' => $validated['instagram'],

                'nama_wali' => $validated['nama_wali'],
                'pendidikan_wali' => $validated['pendidikan_wali'],
                'pekerjaan_wali' => $validated['pekerjaan_wali'],
                'nohp_wali' => $validated['nohp_wali'],

                'nama_ibu' => $validated['nama_ibu'],
                'pendidikan_ibu' => $validated['pendidikan_ibu'],
                'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
                'nohp_ibu' => $validated['nohp_ibu'],

                'alamat_orangtua' => $validated['alamat_orangtua'],
                'jml_keluarga' => $validated['jml_keluarga'],
                'pendapatan_keluarga' => $validated['pendapatan_keluarga'],

                // 🔥 hanya 1 field
                'status_rumah' => $status_rumah,

                'berkas' => $validated['berkas'] ?? null,
            ]);
        });
        // dd($request->all());

        if (!$pendaftaran) {
            return back()->with('error', 'Gagal menyimpan data');
        }

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil!')
            ->with('kode', $pendaftaran->kode_pendaftaran);
    }

    
}
