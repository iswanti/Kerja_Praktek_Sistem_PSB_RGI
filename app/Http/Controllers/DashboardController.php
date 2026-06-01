<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\Gelombang;
use App\Models\JadwalWawancara;
use App\Models\AlumniPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperadmin = $user->role?->nama === 'Superadmin';
        $cabangAdminId = $user->cabang_id;

        $query = Pendaftaran::with(['cabang', 'jurusan', 'gelombang']);

        if (!$isSuperadmin) {
            $query->where('cabang_id', $cabangAdminId);
        }

        if ($isSuperadmin && $request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('nama_jurusan')) {
            $query->whereHas('jurusan', function ($q) use ($request) {
                $q->where('nama_jurusan', $request->nama_jurusan);
            });
        }

        if ($request->filled('gelombang_id')) {
            $query->where('gelombang_id', $request->gelombang_id);
        }

        if ($request->filled('tahun_periode')) {
            $query->whereHas('gelombang', function ($q) use ($request) {
                $q->where('tahun_periode', $request->tahun_periode);
            });
        }

        $baseQuery = Pendaftaran::query();
        if (!$isSuperadmin) {
            $baseQuery->where('cabang_id', $cabangAdminId);
        }
        if ($isSuperadmin && $request->filled('cabang_id')) {
            $baseQuery->where('cabang_id', $request->cabang_id);
        }

        $totalPendaftar = (clone $query)->count();
        $menungguVerifikasi  = (clone $baseQuery)->where('status', 'menunggu_verifikasi')->count();
        $seleksiPretest      = (clone $baseQuery)->where('status', 'seleksi_pretest')->count();
        $wawancara           = (clone $baseQuery)->where('status', 'wawancara')->count();
        $verifikasiKelulusan = (clone $baseQuery)->where('status', 'verifikasi_kelulusan_siswa')->count();
        $diterima            = (clone $baseQuery)->where('status', 'diterima')->count();
        $ditolak             = (clone $baseQuery)->where('status', 'ditolak')->count();
        $cadangan            = (clone $baseQuery)->where('status', 'cadangan')->count();

        $totalAlumniQuery = AlumniPendaftaran::query();
        if ($request->filled('tahun_periode')) {
            $totalAlumniQuery->where('angkatan', (string) $request->tahun_periode);
        }
        $totalAlumni = $totalAlumniQuery->count();

        $pendaftarHariIni = (clone $query)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $totalCabang = $isSuperadmin ? Cabang::count() : Cabang::where('id', $cabangAdminId)->count();

        $totalJurusan = Jurusan::when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('cabang_id', $cabangAdminId);
            })
            ->select('nama_jurusan')
            ->distinct()
            ->count();

        $cabangs = Cabang::when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('id', $cabangAdminId);
            })
            ->orderBy('nama_cabang')
            ->get();

        $jurusans = Jurusan::when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('cabang_id', $cabangAdminId);
            })
            ->select('nama_jurusan')
            ->distinct()
            ->orderBy('nama_jurusan')
            ->get();

        $jurusansRingkasan = Jurusan::query()
            ->when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('cabang_id', $cabangAdminId);
            })
            ->when($isSuperadmin && $request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang_id);
            })
            ->select('nama_jurusan')
            ->distinct()
            ->orderBy('nama_jurusan')
            ->get();
            
        $gelombangs = Gelombang::orderByDesc('id')->get();

        $tahunGelombang = Gelombang::pluck('tahun_periode');

        $tahunAlumni = AlumniPendaftaran::pluck('angkatan')
            ->filter()
            ->map(fn ($tahun) => (int) $tahun);

        $tahunPeriodes = $tahunGelombang
            ->merge($tahunAlumni)
            ->unique()
            ->sortDesc()
            ->values();

        $pendaftarPerCabang = Pendaftaran::select(
                'cabangs.id',
                'cabangs.nama_cabang',
                DB::raw('COUNT(pendaftarans.id) as total')
            )
            ->join('cabangs', 'pendaftarans.cabang_id', '=', 'cabangs.id')
            ->join('jurusans', 'pendaftarans.jurusan_id', '=', 'jurusans.id')
            ->join('gelombangs', 'pendaftarans.gelombang_id', '=', 'gelombangs.id')
            ->when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('pendaftarans.cabang_id', $cabangAdminId);
            })
            ->when($isSuperadmin && $request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.cabang_id', $request->cabang_id);
            })
            ->when($request->filled('nama_jurusan'), function ($q) use ($request) {
                $q->where('jurusans.nama_jurusan', $request->nama_jurusan);
            })
            ->when($request->filled('gelombang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.gelombang_id', $request->gelombang_id);
            })
            ->when($request->filled('tahun_periode'), function ($q) use ($request) {
                $q->where('gelombangs.tahun_periode', $request->tahun_periode);
            })
            ->groupBy('cabangs.id', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->get();

        $pendaftarPerJurusan = Pendaftaran::select(
                'jurusans.nama_jurusan',
                DB::raw('COUNT(pendaftarans.id) as total')
            )
            ->join('jurusans', 'pendaftarans.jurusan_id', '=', 'jurusans.id')
            ->join('gelombangs', 'pendaftarans.gelombang_id', '=', 'gelombangs.id')
            ->when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('pendaftarans.cabang_id', $cabangAdminId);
            })
            ->when($isSuperadmin && $request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.cabang_id', $request->cabang_id);
            })
            ->when($request->filled('nama_jurusan'), function ($q) use ($request) {
                $q->where('jurusans.nama_jurusan', $request->nama_jurusan);
            })
            ->when($request->filled('gelombang_id'), function ($q) use ($request) {
                $q->where('pendaftarans.gelombang_id', $request->gelombang_id);
            })
            ->when($request->filled('tahun_periode'), function ($q) use ($request) {
                $q->where('gelombangs.tahun_periode', $request->tahun_periode);
            })
            ->groupBy('jurusans.nama_jurusan')
            ->orderByDesc('total')
            ->get();

        $pendaftarPerBulan = Pendaftaran::select(
                DB::raw("TO_CHAR(created_at, 'Mon') as bulan"),
                DB::raw("EXTRACT(MONTH FROM created_at) as bulan_angka"),
                DB::raw("COUNT(*) as total")
            )
            ->when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('cabang_id', $cabangAdminId);
            })
            ->when($isSuperadmin && $request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang_id);
            })
            ->when($request->filled('nama_jurusan'), function ($q) use ($request) {
                $q->whereHas('jurusan', function ($jurusan) use ($request) {
                    $jurusan->where('nama_jurusan', $request->nama_jurusan);
                });
            })
            ->when($request->filled('gelombang_id'), function ($q) use ($request) {
                $q->where('gelombang_id', $request->gelombang_id);
            })
            ->when($request->filled('tahun_periode'), function ($q) use ($request) {
                $q->whereHas('gelombang', function ($gelombang) use ($request) {
                    $gelombang->where('tahun_periode', $request->tahun_periode);
                });
            })
            ->groupBy('bulan', 'bulan_angka')
            ->orderBy('bulan_angka')
            ->get();

        $ringkasanCabangJurusan = Cabang::with(['jurusans'])
            ->withCount([
                'pendaftarans as total_pendaftar' => function ($q) use ($request) {
                    if ($request->filled('gelombang_id')) {
                        $q->where('gelombang_id', $request->gelombang_id);
                    }

                    if ($request->filled('tahun_periode')) {
                        $q->whereHas('gelombang', function ($gelombang) use ($request) {
                            $gelombang->where('tahun_periode', $request->tahun_periode);
                        });
                    }

                    if ($request->filled('nama_jurusan')) {
                        $q->whereHas('jurusan', function ($jurusan) use ($request) {
                            $jurusan->where('nama_jurusan', $request->nama_jurusan);
                        });
                    }
                }
            ])
            ->with(['pendaftarans' => function ($q) use ($request) {
                if ($request->filled('gelombang_id')) {
                    $q->where('gelombang_id', $request->gelombang_id);
                }

                if ($request->filled('tahun_periode')) {
                    $q->whereHas('gelombang', function ($gelombang) use ($request) {
                        $gelombang->where('tahun_periode', $request->tahun_periode);
                    });
                }

                if ($request->filled('nama_jurusan')) {
                    $q->whereHas('jurusan', function ($jurusan) use ($request) {
                        $jurusan->where('nama_jurusan', $request->nama_jurusan);
                    });
                }

                $q->select('id', 'cabang_id', 'jurusan_id', 'gelombang_id')
                ->with(['jurusan', 'gelombang']);
            }])
            ->when(!$isSuperadmin, function ($q) use ($cabangAdminId) {
                $q->where('id', $cabangAdminId);
            })
            ->when($isSuperadmin && $request->filled('cabang_id'), function ($q) use ($request) {
                $q->where('id', $request->cabang_id);
            })
            ->get();

        $alumniPerJurusan = AlumniPendaftaran::select(
            'jurusans.nama_jurusan',
            DB::raw('COUNT(alumni_pendaftarans.id) as total')
        )
        ->join('jurusans', 'alumni_pendaftarans.jurusan_id', '=', 'jurusans.id')
        ->when($request->filled('tahun_periode'), function ($q) use ($request) {
            $q->where('alumni_pendaftarans.angkatan', (string) $request->tahun_periode);
        })
        ->when($request->filled('nama_jurusan'), function ($q) use ($request) {
            $q->where('jurusans.nama_jurusan', $request->nama_jurusan);
        })
        ->groupBy('jurusans.nama_jurusan')
        ->orderBy('jurusans.nama_jurusan')
        ->get();

        return view('dashboard_admin', compact(
            'cabangs',
            'jurusans',
            'jurusansRingkasan',
            'gelombangs',
            'tahunPeriodes',
            'totalPendaftar',
            'totalAlumni',
            'totalCabang',
            'totalJurusan',
            'pendaftarHariIni',
            'menungguVerifikasi',
            'seleksiPretest',
            'wawancara',
            'verifikasiKelulusan',
            'diterima',
            'ditolak',
            'cadangan',
            'pendaftarPerCabang',
            'pendaftarPerJurusan',
            'pendaftarPerBulan',
            'ringkasanCabangJurusan',
            'isSuperadmin',
            'alumniPerJurusan'
        ));
    }

    public function siswa()
    {
        $pendaftaran = Pendaftaran::with(['cabang', 'jurusan', 'gelombang'])
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        $gelombang = Gelombang::where('is_active', true)
            ->latest()
            ->first();

        $jadwalWawancaras = collect();

        if ($pendaftaran && $pendaftaran->status === 'wawancara') {
            $jadwalWawancaras = JadwalWawancara::where('gelombang_id', $pendaftaran->gelombang_id)
                ->where('cabang_id', $pendaftaran->cabang_id)
                ->where(function ($query) use ($pendaftaran) {
                    $query->where('jurusan_id', $pendaftaran->jurusan_id)
                        ->orWhereNull('jurusan_id');
                })
                ->where('is_active', true)
                ->orderBy('waktu_mulai')
                ->get();
        }

        $unsurLabels = [
            'operator' => 'Operator',
            'manajemen' => 'Manajemen',
            'scc_asrama' => 'SCC / Asrama',
            'instruktur' => 'Instruktur',
        ];

        return view('dashboard', compact(
            'pendaftaran',
            'gelombang',
            'jadwalWawancaras',
            'unsurLabels'
        ));
    }
    public function timWawancara()
    {
        $pesertaWawancara = Pendaftaran::with(['jurusan', 'cabang', 'wawancara'])
            ->where('status', 'wawancara')
            ->latest()
            ->take(10)
            ->get();

        $totalPeserta = Pendaftaran::where('status', 'wawancara')->count();

        $sudahWawancara = Pendaftaran::where('status', 'wawancara')
            ->whereHas('wawancara')
            ->count();

        $belumWawancara = Pendaftaran::where('status', 'wawancara')
            ->whereDoesntHave('wawancara')
            ->count();

        return view('dashboard_wawancara', compact(
            'pesertaWawancara',
            'totalPeserta',
            'sudahWawancara',
            'belumWawancara'
        ));
    }

    public function exportRingkasan(Request $request)
    {
        $user = auth()->user();
        $isSuperadmin = $user->role?->nama === 'Superadmin';
        $cabangAdminId = $user->cabang_id;

        $jurusans = Jurusan::query()
            ->when(!$isSuperadmin, fn ($q) => $q->where('cabang_id', $cabangAdminId))
            ->when($isSuperadmin && $request->filled('cabang_id'), fn ($q) => $q->where('cabang_id', $request->cabang_id))
            ->select('nama_jurusan')
            ->distinct()
            ->orderBy('nama_jurusan')
            ->get();

        $cabangs = Cabang::query()
            ->when(!$isSuperadmin, fn ($q) => $q->where('id', $cabangAdminId))
            ->when($isSuperadmin && $request->filled('cabang_id'), fn ($q) => $q->where('id', $request->cabang_id))
            ->orderBy('nama_cabang')
            ->get();

        $filename = 'ringkasan-pendaftar.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($cabangs, $jurusans, $request) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array_merge(
                ['No', 'Cabang'],
                $jurusans->pluck('nama_jurusan')->toArray(),
                ['Total']
            ));

            foreach ($cabangs as $index => $cabang) {
                $row = [
                    $index + 1,
                    $cabang->nama_cabang,
                ];

                $total = 0;

                foreach ($jurusans as $jurusan) {
                    $jumlah = Pendaftaran::query()
                        ->where('cabang_id', $cabang->id)
                        ->whereHas('jurusan', function ($q) use ($jurusan) {
                            $q->where('nama_jurusan', $jurusan->nama_jurusan);
                        })
                        ->when($request->filled('gelombang_id'), fn ($q) => $q->where('gelombang_id', $request->gelombang_id))
                        ->when($request->filled('tahun_periode'), function ($q) use ($request) {
                            $q->whereHas('gelombang', function ($gelombang) use ($request) {
                                $gelombang->where('tahun_periode', $request->tahun_periode);
                            });
                        })
                        ->count();

                    $row[] = $jumlah;
                    $total += $jumlah;
                }

                $row[] = $total;

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headers);
    }
}