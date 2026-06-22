<?php

namespace App\Http\Controllers;

use App\Models\Gelombang;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\StatusPendaftaranNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $isActive = $request->boolean('is_active');

        $gelombang = null;

        DB::transaction(function () use ($validated, $isActive, &$gelombang) {
            if ($isActive) {
                Gelombang::where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $gelombang = Gelombang::create([
                ...$validated,
                'is_active' => $isActive,
            ]);
        });

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
            'nama_gelombang'      => 'required',
            'tahun_periode'       => 'required',

            'pendaftaran_mulai'   => 'nullable|date',
            'pendaftaran_selesai' => 'nullable|date|after_or_equal:pendaftaran_mulai',

            'pretest_mulai'       => 'nullable|date',
            'pretest_selesai'     => 'nullable|date|after_or_equal:pretest_mulai',

            'durasi_pretest'      => 'nullable|integer',

            'wawancara_mulai'     => 'nullable|date',
            'wawancara_selesai'   => 'nullable|date|after_or_equal:wawancara_mulai',

            'pengumuman_mulai'    => 'nullable|date',
        ], [
            'pendaftaran_selesai.after_or_equal' => 'Tanggal selesai pendaftaran tidak boleh sebelum tanggal mulai.',
            'pretest_selesai.after_or_equal'     => 'Tanggal selesai pretest tidak boleh sebelum tanggal mulai.',
            'wawancara_selesai.after_or_equal'   => 'Tanggal selesai wawancara tidak boleh sebelum tanggal mulai.',
        ]);

        $isActive = $request->boolean('is_active');

        $oldGelombang = clone $gelombang;

        DB::transaction(function () use ($gelombang, $validated, $isActive) {

            // enforce hanya 1 aktif
            if ($isActive) {
                Gelombang::where('id', '!=', $gelombang->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $gelombang->update([
                ...$validated,
                'is_active' => $isActive,
            ]);
        });

        // penting: refresh data setelah update (ini yang sering dilupakan)
        $gelombang->refresh();

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