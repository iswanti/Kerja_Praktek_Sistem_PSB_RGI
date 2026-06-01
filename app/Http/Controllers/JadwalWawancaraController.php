<?php

namespace App\Http\Controllers;

use App\Models\JadwalWawancara;
use App\Models\Gelombang;
use App\Models\Cabang;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalWawancaraController extends Controller
{

    public function index(Request $request)
    {
        $query = JadwalWawancara::with(['gelombang', 'cabang', 'jurusan']);

        if ($request->search) {
            $query->whereHas('gelombang', function ($q) use ($request) {
                $q->where('nama_gelombang', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('cabang', function ($q) use ($request) {
                $q->where('nama_cabang', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('jurusan', function ($q) use ($request) {
                $q->where('nama_jurusan', 'like', '%' . $request->search . '%');
            })
            ->orWhere('unsur', 'like', '%' . $request->search . '%');
        }

        $jadwals = $query->latest()->paginate(10)->withQueryString();

        return view('admin.jadwal-wawancara.index', compact('jadwals'));
    }

    public function create()
    {
        $gelombangs = Gelombang::where('is_active', true)->get();
        $cabangs = Cabang::with(['jurusans' => function ($q) {
            $q->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

        $unsurs = [
            'operator' => 'Operator',
            'manajemen' => 'Manajemen',
            'scc_asrama' => 'SCC / Asrama',
            'instruktur' => 'Instruktur',
        ];

        return view('admin.jadwal-wawancara.create', compact(
            'gelombangs',
            'cabangs',
            'unsurs'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
            'cabang_id' => 'required|exists:cabangs,id',

            'jurusan_id' => [
                Rule::requiredIf($request->unsur === 'instruktur'),
                'nullable',
                'exists:jurusans,id',
            ],

            'unsur' => 'required|in:operator,manajemen,scc_asrama,instruktur',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'link_wawancara' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['unsur'] !== 'instruktur') {
            $validated['jurusan_id'] = null;
        }

        $exists = JadwalWawancara::where('gelombang_id', $validated['gelombang_id'])
            ->where('cabang_id', $validated['cabang_id'])
            ->where('unsur', $validated['unsur'])
            ->when(
                $validated['jurusan_id'],
                fn ($q) => $q->where('jurusan_id', $validated['jurusan_id']),
                fn ($q) => $q->whereNull('jurusan_id')
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'unsur' => 'Jadwal wawancara untuk gelombang, cabang, jurusan, dan unsur ini sudah dibuat.'
                ]);
        }

        JadwalWawancara::create([
            ...$validated,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.jadwal-wawancara.index')
            ->with('success', 'Jadwal wawancara berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $gelombangs = Gelombang::where('is_active', true)->get();

        $cabangs = Cabang::with(['jurusans' => function ($q) {
                $q->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get();

        $unsurs = [
            'operator' => 'Operator',
            'manajemen' => 'Manajemen',
            'scc_asrama' => 'SCC / Asrama',
            'instruktur' => 'Instruktur',
        ];

        return view('admin.jadwal-wawancara.edit', compact(
            'jadwal',
            'gelombangs',
            'cabangs',
            'unsurs'
        ));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $validated = $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
            'cabang_id' => 'required|exists:cabangs,id',

            'jurusan_id' => [
                Rule::requiredIf($request->unsur === 'instruktur'),
                'nullable',
                'exists:jurusans,id',
            ],

            'unsur' => 'required|in:operator,manajemen,scc_asrama,instruktur',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'link_wawancara' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        // selain instruktur, jurusan dikosongkan
        if ($validated['unsur'] !== 'instruktur') {
            $validated['jurusan_id'] = null;
        }

        $exists = JadwalWawancara::where('gelombang_id', $validated['gelombang_id'])
            ->where('cabang_id', $validated['cabang_id'])
            ->where('unsur', $validated['unsur'])
            ->where('id', '!=', $jadwal->id)
            ->when(
                $validated['jurusan_id'],
                fn ($q) => $q->where('jurusan_id', $validated['jurusan_id']),
                fn ($q) => $q->whereNull('jurusan_id')
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'unsur' => 'Jadwal wawancara untuk gelombang, cabang, jurusan, dan unsur ini sudah dibuat.'
                ]);
        }

        $jadwal->update([
            ...$validated,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.jadwal-wawancara.index')
            ->with('success', 'Jadwal wawancara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);
        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal-wawancara.index')
            ->with('success', 'Jadwal wawancara berhasil dihapus.');
    }
}