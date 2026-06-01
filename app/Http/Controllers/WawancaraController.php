<?php

namespace App\Http\Controllers;

use App\Models\JadwalWawancara;
use App\Models\Pendaftaran;
use App\Models\Wawancara;
use Illuminate\Http\Request;

class WawancaraController extends Controller
{
    public function index(Request $request)
    {

        $query = Pendaftaran::with(['cabang', 'jurusan', 'wawancara'])
            ->whereIn('status', [
                'wawancara',
                'verifikasi_kelulusan_siswa',
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                ->orWhere('kode_pendaftaran', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('status_penilaian')) {
            if ($request->status_penilaian === 'belum') {
                $query->whereDoesntHave('wawancara');
            }

            if ($request->status_penilaian === 'selesai') {
                $query->whereHas('wawancara', function ($q) {
                    $q->whereNotNull('rekomendasi_operator')
                    ->whereNotNull('nilai_manajemen')
                    ->whereNotNull('nilai_scc')
                    ->whereNotNull('nilai_instruktur');
                });
            }
        }

        $pendaftarans = $query->latest()->paginate(10)->withQueryString();

        $user = auth()->user();

        $roleId = $user->role_id;
        $cabangId = $user->cabang_id;
        $unsurRole = $user->unsur_wawancara;
        $jurusanId = $user->jurusan_id;

        $jadwals = collect();

        if ($roleId == 2 && $cabangId) {
            $jadwals = JadwalWawancara::with(['gelombang', 'cabang', 'jurusan'])
                ->where('is_active', true)
                ->where('cabang_id', $cabangId)
                ->orderByRaw("
                    CASE unsur
                        WHEN 'operator' THEN 1
                        WHEN 'manajemen' THEN 2
                        WHEN 'scc_asrama' THEN 3
                        WHEN 'instruktur' THEN 4
                        ELSE 5
                    END
                ")
                ->get();

        } elseif ($roleId == 3 && $cabangId && $unsurRole) {
            $jadwals = JadwalWawancara::with(['gelombang', 'cabang', 'jurusan'])
                ->where('is_active', true)
                ->where('cabang_id', $cabangId)
                ->where('unsur', $unsurRole)
                ->when($unsurRole === 'instruktur', function ($q) use ($jurusanId) {
                    $q->where('jurusan_id', $jurusanId);
                })
                ->get();
        }

        return view('wawancara.index', compact('pendaftarans', 'jadwals'));
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with(['cabang', 'jurusan', 'wawancara'])->findOrFail($id);
        $wawancara   = $pendaftaran->wawancara ?? new Wawancara();

        $user      = auth()->user();
        $roleName  = $user->role?->nama; // nama role: 'Tim Wawancara', 'Admin', dll
        $unsurRole = $user->unsur_wawancara; // kolom langsung di tabel users

        $isTimWawancara = $roleName === 'Tim Wawancara';

        if ($isTimWawancara && $unsurRole) {
            // Normalisasi: 'scc_asrama' → 'scc' agar cocok dengan kondisi blade
            $unsur = match($unsurRole) {
                'scc_asrama' => 'scc',
                default      => $unsurRole, // operator, manajemen, instruktur
            };

            // Cek sudah diisi
            $sudahDiisi = match($unsur) {
                'operator'   => !empty($wawancara->rekomendasi_operator),
                'manajemen'  => !is_null($wawancara->nilai_manajemen),
                'scc'        => !is_null($wawancara->nilai_scc),
                'instruktur' => !is_null($wawancara->nilai_instruktur),
                default      => false,
            };

            if ($sudahDiisi) $unsur = 'sudah_diisi';

        } else {
            // Admin/Superadmin: sequential
            if (empty($wawancara->rekomendasi_operator))   $unsur = 'operator';
            elseif (is_null($wawancara->nilai_manajemen))  $unsur = 'manajemen';
            elseif (is_null($wawancara->nilai_scc))        $unsur = 'scc';
            elseif (is_null($wawancara->nilai_instruktur)) $unsur = 'instruktur';
            else                                            $unsur = 'selesai';
        }

        return view('wawancara.create', compact('pendaftaran', 'wawancara', 'unsur'));
    }

    public function store(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $user      = auth()->user();
        $roleName  = $user->role?->nama;
        $unsurRole = $user->unsur_wawancara;

        // Tentukan unsur dari role, bukan dari request
        if ($roleName === 'Tim Wawancara' && $unsurRole) {
            $unsur = match($unsurRole) {
                'scc_asrama' => 'scc',
                default      => $unsurRole,
            };
        } else {
            // Admin: percaya input (atau bisa sequential juga)
            $unsur = $request->unsur;
        }

        $wawancara = Wawancara::firstOrCreate(
            ['pendaftaran_id' => $pendaftaran->id],
            [
                'pekerjaan_ayah'      => $pendaftaran->pekerjaan_wali,
                'pekerjaan_ibu'       => $pendaftaran->pekerjaan_ibu,
                'pendapatan_orangtua' => $pendaftaran->pendapatan_keluarga,
                'motivasi'            => $pendaftaran->motivasi,
                'riwayat_penyakit'    => $pendaftaran->penyakit,
            ]
        );

        if ($request->unsur === 'operator') {
            $validated = $request->validate([
                'nama_operator' => 'required|string|max:255',
                'rekomendasi' => 'required|string|max:255',
                'rekomendasi_operator' => 'required|string|max:255',
            ]);

            $wawancara->fill($validated);
        }

        if ($request->unsur === 'manajemen') {
            $validated = $request->validate([
                'nama_pewawancara_manajemen' => 'required|string|max:255',
                'pekerjaan_ayah' => 'nullable|string|max:255',
                'pekerjaan_ibu' => 'nullable|string|max:255',
                'pendapatan_orangtua' => 'required|string|max:255',
                'pelanggaran_berat' => 'nullable|string|max:255',
                'motivasi' => 'nullable|string',
                'riwayat_penyakit' => 'nullable|string|max:255',
                'kondisi_rumah' => 'nullable|string|max:255',
                'tingkat_keduafaan' => 'nullable|string|max:255',
                'catatan_manajemen' => 'nullable|string',
                'nilai_manajemen' => 'required|numeric|min:0|max:100',
            ]);

            $wawancara->fill($validated);
        }

        if ($request->unsur === 'scc') {
            $validated = $request->validate([
                'nama_pewawancara_scc' => 'required|string|max:255',
                'merokok' => 'required|string|max:50',
                'mengaji' => 'required|string|max:50',
                'sholat' => 'required|string|max:50',
                'catatan_scc' => 'nullable|string',
                'nilai_scc' => 'required|numeric|min:0|max:100',
            ]);

            $wawancara->fill($validated);
        }

        if ($request->unsur === 'instruktur') {
            $validated = $request->validate([
                'nama_instruktur' => 'required|string|max:255',
                'rencana_setelah_lulus' => 'nullable|string',
                'level_pengetahuan_materi' => 'nullable|string|max:255',
                'kemampuan_dasar' => 'nullable|string|max:255',
                'motivasi_belajar' => 'nullable|string|max:255',
                'catatan_instruktur' => 'nullable|string',
                'nilai_instruktur' => 'required|numeric|min:0|max:100',
            ]);

            $wawancara->fill($validated);
        }

        $wawancara->nilai_akhir = $wawancara->hitungNilaiAkhir();
        $wawancara->rekomendasi_akhir = $wawancara->tentukanRekomendasi();

        $semuaSelesai =
            !empty($wawancara->rekomendasi_operator) &&
            !is_null($wawancara->nilai_manajemen) &&
            !is_null($wawancara->nilai_scc) &&
            !is_null($wawancara->nilai_instruktur);

        if ($semuaSelesai) {
            $wawancara->status = 'selesai';

            $pendaftaran->update([
                'status' => 'verifikasi_kelulusan_siswa',
            ]);
        } else {
            $wawancara->status = 'draft';

            $pendaftaran->update([
                'status' => 'wawancara',
            ]);
        }

        $wawancara->save();

        return redirect()
            ->route('admin.wawancara.index')
            ->with('success', 'Data wawancara berhasil disimpan.');
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with(['cabang', 'jurusan', 'wawancara'])
            ->findOrFail($id);

        $wawancara = $pendaftaran->wawancara;

        return view('wawancara.show', compact('pendaftaran', 'wawancara'));
    }
}