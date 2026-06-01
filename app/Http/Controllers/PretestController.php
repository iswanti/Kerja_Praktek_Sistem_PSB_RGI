<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jurusan;
use App\Models\Gelombang;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PretestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperadmin = $user->role?->nama === 'Superadmin';

        $query = Pendaftaran::with([
            'cabang',
            'jurusan',
            'gelombang'
        ])->whereNotNull('nilai_pretest');

        if (!$isSuperadmin) {
            $query->where('cabang_id', $user->cabang_id);
        }

        if ($isSuperadmin && $request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('gelombang_id')) {
            $query->where('gelombang_id', $request->gelombang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_pendaftaran', 'ilike', "%{$search}%")
                    ->orWhere('nama', 'ilike', "%{$search}%");
            });
        }

        $pendaftarans = $query
            ->orderByDesc('nilai_pretest')
            ->paginate(10)
            ->withQueryString();

        $cabangs = $isSuperadmin
            ? Cabang::orderBy('nama_cabang')->get()
            : Cabang::where('id', $user->cabang_id)->get();

        $jurusans = Jurusan::when(!$isSuperadmin, function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            })
            ->orderBy('nama_jurusan')
            ->get();

        $gelombangs = Gelombang::orderByDesc('id')->get();

        return view('admin.soal.pretest', compact(
            'pendaftarans',
            'cabangs',
            'jurusans',
            'gelombangs',
            'isSuperadmin'
        ));
    }
}