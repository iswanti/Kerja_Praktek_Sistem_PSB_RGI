<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Soal;
use App\Models\JawabanPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeleksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = Pendaftaran::with(['jurusan.cabang'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $soals = collect();
        $gelombang = $pendaftaran?->gelombang;
        $sudahMengerjakan = false;
        $jumlahSoal = 0;

        $jawabanPeserta = collect();

        if ($pendaftaran) {
            $totalBankSoal = Soal::where('jurusan_id', $pendaftaran->jurusan_id)->count();
            $jumlahSoal = min(25, $totalBankSoal);

            $sudahMengerjakan = JawabanPeserta::where('pendaftaran_id', $pendaftaran->id)
                ->exists();

            if ($sudahMengerjakan) {
                $jawabanPeserta = JawabanPeserta::with('soal')
                    ->where('pendaftaran_id', $pendaftaran->id)
                    ->orderBy('id')
                    ->get();
            }
        }

        $pretestAktif = false;
        if ($gelombang) {

            $pretestAktif = now()->between(
                $gelombang->pretest_mulai,
                $gelombang->pretest_selesai
            );

        }
        return view('seleksi.index', compact(
            'pendaftaran',
            'soals',
            'sudahMengerjakan',
            'jumlahSoal',
            'jawabanPeserta',
            'pretestAktif',
            'gelombang'
        ));
    }

    public function pretest()
    {
        $user = Auth::user();

        $pendaftaran = Pendaftaran::with(['jurusan', 'gelombang'])
            ->where('user_id', $user->id)
            ->where('status', 'seleksi_pretest')
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()
                ->route('seleksi.index')
                ->with('error', 'Pendaftaran Anda belum diverifikasi atau tidak dalam tahap pretest.');
        }

        $gelombang = $pendaftaran->gelombang;

        if (!$gelombang || !now()->between($gelombang->pretest_mulai, $gelombang->pretest_selesai)) {
            return redirect()
                ->route('seleksi.index')
                ->with('error', 'Pretest belum dibuka atau jadwal sudah berakhir.');
        }

        $sudahMengerjakan = JawabanPeserta::where('pendaftaran_id', $pendaftaran->id)->exists();

        if ($sudahMengerjakan) {
            return redirect()
                ->route('seleksi.index')
                ->with('info', 'Anda sudah mengerjakan pretest.');
        }

        // 5 soal umum
        $soalUmum = Soal::where('tipe_soal', 'umum')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // 20 soal kejuruan sesuai jurusan peserta
        $soalKejuruan = Soal::where('tipe_soal', 'kejuruan')
            ->where('jurusan_id', $pendaftaran->jurusan_id)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        // Gabungkan: umum dulu, baru kejuruan
        $soals = $soalUmum->concat($soalKejuruan);

        if ($soals->isEmpty()) {
            return redirect()
                ->route('seleksi.index')
                ->with('error', 'Soal pretest belum tersedia.');
        }

        $durasiPretest = $gelombang->durasi_pretest ?? 30;

        return view('seleksi.pretest', compact(
            'pendaftaran',
            'soals',
            'soalUmum',
            'soalKejuruan',
            'durasiPretest'
        ));
    }

    public function submitPretest(Request $request)
    {
        $user = Auth::user();

        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('status', 'seleksi_pretest')
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()
                ->route('seleksi.index')
                ->with('error', 'Anda belum melewati tahap verifikasi.');
        }

        // Cek apakah sudah pernah mengerjakan
        $sudahMengerjakan = JawabanPeserta::where('pendaftaran_id', $pendaftaran->id)
            ->exists();

        if ($sudahMengerjakan) {
            return redirect()
                ->route('seleksi.index')
                ->with('info', 'Anda sudah mengerjakan pretest.');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil hanya soal yang benar-benar tampil di form
        |--------------------------------------------------------------------------
        */
        $soalIds = collect($request->all())
            ->keys()
            ->filter(fn($key) => str_starts_with($key, 'soal_'))
            ->map(fn($key) => (int) str_replace('soal_', '', $key))
            ->values();

        $soals = Soal::whereIn('id', $soalIds)->get();

        if ($soals->isEmpty()) {
            return redirect()
                ->route('seleksi.index')
                ->with('error', 'Tidak ada jawaban yang dikirim.');
        }

        $totalNilai = 0;
        $totalBobot = 0;

        foreach ($soals as $soal) {
            $jawaban = $request->input('soal_' . $soal->id);

            if ($jawaban === null || $jawaban === '') {
                continue;
            }

            $isBenar = null;
            $nilai = 0;

            // Pilihan Ganda
            if ($soal->tipe === 'pilihan_ganda') {
                $isBenar = strtoupper(trim($jawaban)) === strtoupper(trim($soal->jawaban_benar));
                $nilai = $isBenar ? $soal->bobot : 0;
            }

            // Essay
            if ($soal->tipe === 'essay') {
                $nilai = $this->hitungNilaiEssay(
                    $jawaban,
                    $soal->jawaban_benar,
                    $soal->bobot
                );

                $isBenar = $nilai == $soal->bobot;
            }

            // Simpan jawaban
            JawabanPeserta::create([
                'pendaftaran_id' => $pendaftaran->id,
                'user_id'        => $user->id,
                'soal_id'        => $soal->id,
                'jawaban'        => $jawaban,
                'is_benar'       => $isBenar,
                'nilai'          => $nilai,
            ]);

            // Akumulasi
            $totalNilai += $nilai;
            $totalBobot += $soal->bobot;
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung nilai akhir (0-100)
        |--------------------------------------------------------------------------
        | Misal:
        | - 25 soal
        | - Bobot masing-masing 10
        | - Total bobot = 250
        | - Nilai peserta = 170
        | - Nilai akhir = 170/250*100 = 68
        */
        $nilaiAkhir = $totalBobot > 0
            ? round(($totalNilai / $totalBobot) * 100, 2)
            : 0;

        // Simpan nilai pretest ke tabel pendaftarans
        $pendaftaran->update([
            'status'        => 'wawancara',
            'nilai_pretest' => $nilaiAkhir,
        ]);

        return redirect()
            ->route('seleksi.index')
            ->with('success', 'Pretest berhasil dikerjakan. Nilai Anda: ' . $nilaiAkhir);
    }

    private function hitungNilaiEssay($jawabanPeserta, $kunciJawaban, $bobot)
    {
        // Bersihkan dan lowercase
        $jawabanPeserta = strtolower(trim($jawabanPeserta));
        $kunciJawaban   = strtolower(trim($kunciJawaban));

        // Hapus karakter selain huruf, angka, spasi, koma
        $jawabanPeserta = preg_replace('/[^a-zA-Z0-9\s]/', '', $jawabanPeserta);
        $kunciJawaban   = preg_replace('/[^a-zA-Z0-9\s,]/', '', $kunciJawaban);

        // Split kunci jawaban by koma ATAU spasi
        $keywords = preg_split('/[\s,]+/', $kunciJawaban);
        $keywords = array_filter(array_map('trim', $keywords));

        // Split jawaban peserta by spasi
        $kataJawaban = preg_split('/\s+/', $jawabanPeserta);
        $kataJawaban = array_filter(array_map('trim', $kataJawaban));

        // Cek apakah ada keyword yang cocok dengan kata jawaban peserta
        $cocok = 0;
        foreach ($keywords as $keyword) {
            foreach ($kataJawaban as $kata) {
                // Exact match atau keyword ada di dalam jawaban (substring)
                if ($kata === $keyword || str_contains($jawabanPeserta, $keyword)) {
                    $cocok++;
                    break;
                }
            }
        }

        $totalKeywords = count($keywords);

        if ($totalKeywords === 0) return 0;

        $persentase = $cocok / $totalKeywords;

        if ($persentase >= 0.5) {
            return $bobot; // 50% keyword cocok → nilai penuh
        }

        if ($persentase >= 0.25) {
            return round($bobot / 2); // 25% keyword cocok → nilai setengah
        }

        return 0;
    }

}