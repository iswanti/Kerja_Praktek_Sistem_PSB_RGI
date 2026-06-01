<?php

namespace App\Http\Controllers;

use App\Models\Gelombang;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Notifications\StatusPendaftaranNotification;
use Illuminate\Http\Request;

class GelombangController extends Controller
{

    public function index(Request $request)
    {
        $query = Gelombang::query();

        if ($request->search) {
            $query->where('nama_gelombang', 'like', '%' . $request->search . '%');
        }

        $gelombangs = $query
            ->latest()
            ->paginate(10);

        return view('admin.gelombang.index', compact('gelombangs'));
    }

    public function create()
    {
        return view('admin.gelombang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gelombang' => 'required',
            'tahun_periode' => 'required',

            'pendaftaran_mulai' => 'nullable',
            'pendaftaran_selesai' => 'nullable',

            'pretest_mulai' => 'nullable',
            'pretest_selesai' => 'nullable',
            'durasi_pretest' => 'nullable|integer',

            'wawancara_mulai' => 'nullable',
            'wawancara_selesai' => 'nullable',

            'pengumuman_mulai' => 'nullable',
        ]);

        // Gelombang::create([
        //     ...$validated,
        //     'is_active' => $request->has('is_active'),
        // ]);
        $gelombang = Gelombang::create([
            ...$validated,
            'is_active' => $request->has('is_active'),
        ]);

        $this->kirimNotifikasiGelombang($gelombang);

        return redirect()
            ->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $gelombang = Gelombang::findOrFail($id);

        return view('admin.gelombang.edit', compact('gelombang'));
    }

    public function update(Request $request, $id)
    {
        $gelombang = Gelombang::findOrFail($id);

        $validated = $request->validate([
            'nama_gelombang' => 'required',
            'tahun_periode' => 'required',

            'pendaftaran_mulai' => 'nullable',
            'pendaftaran_selesai' => 'nullable',

            'pretest_mulai' => 'nullable',
            'pretest_selesai' => 'nullable',
            'durasi_pretest' => 'nullable|integer',

            'wawancara_mulai' => 'nullable',
            'wawancara_selesai' => 'nullable',

            'pengumuman_mulai' => 'nullable',
        ]);

        $oldGelombang = clone $gelombang;

        $gelombang->update([
            ...$validated,
            'is_active' => $request->has('is_active'),
        ]);

        $this->kirimNotifikasiGelombang($gelombang, $oldGelombang);

        return redirect()
            ->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gelombang = Gelombang::findOrFail($id);

        $gelombang->delete();

        return redirect()
            ->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil dihapus.');
    }

    private function kirimNotifikasiGelombang(Gelombang $gelombang, ?Gelombang $oldGelombang = null): void
    {
        $pendaftaranDibuka = $gelombang->is_active
            && $gelombang->pendaftaran_mulai <= now()
            && $gelombang->pendaftaran_selesai >= now();

        $pretestDibuka = $gelombang->is_active
            && $gelombang->pretest_mulai <= now()
            && $gelombang->pretest_selesai >= now();

        if ($pendaftaranDibuka) {
            User::whereHas('role', fn ($q) => $q->where('nama', 'Siswa'))
                ->get()
                ->each(fn ($user) =>
                    $user->notify(new StatusPendaftaranNotification(tipe: 'pendaftaran_dibuka'))
                );
        }

        if ($pretestDibuka) {
            Pendaftaran::where('gelombang_id', $gelombang->id)
                ->where('status', 'seleksi_pretest')
                ->with('user')
                ->get()
                ->each(fn ($pendaftaran) =>
                    $pendaftaran->user?->notify(new StatusPendaftaranNotification(tipe: 'pretest_dibuka'))
                );
        }
        
        $pendaftaranDitutup = $gelombang->is_active
            && $gelombang->pendaftaran_selesai < now();

        $pretestDitutup = $gelombang->is_active
            && $gelombang->pretest_selesai < now();

        if ($pendaftaranDitutup) {
            User::whereHas('role', fn ($q) => $q->where('nama', 'Siswa'))
                ->get()
                ->each(fn ($user) =>
                    $user->notify(new StatusPendaftaranNotification(tipe: 'pendaftaran_ditutup'))
                );
        }

        if ($pretestDitutup) {
            Pendaftaran::where('gelombang_id', $gelombang->id)
                ->where('status', 'seleksi_pretest')
                ->with('user')
                ->get()
                ->each(fn ($pendaftaran) =>
                    $pendaftaran->user?->notify(new StatusPendaftaranNotification(tipe: 'pretest_ditutup'))
                );
        }
    }
}
