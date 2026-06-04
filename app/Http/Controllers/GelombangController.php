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

        $gelombangs = $query->latest()->paginate(10);

        return view('admin.gelombang.index', compact('gelombangs'));
    }

    public function create()
    {
        return view('admin.gelombang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gelombang'     => 'required',
            'tahun_periode'      => 'required',
            'pendaftaran_mulai'  => 'nullable|date',
            'pendaftaran_selesai'=> 'nullable|date|after_or_equal:pendaftaran_mulai',
            'pretest_mulai'      => 'nullable|date',
            'pretest_selesai'    => 'nullable|date|after_or_equal:pretest_mulai',
            'durasi_pretest'     => 'nullable|integer',
            'wawancara_mulai'    => 'nullable|date',
            'wawancara_selesai'  => 'nullable|date|after_or_equal:wawancara_mulai',
            'pengumuman_mulai'   => 'nullable|date',
        ], [
            'pendaftaran_selesai.after_or_equal' => 'Tanggal selesai pendaftaran tidak boleh sebelum tanggal mulai.',
            'pretest_selesai.after_or_equal'     => 'Tanggal selesai pretest tidak boleh sebelum tanggal mulai.',
            'wawancara_selesai.after_or_equal'   => 'Tanggal selesai wawancara tidak boleh sebelum tanggal mulai.',
        ]);

        // Cek overlap gelombang aktif di tahun yang sama
        if ($request->has('is_active') && $validated['pendaftaran_mulai'] && $validated['pendaftaran_selesai']) {
            $overlap = Gelombang::where('is_active', true)
                ->where('tahun_periode', $validated['tahun_periode'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('pendaftaran_mulai', [
                            $validated['pendaftaran_mulai'],
                            $validated['pendaftaran_selesai'],
                        ])
                        ->orWhereBetween('pendaftaran_selesai', [
                            $validated['pendaftaran_mulai'],
                            $validated['pendaftaran_selesai'],
                        ])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('pendaftaran_mulai', '<=', $validated['pendaftaran_mulai'])
                              ->where('pendaftaran_selesai', '>=', $validated['pendaftaran_selesai']);
                        });
                })
                ->exists();

            if ($overlap) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'pendaftaran_mulai' => 'Sudah ada gelombang aktif di rentang tanggal pendaftaran ini pada tahun ' . $validated['tahun_periode'] . '.',
                    ]);
            }
        }

        // Cek maksimal 2 gelombang per tahun
        $jumlahGelombang = Gelombang::where('tahun_periode', $validated['tahun_periode'])->count();

        if ($jumlahGelombang >= 2) {
            return back()
                ->withInput()
                ->withErrors([
                    'tahun_periode' => 'Tahun ' . $validated['tahun_periode'] . ' sudah memiliki 2 gelombang (maksimal).',
                ]);
        }

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
            'nama_gelombang'     => 'required',
            'tahun_periode'      => 'required',
            'pendaftaran_mulai'  => 'nullable|date',
            'pendaftaran_selesai'=> 'nullable|date|after_or_equal:pendaftaran_mulai',
            'pretest_mulai'      => 'nullable|date',
            'pretest_selesai'    => 'nullable|date|after_or_equal:pretest_mulai',
            'durasi_pretest'     => 'nullable|integer',
            'wawancara_mulai'    => 'nullable|date',
            'wawancara_selesai'  => 'nullable|date|after_or_equal:wawancara_mulai',
            'pengumuman_mulai'   => 'nullable|date',
        ], [
            'pendaftaran_selesai.after_or_equal' => 'Tanggal selesai pendaftaran tidak boleh sebelum tanggal mulai.',
            'pretest_selesai.after_or_equal'     => 'Tanggal selesai pretest tidak boleh sebelum tanggal mulai.',
            'wawancara_selesai.after_or_equal'   => 'Tanggal selesai wawancara tidak boleh sebelum tanggal mulai.',
        ]);

        // Cek overlap gelombang aktif (kecuali diri sendiri)
        if ($request->has('is_active') && $validated['pendaftaran_mulai'] && $validated['pendaftaran_selesai']) {
            $overlap = Gelombang::where('is_active', true)
                ->where('tahun_periode', $validated['tahun_periode'])
                ->where('id', '!=', $id) // abaikan gelombang ini sendiri
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('pendaftaran_mulai', [
                            $validated['pendaftaran_mulai'],
                            $validated['pendaftaran_selesai'],
                        ])
                        ->orWhereBetween('pendaftaran_selesai', [
                            $validated['pendaftaran_mulai'],
                            $validated['pendaftaran_selesai'],
                        ])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('pendaftaran_mulai', '<=', $validated['pendaftaran_mulai'])
                              ->where('pendaftaran_selesai', '>=', $validated['pendaftaran_selesai']);
                        });
                })
                ->exists();

            if ($overlap) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'pendaftaran_mulai' => 'Sudah ada gelombang aktif di rentang tanggal pendaftaran ini pada tahun ' . $validated['tahun_periode'] . '.',
                    ]);
            }
        }

        // Cek maksimal 2 gelombang per tahun (kecuali diri sendiri)
        $jumlahGelombang = Gelombang::where('tahun_periode', $validated['tahun_periode'])
            ->where('id', '!=', $id)
            ->count();

        if ($jumlahGelombang >= 2) {
            return back()
                ->withInput()
                ->withErrors([
                    'tahun_periode' => 'Tahun ' . $validated['tahun_periode'] . ' sudah memiliki 2 gelombang (maksimal).',
                ]);
        }

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

        $pendaftaranDitutup = $gelombang->is_active
            && $gelombang->pendaftaran_selesai < now();

        $pretestDitutup = $gelombang->is_active
            && $gelombang->pretest_selesai < now();

        // Notif pendaftaran dibuka → hanya siswa yang belum pernah diterima
        if ($pendaftaranDibuka) {
            User::whereHas('role', fn($q) => $q->where('nama', 'Siswa'))
                ->whereDoesntHave('pendaftarans', fn($q) =>
                    $q->whereIn('status', ['diterima'])
                )
                ->get()
                ->each(fn($user) =>
                    $user->notify(new StatusPendaftaranNotification(tipe: 'pendaftaran_dibuka'))
                );
        }

        // Notif pretest dibuka → hanya peserta gelombang ini yang statusnya seleksi_pretest
        if ($pretestDibuka) {
            Pendaftaran::where('gelombang_id', $gelombang->id)
                ->where('status', 'seleksi_pretest')
                ->with('user')
                ->get()
                ->each(fn($pendaftaran) =>
                    $pendaftaran->user?->notify(new StatusPendaftaranNotification(tipe: 'pretest_dibuka'))
                );
        }

        // Notif pendaftaran ditutup
        if ($pendaftaranDitutup) {
            User::whereHas('role', fn($q) => $q->where('nama', 'Siswa'))
                ->whereDoesntHave('pendaftarans', fn($q) =>
                    $q->whereIn('status', ['diterima'])
                )
                ->get()
                ->each(fn($user) =>
                    $user->notify(new StatusPendaftaranNotification(tipe: 'pendaftaran_ditutup'))
                );
        }

        // Notif pretest ditutup
        if ($pretestDitutup) {
            Pendaftaran::where('gelombang_id', $gelombang->id)
                ->where('status', 'seleksi_pretest')
                ->with('user')
                ->get()
                ->each(fn($pendaftaran) =>
                    $pendaftaran->user?->notify(new StatusPendaftaranNotification(tipe: 'pretest_ditutup'))
                );
        }
    }
}