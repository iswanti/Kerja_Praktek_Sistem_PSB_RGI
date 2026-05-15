<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pendaftaran;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PendaftaranController extends Controller
{

    public function index(Request $request)
    {
        $query = Pendaftaran::with(['cabang', 'jurusan']);

        // SEARCH: kode daftar, nama, jurusan, cabang, periode/status
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_pendaftaran', 'ilike', "%{$search}%")
                    ->orWhere('nama', 'ilike', "%{$search}%")
                    ->orWhere('status', 'ilike', "%{$search}%")
                    ->orWhereHas('jurusan', function ($jurusan) use ($search) {
                        $jurusan->where('nama', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('cabang', function ($cabang) use ($search) {
                        $cabang->where('nama_cabang', 'ilike', "%{$search}%");
                    });
            });
        }

        // FILTER CABANG
        // if ($request->filled('cabang_id')) {
        //     $query->where('cabang_id', $request->cabang_id);
        // }

        // // FILTER JURUSAN
        // if ($request->filled('jurusan_id')) {
        //     $query->where('jurusan_id', $request->jurusan_id);
        // }

        // Filter Jurusan: Hanya jalan jika jurusan_id ada isinya
        $query->when($request->filled('jurusan_id'), function ($q) use ($request) {
            return $q->where('jurusan_id', $request->jurusan_id);
        });

        // Filter Cabang: Hanya jalan jika cabang_id ada isinya
        $query->when($request->filled('cabang_id'), function ($q) use ($request) {
            return $q->where('cabang_id', $request->cabang_id);
        });

        // FILTER STATUS / PERIODE
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // // FILTER PERIODE BULAN, format: 2026-05
        // if ($request->filled('periode')) {
        //     [$tahun, $bulan] = explode('-', $request->periode);

        //     $query->whereYear('created_at', $tahun)
        //           ->whereMonth('created_at', $bulan);
        // }

        // FILTER PER TANGGAL (Format: 2026-05-09)
        if ($request->filled('periode')) {
            // whereDate akan memisahkan jam:menit:detik dari kolom created_at 
            // sehingga hanya membandingkan tanggalnya saja
            $query->whereDate('created_at', $request->periode);
        }

        $pendaftarans = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('pendaftaran.index', compact(
            'pendaftarans',
            'cabangs',
            'jurusans'
        ));
    }

    public function create (){
        $cabangs = Cabang::with(['jurusans' => function ($q) {
            $q->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

        return view('pendaftaran.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI
        $validated = $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'jurusan_id' => 'required|exists:jurusans,id',
            'nik' => 'required|numeric|digits:16',
            'nkk' => 'required|numeric|digits:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'umur' => 'required|numeric',
            'jenis_kelamin' => 'required',
            'anak_ke' => 'required|numeric',
            'alamat' => 'required|string',
            'provinsi_nama' => 'required',
            'kabupaten_nama' => 'required',
            'kecamatan_nama' => 'required',
            'kelurahan_nama' => 'required',
            'id_alamat' => 'required',

            'pendidikan' => 'required',
            'sekolah' => 'required',
            'cita_cita' => 'required',
            'hobi' => 'nullable|array',
            'hobi_lainnya' => 'nullable|string|max:100',
            'no_hp' => 'required',
            'penyakit' => 'nullable|string',

            'facebook' => [
                'required',
                'regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'
            ],
            'instagram' => [
                'required',
                'regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/[A-Za-z0-9_.]+$|^@[A-Za-z0-9_.]+$/'
            ],

            'nama_wali' => 'required',
            'pendidikan_wali' => 'required',
            'pekerjaan_wali' => 'required',
            'pekerjaan_wali_lainnya' => 'nullable|required_if:pekerjaan_wali,Lainnya|string|max:100',
            'nohp_wali' => 'required',

            'nama_ibu' => 'required',
            'pendidikan_ibu' => 'required',
            'pekerjaan_ibu' => 'required',
            'pekerjaan_ibu_lainnya' => 'nullable|required_if:pekerjaan_ibu,Lainnya|string|max:100',

            'nohp_ibu' => 'required',

            'alamat_orangtua' => 'required',
            'jml_keluarga' => 'required|numeric',
            'pendapatan_keluarga' => 'required',

            'status_rumah' => 'required|in:milik_sendiri,sewa,milik_kerabat,lainnya',
            'status_lainnya' => 'nullable|required_if:status_rumah,lainnya|string|max:50',

            'motivasi' => 'required',
            'alasan' => 'required',
            'alasan_lainnya' => 'nullable|required_if:alasan,lainnya|string|max:255',
            'pengenalan' => 'nullable|array',
            'pengenalan_lainnya' => 'nullable|string|max:100',
            'rekomendasi' => 'nullable|string|max:100',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'foto_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_ijazah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sktm' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_sehat' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_rumah' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'surat_vaksin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $hobi = $request->hobi ?? [];
        if (in_array('Lainnya', $hobi)) {
            $hobi = array_diff($hobi, ['Lainnya']); // hapus label
            $hobi[] = $request->hobi_lainnya;       // ganti isi user
        }
        $hobi = array_filter($hobi);

        $pengenalan = $request->pengenalan ?? [];
        if (in_array('Lainnya', $pengenalan)) {
            $pengenalan = array_diff($pengenalan, ['Lainnya']);
            $pengenalan[] = $request->pengenalan_lainnya;
        }
        $pengenalan = array_filter($pengenalan);

        $pekerjaan_ibu = $validated['pekerjaan_ibu'] === 'Lainnya'
            ? $validated['pekerjaan_ibu_lainnya']
            : $validated['pekerjaan_ibu'];

        $pekerjaan_wali = $validated['pekerjaan_wali'] === 'Lainnya'
            ? $validated['pekerjaan_wali_lainnya']
            : $validated['pekerjaan_wali'];

        // 2. GABUNGKAN JADI 1 FIELD
        $status_rumah = $validated['status_rumah'] === 'lainnya'
            ? $validated['status_lainnya']
            : $validated['status_rumah'];
        
        $alasan = $validated['alasan'] === 'lainnya'
            ? $validated['alasan_lainnya']
            : $validated['alasan'];
        
        $fileFields = [
            'pas_foto','foto_kk','foto_ktp','foto_ijazah','sktm','surat_sehat','foto_rumah','surat_vaksin',
        ];
        $folder = 'berkas_pendaftaran/' . strtolower(str_replace(' ', '_', $validated['nama'])) . '_' . uniqid();

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // 🔥 nama file dibuat tetap (biar rapi)
                $filename = $field . '.' . $file->getClientOriginalExtension();

                $validated[$field] = $file->storeAs($folder, $filename, 'public');
            }
        }
        

        // 4. SIMPAN
        $pendaftaran = DB::transaction(function () use ($validated, $status_rumah, $hobi, $pekerjaan_ibu, $pekerjaan_wali, $pengenalan, $alasan){
            return Pendaftaran::create([
                'cabang_id' => $validated['cabang_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'nik' => $validated['nik'],
                'nkk' => $validated['nkk'],
                'nama' => $validated['nama'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tgl_lahir' => $validated['tgl_lahir'],
                'umur' => $validated['umur'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'anak_ke' => $validated['anak_ke'],
                'alamat' => $validated['alamat'],
                'provinsi_nama' => $validated['provinsi_nama'],
                'kabupaten_nama' => $validated['kabupaten_nama'],
                'kecamatan_nama' => $validated['kecamatan_nama'],
                'kelurahan_nama' => $validated['kelurahan_nama'],
                'id_alamat' => $validated['id_alamat'],

                'pendidikan' => $validated['pendidikan'],
                'sekolah' => $validated['sekolah'],
                'cita_cita' => $validated['cita_cita'],
                'hobi' => $hobi,
                'no_hp' => $validated['no_hp'],
                'penyakit' => $validated['penyakit'] ?? null,

                'facebook' => $validated['facebook'],
                'instagram' => $validated['instagram'],

                'nama_wali' => $validated['nama_wali'],
                'pendidikan_wali' => $validated['pendidikan_wali'],
                'pekerjaan_wali' => $pekerjaan_wali,
                'nohp_wali' => $validated['nohp_wali'],

                'nama_ibu' => $validated['nama_ibu'],
                'pendidikan_ibu' => $validated['pendidikan_ibu'],
                'pekerjaan_ibu' => $pekerjaan_ibu,
                'nohp_ibu' => $validated['nohp_ibu'],

                'alamat_orangtua' => $validated['alamat_orangtua'],
                'jml_keluarga' => $validated['jml_keluarga'],
                'pendapatan_keluarga' => $validated['pendapatan_keluarga'],

                // 🔥 hanya 1 field
                'status_rumah' => $status_rumah,
                'motivasi' => $validated['motivasi'],
                'alasan' => $alasan,
                'pengenalan' => $pengenalan,
                'rekomendasi' => $validated['rekomendasi'],

                'pas_foto' => $validated['pas_foto'] ?? null,
                'foto_kk' => $validated['foto_kk'] ?? null,
                'foto_ktp' => $validated['foto_ktp'] ?? null,
                'foto_ijazah' => $validated['foto_ijazah'] ?? null,
                'sktm' => $validated['sktm'] ?? null,
                'surat_sehat' => $validated['surat_sehat'] ?? null,
                'foto_rumah' => $validated['foto_rumah'] ?? null,
                'surat_vaksin' => $validated['surat_vaksin'] ?? null,
                'status' => 'menunggu_verifikasi',
            ]);
        });
        // dd($request->all());

        if (!$pendaftaran) {
            return back()->with('error', 'Gagal menyimpan data');
        }

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil!')
            ->with('kode', $pendaftaran->kode_pendaftaran);
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $cabangs = Cabang::with(['jurusans' => function ($q) {
            $q->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

        $jurusans = Jurusan::where('is_active', true)->get();

        return view('pendaftaran.edit', compact(
            'pendaftaran',
            'cabangs',
            'jurusans'
        ));
    }

    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $validated = $request->validate([
            'cabang_id' => 'required',
            'jurusan_id' => 'required',

            'nik' => 'required',
            'nkk' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'umur' => 'required',
            'jenis_kelamin' => 'required',
            'anak_ke' => 'required',
            'alamat' => 'required',

            'provinsi_nama' => 'required',
            'kabupaten_nama' => 'required',
            'kecamatan_nama' => 'required',
            'kelurahan_nama' => 'required',
            'id_alamat' => 'required',

            'pendidikan' => 'required',
            'sekolah' => 'required',
            'cita_cita' => 'required',
            'no_hp' => 'required',

            'hobi' => 'nullable|array',
            'hobi_lainnya' => 'nullable|string',

            'penyakit' => 'nullable',
            'facebook' => 'nullable',
            'instagram' => 'nullable',

            'nama_wali' => 'nullable',
            'pendidikan_wali' => 'nullable',
            'pekerjaan_wali' => 'nullable',
            'pekerjaan_wali_lainnya' => 'nullable',
            'nohp_wali' => 'nullable',

            'nama_ibu' => 'nullable',
            'pendidikan_ibu' => 'nullable',
            'pekerjaan_ibu' => 'nullable',
            'pekerjaan_ibu_lainnya' => 'nullable',
            'nohp_ibu' => 'nullable',

            'alamat_orangtua' => 'nullable',
            'jml_keluarga' => 'nullable',
            'pendapatan_keluarga' => 'nullable',

            'status_rumah' => 'required',
            'status_lainnya' => 'nullable',

            'motivasi' => 'nullable',

            'pengenalan' => 'nullable|array',
            'pengenalan_lainnya' => 'nullable',

            'alasan' => 'nullable',
            'alasan_lainnya' => 'nullable',
            'rekomendasi' => 'nullable',

            'pas_foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sktm' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_sehat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_rumah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_vaksin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $validated;

        if ($request->filled('hobi_lainnya')) {
            $hobi = $request->hobi ?? [];
            $hobi = array_filter($hobi, fn($item) => $item !== 'Lainnya');
            $hobi[] = $request->hobi_lainnya;
            $data['hobi'] = implode(',', $hobi);
        } else {
            $data['hobi'] = implode(',', $request->hobi ?? []);
        }

        if ($request->filled('pengenalan_lainnya')) {
            $pengenalan = $request->pengenalan ?? [];
            $pengenalan = array_filter($pengenalan, fn($item) => $item !== 'Lainnya');
            $pengenalan[] = $request->pengenalan_lainnya;
            $data['pengenalan'] = implode(',', $pengenalan);
        } else {
            $data['pengenalan'] = implode(',', $request->pengenalan ?? []);
        }

        if ($request->pekerjaan_wali === 'Lainnya') {
            $data['pekerjaan_wali'] = $request->pekerjaan_wali_lainnya;
        }

        if ($request->pekerjaan_ibu === 'Lainnya') {
            $data['pekerjaan_ibu'] = $request->pekerjaan_ibu_lainnya;
        }

        if ($request->status_rumah === 'lainnya') {
            $data['status_rumah'] = $request->status_lainnya;
        }

        if ($request->alasan === 'lainnya') {
            $data['alasan'] = $request->alasan_lainnya;
        }

        unset(
            $data['hobi_lainnya'],
            $data['pengenalan_lainnya'],
            $data['pekerjaan_wali_lainnya'],
            $data['pekerjaan_ibu_lainnya'],
            $data['status_lainnya'],
            $data['alasan_lainnya']
        );

        $fileFields = [
            'pas_foto',
            'foto_kk',
            'foto_ktp',
            'foto_ijazah',
            'sktm',
            'surat_sehat',
            'foto_rumah',
            'surat_vaksin',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('berkas_pendaftaran', 'public');
            } else {
                unset($data[$field]);
            }
        }

        $pendaftaran->update($data);

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->delete();

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with(['cabang', 'jurusan'])->findOrFail($id);

        return view('pendaftaran.show', compact('pendaftaran'));
    }

    // public function verifikasi(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:diterima,ditolak',
    //     ]);

    //     $pendaftaran = Pendaftaran::findOrFail($id);

    //     $pendaftaran->update([
    //         'status' => $request->status,
    //     ]);

    //     return redirect()
    //         ->route('pendaftaran.show', $pendaftaran->id)
    //         ->with('success', 'Status pendaftaran berhasil diperbarui.');
    // }
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,seleksi_pretest,wawancara,verifikasi_kelulusan_siswa,ditolak',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => $request->status,
        ]);

        // Pesan sukses sesuai status yang dipilih
        $messages = [
            'menunggu_verifikasi'       => 'Pendaftaran berhasil dikembalikan ke tahap Menunggu Verifikasi.',
            'terverifikasi'             => 'Pendaftaran berhasil diverifikasi.',
            'seleksi_pretest'           => 'Pendaftaran berhasil dipindahkan ke tahap Seleksi Pretest.',
            'wawancara'                 => 'Pendaftaran berhasil dipindahkan ke tahap Wawancara.',
            'verifikasi_kelulusan_siswa'=> 'Pendaftaran berhasil dipindahkan ke tahap Verifikasi Kelulusan Siswa.',
            'ditolak'                   => 'Pendaftaran ditolak.',
        ];

        return redirect()
            ->route('pendaftaran.show', $pendaftaran->id)
            ->with('success', $messages[$request->status] ?? 'Status pendaftaran berhasil diperbarui.');
    }

    
}
