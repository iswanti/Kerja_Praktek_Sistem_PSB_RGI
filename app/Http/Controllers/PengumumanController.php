<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;

class PengumumanController extends Controller
{

    public function index()
    {
        // Ambil data pendaftaran milik user yang login
        $pendaftaran = Pendaftaran::with(['jurusan', 'cabang'])
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        $pengumumanAktif = false;
        if ($pendaftaran && $pendaftaran->gelombang) {

            $pengumumanAktif =
                now()->greaterThanOrEqualTo(
                    $pendaftaran->gelombang->pengumuman_mulai
                );

        }

        // Gunakan view publik.blade.php
        return view('pengumuman.publik', compact('pendaftaran', 'pengumumanAktif'));
    }
}