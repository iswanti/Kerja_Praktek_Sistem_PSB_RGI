<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Wawancara;
use Illuminate\Http\Request;

class WawancaraController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['cabang', 'jurusan', 'wawancara'])
            ->where('status', 'wawancara');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('kode_pendaftaran', 'ILIKE', "%{$search}%");
            });
        }

        $pendaftarans = $query->latest()->paginate(10)->withQueryString();

        return view('wawancara.index', compact('pendaftarans'));
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with(['cabang', 'jurusan', 'wawancara'])
            ->findOrFail($id);

        $wawancara = $pendaftaran->wawancara ?? new Wawancara();

        if (empty($wawancara->rekomendasi_operator)) {
            $unsur = 'operator';
        } elseif (is_null($wawancara->nilai_manajemen)) {
            $unsur = 'manajemen';
        } elseif (is_null($wawancara->nilai_scc)) {
            $unsur = 'scc';
        } elseif (is_null($wawancara->nilai_instruktur)) {
            $unsur = 'instruktur';
        } else {
            $unsur = 'selesai';
        }

        return view('wawancara.create', compact(
            'pendaftaran',
            'wawancara',
            'unsur'
        ));
    }

    public function store(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $wawancara = Wawancara::firstOrCreate([
            'pendaftaran_id' => $pendaftaran->id,
        ]);

        if ($request->unsur === 'operator') {
            $validated = $request->validate([
                'nama_operator' => 'required|string|max:255',
                'rekomendasi_operator' => 'required|string|max:255',
            ]);

            $wawancara->fill($validated);
        }

        if ($request->unsur === 'manajemen') {
            $validated = $request->validate([
                'nama_pewawancara_manajemen' => 'required|string|max:255',
                'pendapatan_orangtua' => 'nullable|string|max:255',
                'pelanggaran_berat' => 'nullable|string|max:255',
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

        if ($wawancara->semua_unsur_selesai) {
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
            ->route('wawancara.index')
            ->with('success', 'Data wawancara berhasil disimpan.');
    }
}