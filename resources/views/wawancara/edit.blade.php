<x-app-layout>
    <div class="w-full mx-auto sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">
                            Input Wawancara
                        </h1>
                        <p class="text-purple-100 text-sm mt-1">
                            Kode Daftar: {{ $pendaftaran->kode_pendaftaran }}
                        </p>
                    </div>

                    <a href="{{ route('admin.pendaftaran.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-semibold text-sm transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <div class="p-8 space-y-8">

                {{-- ZOOM CARD --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">

                        {{-- ICON + TITLE --}}
                        <div class="flex items-center gap-4 min-w-[320px]">
                            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                                <i data-lucide="video" class="w-8 h-8"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    Link Zoom Wawancara
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    Gunakan link berikut untuk sesi wawancara online.
                                </p>
                            </div>
                        </div>

                        {{-- INFORMASI ZOOM --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 min-w-[320px]">

                            {{-- LINK MEETING --}}
                            <div class="md:col-span-6 min-w-10">
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">
                                    Link Meeting
                                </p>

                                <a href="{{ $zoomLink ?? '#' }}"
                                target="_blank"
                                class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline break-all">
                                    {{ $zoomLink ?? 'https://zoom.us/j/1234567890' }}
                                </a>
                            </div>

                            {{-- MEETING ID --}}
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">
                                    Meeting ID
                                </p>

                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $meetingId ?? '123 456 7890' }}
                                </p>
                            </div>

                            {{-- PASSCODE --}}
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">
                                    Passcode
                                </p>

                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $passcode ?? 'RGIWAW2026' }}
                                </p>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="flex-shrink-0">
                            <a href="{{ $zoomLink ?? '#' }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                                    bg-gradient-to-r from-indigo-600 to-purple-600
                                    hover:from-indigo-700 hover:to-purple-700
                                    text-white font-semibold shadow-sm transition">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Buka Zoom
                            </a>
                        </div>
                    </div>
                </div>

                {{-- DATA PENDAFTAR --}}
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-purple-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Pendaftar</h2>
                            <p class="text-sm text-gray-500">Data berikut diambil otomatis dari form pendaftaran.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                        <x-detail-item label="Nama Santri" :value="$pendaftaran->nama" />
                        <x-detail-item label="Kode Pendaftaran" :value="$pendaftaran->kode_pendaftaran" />
                        <x-detail-item label="Jurusan" :value="$pendaftaran->jurusan?->nama_jurusan ?? '-'" />
                        <x-detail-item label="Cabang" :value="$pendaftaran->cabang?->nama_cabang ?? '-'" />
                        <x-detail-item label="Usia" :value="$pendaftaran->umur . ' tahun'" />
                        <x-detail-item label="Pendidikan Terakhir" :value="$pendaftaran->pendidikan" />
                    </div>

                    <div class="mt-5">
                        <x-detail-item label="Alamat" :value="$pendaftaran->alamat" />
                    </div>
                </div>

                {{-- PROGRESS 4 UNSUR --}}
                <div class="border-t border-gray-100 pt-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-5">
                        Progress Wawancara
                    </h2>

                    @php
                        $progress = [
                            'operator' => [
                                'title' => 'Operator',
                                'icon' => 'user-check',
                                'status' => $wawancara?->operator_data ? 'Selesai' : 'Belum Dinilai',
                                'active' => auth()->user()->unsur_wawancara === 'operator',
                            ],
                            'manajemen' => [
                                'title' => 'Manajemen',
                                'icon' => 'clipboard-list',
                                'status' => $wawancara?->manajemen_data ? 'Selesai' : 'Belum Dinilai',
                                'active' => auth()->user()->unsur_wawancara === 'manajemen',
                            ],
                            'scc_asrama' => [
                                'title' => 'SCC/Asrama',
                                'icon' => 'home',
                                'status' => $wawancara?->scc_data ? 'Selesai' : 'Belum Dinilai',
                                'active' => auth()->user()->unsur_wawancara === 'scc_asrama',
                            ],
                            'instruktur' => [
                                'title' => 'Instruktur',
                                'icon' => 'graduation-cap',
                                'status' => $wawancara?->instruktur_data ? 'Selesai' : 'Belum Dinilai',
                                'active' => auth()->user()->unsur_wawancara === 'instruktur',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($progress as $item)
                            <div class="rounded-2xl border p-5 {{ $item['active'] ? 'border-purple-500 bg-purple-50' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center justify-center mb-3">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-7 h-7 {{ $item['active'] ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                </div>

                                <p class="text-center font-semibold text-gray-800">
                                    {{ $item['title'] }}
                                </p>

                                <p class="text-center text-sm mt-1 {{ $item['status'] == 'Selesai' ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $item['status'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- FORM WAWANCARA --}}
                <form action="{{ route('admin.wawancara.store', $pendaftaran->id) }}" method="POST">
                    @csrf

                    @php
                        $unsur = auth()->user()->unsur_wawancara;
                    @endphp

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                        {{-- DATA OTOMATIS --}}
                        <div class="rounded-2xl border border-gray-200 p-5 bg-gray-50">
                            <h3 class="font-bold text-gray-900 mb-4">
                                Data dari Pendaftaran
                            </h3>

                            <div class="space-y-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Nama Santri</p>
                                    <p class="font-semibold text-gray-900">{{ $pendaftaran->nama }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Pekerjaan Ayah</p>
                                    <p class="font-semibold text-gray-900">{{ $pendaftaran->pekerjaan_wali ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Pekerjaan Ibu</p>
                                    <p class="font-semibold text-gray-900">{{ $pendaftaran->pekerjaan_ibu ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Motivasi Masuk RGI</p>
                                    <p class="font-semibold text-gray-900">{{ $pendaftaran->motivasi ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Riwayat Penyakit</p>
                                    <p class="font-semibold text-gray-900">{{ $pendaftaran->penyakit ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="mt-6 rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm text-blue-700">
                                Data ini otomatis dari pendaftaran dan tidak dapat diubah di halaman wawancara.
                            </div>
                        </div>

                        {{-- FORM SESUAI UNSUR --}}
                        <div class="xl:col-span-2 rounded-2xl border border-gray-200 p-6">
                            @if($unsur === 'operator')
                                <h3 class="text-xl font-bold text-purple-700 mb-5">Bagian Operator</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nama Operator</label>
                                        <input type="text" name="nama_operator" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Rekomendasi</label>
                                        <select name="rekomendasi_operator" class="w-full rounded-xl border-gray-300 text-black" required>
                                            <option value="">Pilih Rekomendasi</option>
                                            <option value="layak">Layak</option>
                                            <option value="dipertimbangkan">Dipertimbangkan</option>
                                            <option value="tidak_layak">Tidak Layak</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if($unsur === 'manajemen')
                                <h3 class="text-xl font-bold text-purple-700 mb-5">Bagian Manajemen</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nama Pewawancara</label>
                                        <input type="text" name="nama_pewawancara" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Pendapatan Orang Tua / Bulan</label>
                                        <select name="pendapatan_orangtua" class="w-full rounded-xl border-gray-300 text-black" required>
                                            <option value="">Pilih Pendapatan</option>
                                            <option value="< 1 juta">&lt; Rp 1.000.000</option>
                                            <option value="1-3 juta">Rp 1.000.000 - Rp 3.000.000</option>
                                            <option value="3-5 juta">Rp 3.000.000 - Rp 5.000.000</option>
                                            <option value="> 5 juta">&gt; Rp 5.000.000</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Pelanggaran Berat</label>
                                        <select name="pelanggaran_berat" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Tidak Ada">Tidak Ada</option>
                                            <option value="Ada">Ada</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Kondisi Rumah</label>
                                        <select name="kondisi_rumah" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="">Pilih Kondisi</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang Layak">Kurang Layak</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Tingkat Keduafaan</label>
                                        <select name="tingkat_keduafaan" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="">Pilih Tingkat</option>
                                            <option value="Rendah">Rendah</option>
                                            <option value="Sedang">Sedang</option>
                                            <option value="Tinggi">Tinggi</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nilai</label>
                                        <input type="number" name="nilai" min="0" max="100" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold mb-2">Catatan</label>
                                        <textarea name="catatan" rows="4" class="w-full rounded-xl border-gray-300"></textarea>
                                    </div>
                                </div>
                            @endif

                            @if($unsur === 'scc_asrama')
                                <h3 class="text-xl font-bold text-purple-700 mb-5">Bagian SCC / Asrama</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nama Pewawancara</label>
                                        <input type="text" name="nama_pewawancara" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Merokok</label>
                                        <select name="merokok" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Tidak">Tidak</option>
                                            <option value="Ya">Ya</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Mengaji</label>
                                        <select name="mengaji" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Mahir">Mahir</option>
                                            <option value="Lancar">Lancar</option>
                                            <option value="Terbata-bata">Terbata-bata</option>
                                            <option value="Belum">Belum</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Sholat</label>
                                        <select name="sholat" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Lengkap">Lengkap</option>
                                            <option value="Sering">Sering</option>
                                            <option value="Jarang-Jarang">Jarang-Jarang</option>
                                            <option value="Tidak Pernah">Tidak Pernah</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nilai</label>
                                        <input type="number" name="nilai" min="0" max="100" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold mb-2">Catatan</label>
                                        <textarea name="catatan" rows="4" class="w-full rounded-xl border-gray-300"></textarea>
                                    </div>
                                </div>
                            @endif

                            @if($unsur === 'instruktur')
                                <h3 class="text-xl font-bold text-purple-700 mb-5">Bagian Instruktur Kelas</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nama Instruktur</label>
                                        <input type="text" name="nama_instruktur" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Jurusan Keahlian</label>
                                        <input type="text" value="{{ $pendaftaran->jurusan?->nama_jurusan }}" readonly class="w-full rounded-xl border-gray-300 bg-gray-100">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold mb-2">Lulus dari RGI Mau Berbuat Apa</label>
                                        <textarea name="rencana_setelah_lulus" rows="3" class="w-full rounded-xl border-gray-300"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Level Pengetahuan Materi</label>
                                        <select name="level_pengetahuan" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang">Kurang</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Kemampuan Dasar</label>
                                        <select name="kemampuan_dasar" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang">Kurang</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Motivasi Belajar</label>
                                        <select name="motivasi_belajar" class="w-full rounded-xl border-gray-300 text-black">
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang">Kurang</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold mb-2">Nilai</label>
                                        <input type="number" name="nilai" min="0" max="100" class="w-full rounded-xl border-gray-300" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold mb-2">Catatan</label>
                                        <textarea name="catatan" rows="4" class="w-full rounded-xl border-gray-300"></textarea>
                                    </div>
                                </div>
                            @endif

                            <div class="flex justify-end gap-3 mt-8 border-t pt-6">
                                <button type="submit"
                                        name="action"
                                        value="draft"
                                        class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold">
                                    Simpan Draft
                                </button>

                                <button type="submit"
                                        name="action"
                                        value="submit"
                                        class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold">
                                    Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>