<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Jurusan;
use App\Models\Cabang;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $cabangs = Cabang::with('jurusans')->get();
        $jurusans = Jurusan::all();

        $query = Soal::with('jurusan');

        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('tipe_soal')) {
            $query->where('tipe_soal', $request->tipe_soal);
        }

        $soals = $query->orderBy('urutan')->paginate(10);

        return view('admin.soal.index', compact('cabangs', 'jurusans', 'soals'));
    }

    public function create(Request $request)
    {
        $jurusans = Jurusan::all();
        $jurusanId = $request->jurusan_id;

        return view('admin.soal.create', compact('jurusans', 'jurusanId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_soal'     => 'required|in:umum,kejuruan',
            'jurusan_id'    => $request->tipe_soal === 'kejuruan'
                                ? 'required|exists:jurusans,id'
                                : 'nullable|exists:jurusans,id',
            'tipe'          => 'required|in:pilihan_ganda,essay',
            'pertanyaan'    => 'required|string',
            'deskripsi'     => 'nullable|string',
            'pilihan_a'     => 'nullable|string',
            'pilihan_b'     => 'nullable|string',
            'pilihan_c'     => 'nullable|string',
            'pilihan_d'     => 'nullable|string',
            'pilihan_e'     => 'nullable|string',
            'jawaban_benar' => 'nullable|string',
            'jawaban_essay' => 'nullable|string',
            'bobot'         => 'required|integer|min:0',
        ]);

        if ($request->tipe === 'pilihan_ganda' && !$request->jawaban_benar) {
            return back()->withInput()->withErrors(['jawaban_benar' => 'Pilih salah satu jawaban benar.']);
        }

        if ($request->tipe === 'essay' && !$request->jawaban_essay) {
            return back()->withInput()->withErrors(['jawaban_essay' => 'Isi jawaban acuan essay terlebih dahulu.']);
        }

        $jurusanId = $request->tipe_soal === 'kejuruan' ? $request->jurusan_id : null;

        $urutan = (Soal::where('jurusan_id', $jurusanId)->max('urutan') ?? 0) + 1;

        Soal::create([
            'tipe_soal'     => $request->tipe_soal,
            'jurusan_id'    => $jurusanId,
            'tipe'          => $request->tipe,
            'pertanyaan'    => $request->pertanyaan,
            'deskripsi'     => $request->deskripsi,
            'pilihan_a'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_a : null,
            'pilihan_b'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_b : null,
            'pilihan_c'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_c : null,
            'pilihan_d'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_d : null,
            'pilihan_e'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_e : null,
            'jawaban_benar' => $request->tipe === 'pilihan_ganda'
                                ? $request->jawaban_benar
                                : $request->jawaban_essay,
            'bobot'         => $request->bobot,
            'urutan'        => $urutan,
        ]);

        return redirect()
            ->route('admin.soal.index')
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    public function edit(Soal $soal)
    {
        $jurusans = Jurusan::all();

        return view('admin.soal.edit', compact('soal', 'jurusans'));
    }

    public function update(Request $request, Soal $soal)
    {
        $request->validate([
            'tipe_soal'     => 'required|in:umum,kejuruan',
            'jurusan_id'    => $request->tipe_soal === 'kejuruan'
                                ? 'required|exists:jurusans,id'
                                : 'nullable|exists:jurusans,id',
            'tipe'          => 'required|in:pilihan_ganda,essay',
            'pertanyaan'    => 'required|string',
            'deskripsi'     => 'nullable|string',
            'bobot'         => 'required|integer|min:0',
            'pilihan_a'     => 'nullable|string',
            'pilihan_b'     => 'nullable|string',
            'pilihan_c'     => 'nullable|string',
            'pilihan_d'     => 'nullable|string',
            'pilihan_e'     => 'nullable|string',
            'jawaban_benar' => 'nullable|string',
            'jawaban_essay' => 'nullable|string',
        ]);

        $jurusanId = $request->tipe_soal === 'kejuruan' ? $request->jurusan_id : null;

        $soal->update([
            'tipe_soal'     => $request->tipe_soal,
            'jurusan_id'    => $jurusanId,
            'tipe'          => $request->tipe,
            'pertanyaan'    => $request->pertanyaan,
            'deskripsi'     => $request->deskripsi,
            'pilihan_a'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_a : null,
            'pilihan_b'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_b : null,
            'pilihan_c'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_c : null,
            'pilihan_d'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_d : null,
            'pilihan_e'     => $request->tipe === 'pilihan_ganda' ? $request->pilihan_e : null,
            'jawaban_benar' => $request->tipe === 'pilihan_ganda'
                                ? $request->jawaban_benar
                                : $request->jawaban_essay,
            'bobot'         => $request->bobot,
        ]);

        return redirect()
            ->route('admin.soal.index', ['jurusan_id' => $soal->jurusan_id])
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Soal $soal)
    {
        $jurusanId = $soal->jurusan_id;
        $soal->delete();

        return redirect()
            ->route('admin.soal.index', ['jurusan_id' => $jurusanId])
            ->with('success', 'Soal berhasil dihapus!');
    }
}