<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['cabang', 'jurusan']);

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('periode')) {
            [$tahun, $bulan] = explode('-', $request->periode);

            $query->whereYear('created_at', $tahun)
                  ->whereMonth('created_at', $bulan);
        }

        $totalPendaftar = (clone $query)->count();

        $menungguVerifikasi = (clone $query)
            ->where('status', 'menunggu_verifikasi')
            ->count();

        $terverifikasi = (clone $query)
            ->where('status', 'terverifikasi')
            ->count();

        $seleksiPretest = (clone $query)
            ->where('status', 'seleksi_pretest')
            ->count();

        $wawancara = (clone $query)
            ->where('status', 'wawancara')
            ->count();

        $verifikasiKelulusan = (clone $query)
            ->where('status', 'verifikasi_kelulusan_siswa')
            ->count();

        $pendaftarHariIni = (clone $query)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $totalCabang = Cabang::count();
        $totalJurusan = Jurusan::count();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $pendaftarPerCabang = Pendaftaran::select(
                'cabangs.id',
                'cabangs.nama_cabang',
                DB::raw('COUNT(pendaftarans.id) as total')
            )
            ->join('cabangs', 'pendaftarans.cabang_id', '=', 'cabangs.id')
            ->when($request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.cabang_id', $request->cabang_id);
            })
            ->when($request->filled('jurusan_id'), function ($q) use ($request) {
                $q->where('pendaftarans.jurusan_id', $request->jurusan_id);
            })
            ->when($request->filled('periode'), function ($q) use ($request) {
                [$tahun, $bulan] = explode('-', $request->periode);

                $q->whereYear('pendaftarans.created_at', $tahun)
                  ->whereMonth('pendaftarans.created_at', $bulan);
            })
            ->groupBy('cabangs.id', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->get();

        $pendaftarPerJurusan = Pendaftaran::select(
                'jurusans.id',
                'jurusans.nama_jurusan',
                DB::raw('COUNT(pendaftarans.id) as total')
            )
            ->join('jurusans', 'pendaftarans.jurusan_id', '=', 'jurusans.id')
            ->when($request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.cabang_id', $request->cabang_id);
            })
            ->when($request->filled('jurusan_id'), function ($q) use ($request) {
                $q->where('pendaftarans.jurusan_id', $request->jurusan_id);
            })
            ->when($request->filled('periode'), function ($q) use ($request) {
                [$tahun, $bulan] = explode('-', $request->periode);

                $q->whereYear('pendaftarans.created_at', $tahun)
                  ->whereMonth('pendaftarans.created_at', $bulan);
            })
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan')
            ->orderByDesc('total')
            ->get();

        $pendaftarPerBulan = Pendaftaran::select(
                DB::raw("TO_CHAR(created_at, 'Mon') as bulan"),
                DB::raw("EXTRACT(MONTH FROM created_at) as bulan_angka"),
                DB::raw("COUNT(*) as total")
            )
            ->when($request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang_id);
            })
            ->when($request->filled('jurusan_id'), function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            })
            ->groupBy('bulan', 'bulan_angka')
            ->orderBy('bulan_angka')
            ->get();

        $ringkasanCabangJurusan = Cabang::withCount([
            'pendaftarans as total_pendaftar' => function ($q) use ($request) {
                if ($request->filled('periode')) {
                    [$tahun, $bulan] = explode('-', $request->periode);

                    $q->whereYear('created_at', $tahun)
                      ->whereMonth('created_at', $bulan);
                }

                if ($request->filled('jurusan_id')) {
                    $q->where('jurusan_id', $request->jurusan_id);
                }
            }
        ])
        ->with(['pendaftarans' => function ($q) use ($request) {
            if ($request->filled('periode')) {
                [$tahun, $bulan] = explode('-', $request->periode);

                $q->whereYear('created_at', $tahun)
                  ->whereMonth('created_at', $bulan);
            }

            if ($request->filled('jurusan_id')) {
                $q->where('jurusan_id', $request->jurusan_id);
            }

            $q->select('id', 'cabang_id', 'jurusan_id');
        }])
        ->when($request->filled('cabang_id'), function ($q) use ($request) {
            $q->where('id', $request->cabang_id);
        })
        ->get();

        return view('dashboard_admin', compact(
            'cabangs',
            'jurusans',
            'totalPendaftar',
            'totalCabang',
            'totalJurusan',
            'pendaftarHariIni',
            'menungguVerifikasi',
            'terverifikasi',
            'seleksiPretest',
            'wawancara',
            'verifikasiKelulusan',
            'pendaftarPerCabang',
            'pendaftarPerJurusan',
            'pendaftarPerBulan',
            'ringkasanCabangJurusan'
        ));
    }
}