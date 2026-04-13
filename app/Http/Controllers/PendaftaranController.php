<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;


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

    public function store (Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'jurusan_id' => 'required|exists:jurusans,id',
            'nik' => 'required|numeric|digits:16',
            'nkk' => 'required|numeric|digits:16',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'umur' => 'required',
            'jenis_kelamin' => 'required',
            'anak_ke' => 'required',
            'alamat' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'pendidikan' => 'required',
            'sekolah' => 'required',
            'berkas' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ]);

        Pendaftaran::create([
            'cabang_id' => $request->cabang_id,
            'jurusan_id' => $request->jurusan_id,
            'nik' => $request->nik,
            'nkk' => $request->nkk,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'umur' => $request->umur,
            'jenis_kelamin' => $request->jenis_kelamin,
            'anak_ke' => $request->anak_ke,
            'alamat' => $request->alamat,
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'pendidikan' => $request->pendidikan,
            'sekolah' => $request->sekolah,
            'berkas' => $request->file('berkas')?->store('berkas', 'public'),
            
        ]);

        // if (Pendaftaran::where('user_id', auth()->id())->exists()) {
        //     return redirect()->back()
        //         ->with('error', 'Anda sudah melakukan pendaftaran');
        // }
        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil!');
    }

    
}
