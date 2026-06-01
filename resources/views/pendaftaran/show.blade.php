<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">Detail Pendaftaran</h1>
                        <p class="text-blue-100 text-sm mt-1">Kode Daftar: {{ $pendaftaran->kode_pendaftaran }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        @php
                            $statusLabels = [
                                'menunggu_verifikasi'        => ['text' => 'Menunggu Verifikasi',        'class' => 'bg-yellow-100 text-yellow-700'],
                                'seleksi_pretest'            => ['text' => 'Seleksi Pretest',            'class' => 'bg-blue-100 text-blue-700'],
                                'wawancara'                  => ['text' => 'Wawancara',                  'class' => 'bg-purple-100 text-purple-700'],
                                'verifikasi_kelulusan_siswa' => ['text' => 'Verifikasi Kelulusan Siswa', 'class' => 'bg-teal-100 text-teal-700'],
                                'diterima'                   => ['text' => 'Diterima',                   'class' => 'bg-green-100 text-green-700'],
                                'cadangan'                   => ['text' => 'Cadangan',                   'class' => 'bg-amber-100 text-amber-700'],
                                'ditolak'                    => ['text' => 'Ditolak',                    'class' => 'bg-red-100 text-red-700'],
                            ];

                            $status = $statusLabels[$pendaftaran->status] ?? [
                                'text' => 'Menunggu Verifikasi',
                                'class' => 'bg-yellow-100 text-yellow-700'
                            ];
                        @endphp
                        

                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $status['class'] }}">
                            {{ $status['text'] }}
                        </span>

                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.pendaftaran.index') : route('dashboard') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/20 hover:bg-white/30 text-white font-semibold transition">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-10">

                {{-- VERIFIKASI --}}
                @php
                    $alurBerikutnya = [
                        'seleksi_pretest' => ['status' => 'wawancara',                  'label' => 'Lanjut ke Wawancara'],
                        'wawancara'       => ['status' => 'verifikasi_kelulusan_siswa', 'label' => 'Lanjut ke Verifikasi Kelulusan'],
                    ];

                    $isAwal      = $pendaftaran->status === 'menunggu_verifikasi';
                    $isTengah    = isset($alurBerikutnya[$pendaftaran->status]);
                    $isKelulusan = $pendaftaran->status === 'verifikasi_kelulusan_siswa';
                    $isCadangan  = $pendaftaran->status === 'cadangan'; // ← tambah
                    $isFinal     = in_array($pendaftaran->status, ['diterima', 'ditolak']); // ← hapus cadangan dari sini
                    $next        = $alurBerikutnya[$pendaftaran->status] ?? null;
                    $isAdmin     = in_array(Auth::user()->role?->nama, ['Admin', 'Superadmin']);

                    $tampilkanPanel = !$isFinal && $isAdmin && (
                        $isAwal ||
                        $isTengah ||
                        ($isKelulusan && $verifikasiKelulusanAktif) ||
                        ($isCadangan && $verifikasiKelulusanAktif) // ← tambah
                    );
                @endphp

                @if($tampilkanPanel)
                    <div class="p-5 rounded-2xl border flex flex-col md:flex-row md:items-center md:justify-between gap-4
                        {{ $isKelulusan ? 'bg-teal-50 border-teal-200' : ($isCadangan ? 'bg-amber-50 border-amber-200' : 'bg-yellow-50 border-yellow-200') }}">

                        <div>
                            @if($isAwal)
                                <h3 class="font-bold text-gray-900">Verifikasi Pendaftaran</h3>
                                <p class="text-sm text-gray-500">Setujui atau tolak pendaftaran setelah memeriksa data.</p>
                            @elseif($isTengah)
                                <h3 class="font-bold text-gray-900">{{ $next['label'] }}</h3>
                                <p class="text-sm text-gray-500">Lanjutkan proses ke tahap berikutnya.</p>
                            @elseif($isKelulusan)
                                <h3 class="font-bold text-gray-900">Verifikasi Kelulusan</h3>
                                <p class="text-sm text-gray-500">Tentukan keputusan akhir penerimaan peserta ini.</p>
                            @elseif($isCadangan)
                                <h3 class="font-bold text-gray-900">Peserta Cadangan</h3>
                                <p class="text-sm text-gray-500">Tentukan apakah peserta cadangan ini akhirnya diterima atau tidak.</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-3">

                            @if($isAwal)
                                <form id="lanjutForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="seleksi_pretest">
                                    <button type="button" onclick="confirmLanjut('Verifikasi pendaftaran ini?', 'lanjutForm')"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="badge-check" class="w-4 h-4"></i> Verifikasi
                                    </button>
                                </form>
                                <form id="tolakForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="alasan_ditolak" id="alasan_ditolak">
                                    <button type="button" onclick="confirmTolak()"
                                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i> Tolak
                                    </button>
                                </form>

                            @elseif($isTengah)
                                <form id="lanjutForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $next['status'] }}">
                                    <button type="button" onclick="confirmLanjut('{{ $next['label'] }}', 'lanjutForm')"
                                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="arrow-right-circle" class="w-4 h-4"></i> {{ $next['label'] }}
                                    </button>
                                </form>

                            @elseif($isKelulusan)
                                <form id="diterimaForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="button" onclick="confirmLanjut('Terima peserta ini', 'diterimaForm')"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i> Diterima
                                    </button>
                                </form>
                                <form id="cadanganForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cadangan">
                                    <button type="button" onclick="confirmLanjut('Jadikan peserta ini cadangan', 'cadanganForm')"
                                            class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="bookmark" class="w-4 h-4"></i> Cadangan
                                    </button>
                                </form>
                                <form id="tolakForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="alasan_ditolak" id="alasan_ditolak">
                                    <button type="button" onclick="confirmTolak()"
                                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i> Tidak Lulus
                                    </button>
                                </form>
                            @elseif($isCadangan)
                                <form id="diterimaForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="button" onclick="confirmLanjut('Terima peserta cadangan ini?', 'diterimaForm')"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i> Diterima
                                    </button>
                                </form>

                                <form id="tolakForm" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="alasan_ditolak" id="alasan_ditolak">
                                    <button type="button" onclick="confirmTolak()"
                                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i> Tidak Diterima
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>

                {{-- Panel kelulusan belum waktunya --}}
                @elseif($isKelulusan && $isAdmin && !$verifikasiKelulusanAktif)
                    <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Verifikasi Kelulusan Belum Dibuka</h3>
                            <p class="text-sm text-gray-500">
                                Jadwal verifikasi kelulusan belum tiba.
                                @if($pendaftaran->gelombang?->pengumuman_mulai)
                                    Dibuka pada:
                                    <span class="font-semibold text-blue-700">
                                        {{ \Carbon\Carbon::parse($pendaftaran->gelombang->pengumuman_mulai)->translatedFormat('d F Y H:i') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Alasan ditolak --}}
                @if($pendaftaran->status === 'ditolak' && $pendaftaran->alasan_ditolak)
                    <div class="p-5 rounded-2xl bg-red-50 border border-red-200 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-red-800">Alasan Penolakan</h3>
                            <p class="text-sm text-red-700 mt-1">{{ $pendaftaran->alasan_ditolak }}</p>
                        </div>
                    </div>
                @endif

                {{-- KAMPUS DAN JURUSAN --}}
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Kampus dan Jurusan</h2>
                            <p class="text-sm text-gray-500">Informasi kampus dan program keahlian yang dipilih.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-detail-item label="Kampus" :value="$pendaftaran->cabang?->nama_cabang ?? '-'" />
                        <x-detail-item label="Jurusan" :value="$pendaftaran->jurusan?->nama_jurusan ?? $pendaftaran->jurusan?->nama ?? '-'" />
                        
                    </div>
                </div>

                {{-- DATA DIRI --}}
                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Data Diri</h2>
                            <p class="text-sm text-gray-500">Data diri sesuai dengan dokumen identitas resmi.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-detail-item label="NIK" :value="$pendaftaran->nik" />
                        <x-detail-item label="No KK" :value="$pendaftaran->nkk" />
                        <x-detail-item label="Nama Lengkap" :value="$pendaftaran->nama" />
                        <x-detail-item label="Tempat Lahir" :value="$pendaftaran->tempat_lahir" />
                        <x-detail-item label="Tanggal Lahir" :value="$pendaftaran->tgl_lahir" />
                        <x-detail-item label="Umur" :value="$pendaftaran->umur . ' tahun'" />
                        <x-detail-item label="Jenis Kelamin" :value="$pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'" />
                        <x-detail-item label="Anak Ke" :value="$pendaftaran->anak_ke" />
                        <x-detail-item label="No HP" :value="$pendaftaran->no_hp" />
                        <x-detail-item label="Pendidikan Terakhir" :value="$pendaftaran->pendidikan" />
                        <x-detail-item label="Nama Sekolah" :value="$pendaftaran->sekolah" />
                        <x-detail-item label="Cita-cita" :value="$pendaftaran->cita_cita" />
                        <x-detail-item label="Hobi" :value="is_array($pendaftaran->hobi) ? implode(', ', $pendaftaran->hobi) : $pendaftaran->hobi" />
                        <x-detail-item label="Penyakit" :value="$pendaftaran->penyakit ?? '-'" />
                    </div>
                    <div class="mt-5">
                        <x-detail-item label="Alamat Lengkap" :value="$pendaftaran->alamat" />
                    </div>
                    {{-- WILAYAH --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-detail-item label="Provinsi" :value="$pendaftaran->provinsi_nama" />
                        <x-detail-item label="Kabupaten/Kota" :value="$pendaftaran->kabupaten_nama" />
                        <x-detail-item label="Kecamatan" :value="$pendaftaran->kecamatan_nama" />
                        <x-detail-item label="Kelurahan" :value="$pendaftaran->kelurahan_nama" />
                    </div>
                </div>

                {{-- MEDIA SOSIAL --}}
                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Akun Media Sosial</h2>
                            <p class="text-sm text-gray-500">Informasi akun media sosial pendaftar.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-detail-item label="Facebook" :value="$pendaftaran->facebook" />
                        <x-detail-item label="Instagram" :value="$pendaftaran->instagram" />
                    </div>
                </div>

                {{-- DATA ORANG TUA --}}
                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Orang Tua atau Wali</h2>
                            <p class="text-sm text-gray-500">Informasi data orang tua atau wali yang dapat dihubungi.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-detail-item label="Nama Ayah / Wali" :value="$pendaftaran->nama_wali" />
                        <x-detail-item label="Pendidikan Wali" :value="$pendaftaran->pendidikan_wali" />
                        <x-detail-item label="Pekerjaan Wali" :value="$pendaftaran->pekerjaan_wali" />
                        <x-detail-item label="No HP Wali" :value="$pendaftaran->nohp_wali" />
                        <x-detail-item label="Nama Ibu" :value="$pendaftaran->nama_ibu" />
                        <x-detail-item label="Pendidikan Ibu" :value="$pendaftaran->pendidikan_ibu" />
                        <x-detail-item label="Pekerjaan Ibu" :value="$pendaftaran->pekerjaan_ibu" />
                        <x-detail-item label="No HP Ibu" :value="$pendaftaran->nohp_ibu" />
                        <x-detail-item label="Jumlah Keluarga" :value="$pendaftaran->jml_keluarga" />
                        <x-detail-item label="Pendapatan Keluarga" :value="$pendaftaran->pendapatan_keluarga" />
                        <x-detail-item label="Status Rumah" :value="$pendaftaran->status_rumah" />
                    </div>
                    <div class="mt-5">
                        <x-detail-item label="Alamat Orang Tua" :value="$pendaftaran->alamat_orangtua" />
                    </div>
                </div>

                {{-- LAIN-LAIN --}}
                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Lain-lain</h2>
                            <p class="text-sm text-gray-500">Motivasi dan informasi pengenalan Rumah Gemilang Indonesia.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-detail-item label="Alasan Memilih RGI" :value="$pendaftaran->alasan" />
                        <x-detail-item label="Mengetahui RGI Dari" :value="is_array($pendaftaran->pengenalan) ? implode(', ', $pendaftaran->pengenalan) : $pendaftaran->pengenalan" />
                        <x-detail-item label="Rekomendasi" :value="$pendaftaran->rekomendasi ?? '-'" />
                    </div>
                    <div class="mt-5">
                        <x-detail-item label="Motivasi" :value="$pendaftaran->motivasi" />
                    </div>
                </div>

                {{-- BERKAS --}}
                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Berkas Upload</h2>
                            <p class="text-sm text-gray-500">Dokumen pendukung yang telah diunggah.</p>
                        </div>
                    </div>

                    <div x-data="{ previewOpen: false, previewFile: '' }">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                            @php
                                $files = [
                                    'pas_foto'    => 'Pas Foto',
                                    'foto_kk'     => 'Kartu Keluarga',
                                    'foto_ktp'    => 'KTP',
                                    'foto_ijazah' => 'Ijazah Terakhir',
                                    'sktm'        => 'SKTM (Surat Keterangan Tidak Mampu) / DKM',
                                    'surat_sehat' => 'Surat Sehat',
                                    'foto_rumah'  => 'Foto Rumah ( Fotokan seluruh ruangan rumah dalam bentuk grid)',
                                ];
                            @endphp

                            @foreach($files as $field => $label)
                                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:bg-blue-50/30 transition-colors">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="file" class="w-5 h-5"></i>
                                        </div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $label }}</p>
                                    </div>

                                    @if($pendaftaran->$field)
                                        <button type="button"
                                                @click="previewOpen = true; previewFile = '{{ asset('storage/' . $pendaftaran->$field) }}'"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                            Lihat File
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">Tidak ada file</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Modal Preview (sama persis dengan edit) --}}
                        <template x-teleport="body">
                            <div x-show="previewOpen" x-transition.opacity x-cloak
                                 class="fixed inset-0 z-[99999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
                                 style="display: none;" @click.self="previewOpen = false">
                                <div x-show="previewOpen" class="relative bg-white w-full max-w-6xl h-[92vh] rounded-3xl shadow-2xl overflow-hidden">
                                    <div class="flex items-center justify-between px-6 py-4 border-b bg-white">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">Preview Dokumen</h3>
                                            <p class="text-sm text-gray-500">Klik area luar untuk menutup preview</p>
                                        </div>
                                        <button type="button" @click="previewOpen = false"
                                                class="w-10 h-10 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-md transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="w-full h-[calc(92vh-80px)] bg-gray-100 flex items-center justify-center overflow-auto">
                                        <template x-if="previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                            <div class="w-full h-full flex items-center justify-center p-4">
                                                <img :src="previewFile" class="max-w-full max-h-full object-contain rounded-xl shadow">
                                            </div>
                                        </template>
                                        <template x-if="!previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                            <iframe :src="previewFile" class="w-full h-full border-0 bg-white"></iframe>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>