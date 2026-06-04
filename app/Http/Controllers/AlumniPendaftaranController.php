<?php

namespace App\Http\Controllers;

use App\Models\AlumniPendaftaran;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Imports\AlumniPendaftaranImport;
use App\Exports\AlumniTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AlumniPendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $query = AlumniPendaftaran::with('jurusan');

        if ($request->filled('search')) {
            $query->where('nama', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        $alumnis = $query->latest()->paginate(10);

        $jurusans = Jurusan::where('is_active', true)->get();

        return view(
            'admin.alumni.index',
            compact('alumnis', 'jurusans')
        );
    }

    public function create()
    {
        $jurusans = Jurusan::where('is_active', true)->get();

        return view(
            'admin.alumni.create',
            compact('jurusans')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan' => 'required|string|max:20',
        ]);

        AlumniPendaftaran::create($validated);

        return redirect()
            ->route('admin.alumni.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alumni = AlumniPendaftaran::findOrFail($id);
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('admin.alumni.edit', compact('alumni', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $alumni = AlumniPendaftaran::findOrFail($id);

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan'   => 'required|digits:4',
        ]);

        $alumni->update($validated);

        return redirect()
            ->route('admin.alumni.index')
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AlumniPendaftaran::findOrFail($id)->delete();

        return redirect()
            ->route('admin.alumni.index')
            ->with('success', 'Data alumni berhasil dihapus.');
    }

    // Upload CSV
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:csv,txt|max:2048',
    //     ]);

    //     $handle = fopen($request->file('file')->getPathname(), 'r');

    //     $header  = null;
    //     $success = 0;
    //     $errors  = [];
    //     $row     = 1;

    //     while (($line = fgetcsv($handle, 1000, ',')) !== false) {
    //         // Skip baris kosong
    //         if (count(array_filter($line)) === 0) {
    //             $row++;
    //             continue;
    //         }

    //         if ($row === 1) {
    //             $header = array_map('trim', $line);

    //             $requiredHeader = ['nama', 'jurusan', 'angkatan'];

    //             if ($header !== $requiredHeader) {
    //                 fclose($handle);

    //                 return back()->withErrors([
    //                     'file' => 'Format header CSV harus: nama,jurusan,angkatan'
    //                 ]);
    //             }

    //             $row++;
    //             continue;
    //         }

    //         if (count($line) !== count($header)) {
    //             $errors[] = "Baris {$row}: jumlah kolom tidak sesuai.";
    //             $row++;
    //             continue;
    //         }

    //         $data = array_combine($header, $line);
    //         $data = array_map(fn ($value) => trim($value), $data);

    //         $validator = Validator::make($data, [
    //             'nama'     => 'required|string|max:255',
    //             'jurusan'  => 'required|string|max:255',
    //             'angkatan' => 'required|digits:4|integer',
    //         ], [
    //             'nama.required'     => 'nama wajib diisi',
    //             'jurusan.required'  => 'jurusan wajib diisi',
    //             'angkatan.required' => 'angkatan wajib diisi',
    //             'angkatan.digits'   => 'angkatan harus 4 digit, contoh: 2022',
    //             'angkatan.integer'  => 'angkatan harus berupa angka',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors[] = "Baris {$row}: " . implode(', ', $validator->errors()->all());
    //             $row++;
    //             continue;
    //         }

    //         $jurusanNama = preg_replace('/\s+/', ' ', trim($data['jurusan']));

    //         $jurusan = Jurusan::get()->first(function ($item) use ($jurusanNama) {
    //             $namaJurusanDb = preg_replace('/\s+/', ' ', trim($item->nama_jurusan));

    //             return strtolower($namaJurusanDb) === strtolower($jurusanNama);
    //         });

    //         if (!$jurusan) {
    //             $errors[] = "Baris {$row}: jurusan '{$data['jurusan']}' tidak ditemukan.";
    //             $row++;
    //             continue;
    //         }

    //         if (!$jurusan) {
    //             $errors[] = "Baris {$row}: jurusan '{$data['jurusan']}' tidak ditemukan.";
    //             $row++;
    //             continue;
    //         }

    //         AlumniPendaftaran::create([
    //             'nama'       => $data['nama'],
    //             'jurusan_id' => $jurusan->id,
    //             'angkatan'   => $data['angkatan'],
    //         ]);

    //         $success++;
    //         $row++;
    //     }

    //     fclose($handle);

    //     $message = "{$success} data berhasil diimport.";

    //     if (!empty($errors)) {
    //         return back()
    //             ->with('success', $message)
    //             ->withErrors([
    //                 'file' => implode(' | ', $errors)
    //             ]);
    //     }

    //     return redirect()
    //         ->route('admin.alumni.index')
    //         ->with('success', $message);
    // }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:2048',
        ]);

        $import = new AlumniPendaftaranImport;

        Excel::import($import, $request->file('file'));

        $message = "{$import->success} data berhasil diimport.";

        if (!empty($import->errors)) {
            return back()
                ->with('success', $message)
                ->withErrors([
                    'file' => implode(' | ', $import->errors)
                ]);
        }

        return redirect()
            ->route('admin.alumni.index')
            ->with('success', $message);
    }

    public function template()
    {
        return Excel::download(new AlumniTemplateExport, 'template-alumni.xlsx');
    }

    // public function template()
    // {
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="template-alumni.csv"',
    //     ];

    //     $jurusans = Jurusan::orderBy('nama_jurusan')->pluck('nama_jurusan');

    //     return response()->stream(function () use ($jurusans) {
    //         $handle = fopen('php://output', 'w');

    //         fputcsv($handle, ['nama', 'jurusan', 'angkatan']);
    //         fputcsv($handle, ['Budi Santoso', $jurusans->first() ?? 'Nama Jurusan', '2022']);

    //         fputcsv($handle, []);
    //         fputcsv($handle, ['Daftar jurusan tersedia']);

    //         foreach ($jurusans as $jurusan) {
    //             fputcsv($handle, ['', $jurusan, '']);
    //         }

    //         fclose($handle);
    //     }, 200, $headers);
    // }
}
