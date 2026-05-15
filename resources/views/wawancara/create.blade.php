<x-app-layout>
    <div class="w-full">
        {{-- FORM INPUT WAWANCARA --}}
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- FORM HEADER --}}
            <div class="bg-gradient-to-r  from-blue-600 to-blue-500 px-6 py-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center font-bold">
                            @php
                                $nama = trim($pendaftaran->nama ?? '');
                                $kata = preg_split('/\s+/', $nama, -1, PREG_SPLIT_NO_EMPTY);

                                $inisial = '';

                                if (count($kata) >= 2) {
                                    $inisial = strtoupper(
                                        substr($kata[0], 0, 1) .
                                        substr($kata[1], 0, 1)
                                    );
                                } elseif (count($kata) === 1) {
                                    $inisial = strtoupper(substr($kata[0], 0, 2));
                                } else {
                                    $inisial = 'NA';
                                }
                            @endphp

                            {{ $inisial }}
                        </div>

                        <div>
                            <h2 class="text-white font-bold text-lg">{{ $pendaftaran->nama ?? '-' }}</h2>
                            <p class="text-indigo-100 text-sm">
                                {{ $pendaftaran->kode_pendaftaran ?? '-' }} · {{ $pendaftaran->jurusan->nama_jurusan ?? '-' }} · {{ $pendaftaran->cabang->nama_cabang ?? '-' }} · Tahap Wawancara
                            </p>
                        </div>
                    </div>

                    <span class="px-4 py-2 rounded-full bg-white/20 text-white text-sm font-semibold">
                        Wawancara
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                {{-- STAGE PROGRESS --}}
                <div class="rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center">
                        @php
                            $stages = ['Operator', 'Manajemen', 'SCC/Asrama', 'Instruktur'];

                            // Status tiap tahap
                            $completed = [
                                !empty($wawancara->rekomendasi_operator),
                                !is_null($wawancara->nilai_manajemen),
                                !is_null($wawancara->nilai_scc),
                                !is_null($wawancara->nilai_instruktur),
                            ];

                            // Cari tahap aktif (yang pertama belum selesai)
                            $currentIndex = array_search(false, $completed, true);

                            // Jika semua selesai
                            if ($currentIndex === false) {
                                $currentIndex = count($stages);
                            }
                        @endphp

                        @foreach($stages as $index => $stage)
                            <div class="flex-1 flex flex-col items-center text-center relative">

                                {{-- Garis penghubung --}}
                                @if(!$loop->last)
                                    <div class="absolute top-5 left-1/2 w-full h-0.5
                                        {{ $index < $currentIndex ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                    </div>
                                @endif

                                {{-- Bulatan --}}
                                <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2
                                    {{
                                        $completed[$index]
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : (
                                                $index == $currentIndex
                                                    ? 'bg-white text-indigo-600 border-indigo-600 ring-4 ring-indigo-100'
                                                    : 'bg-gray-100 text-gray-400 border-gray-200'
                                            )
                                    }}">
                                    {{ $completed[$index] ? '✓' : $index + 1 }}
                                </div>

                                {{-- Label --}}
                                <p class="text-xs mt-2 font-semibold
                                    {{ ($completed[$index] || $index == $currentIndex)
                                        ? 'text-indigo-600'
                                        : 'text-gray-500' }}">
                                    {{ $stage }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- DATA OTOMATIS --}}
                <div class="rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900">Data dari Pendaftaran</h3>
                            <p class="text-sm text-gray-500">Data otomatis dan tidak dapat diubah di sini</p>
                        </div>
                    </div>

                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 bg-white">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500">Pekerjaan Ayah</p>
                            <p class="font-semibold text-gray-900">Wiraswasta</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500">Pekerjaan Ibu</p>
                            <p class="font-semibold text-gray-900">Ibu Rumah Tangga</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                            <p class="text-xs text-gray-500">Motivasi Masuk RGI</p>
                            <p class="font-semibold text-gray-900">
                                Ingin mendalami ilmu design dan membangun karir kreatif
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                            <p class="text-xs text-gray-500">Riwayat Penyakit</p>
                            <p class="font-semibold text-gray-900">Tidak ada</p>
                        </div>
                    </div>
                </div>

                {{-- FORM MANAJEMEN --}}
                <form action="{{ route('wawancara.store', $pendaftaran->id) }}" method="POST" class="rounded-2xl border border-gray-100 overflow-hidden">
                    @csrf

                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        @if(session('success'))
                            <script>
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: '{{ session("success") }}',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            </script>
                        @endif

                    <input type="hidden" name="unsur" value="{{ $unsur }}">

                    @if($unsur === 'operator')
                        <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i data-lucide="user-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Bagian Operator</h3>
                                <p class="text-sm text-gray-500">Verifikasi data dasar dan rekomendasi awal.</p>
                            </div>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Operator</label>
                                <input type="text" name="nama_operator"
                                    value="{{ old('nama_operator', $wawancara->nama_operator) }}"
                                    class="w-full rounded-xl border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Rekomendasi</label>
                                <select name="rekomendasi_operator" class="w-full rounded-xl border-gray-300 text-black">
                                    <option value="">Pilih Rekomendasi</option>
                                    @foreach(['direkomendasikan', 'dipertimbangkan', 'tidak_direkomendasikan'] as $item)
                                        <option value="{{ $item }}" {{ old('rekomendasi_operator', $wawancara->rekomendasi_operator) == $item ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $item)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($unsur === 'manajemen')
                        <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Bagian Manajemen</h3>
                                <p class="text-sm text-gray-500">Penilaian oleh tim manajemen RGI.</p>
                            </div>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pewawancara</label>
                                <input type="text" name="nama_pewawancara_manajemen"
                                    value="{{ old('nama_pewawancara_manajemen', $wawancara->nama_pewawancara_manajemen) }}"
                                    class="w-full rounded-xl border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pendapatan Orang Tua / Bulan</label>
                                <select name="pendapatan_orangtua" class="w-full rounded-xl border-gray-300 text-black">
                                    <option value="">Pilih Pendapatan</option>
                                    @foreach(['< Rp 1.000.000', 'Rp 1.000.000 - Rp 2.000.000', 'Rp 2.000.000 - Rp 4.000.000', 'Rp 4.000.000 - Rp 6.000.000', '> Rp 6.000.000'] as $item)
                                        <option value="{{ $item }}" {{ old('pendapatan_orangtua', $wawancara->pendapatan_orangtua) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pelanggaran Berat</label>
                                <select name="pelanggaran_berat" class="w-full rounded-xl border-gray-300 text-black">
                                    <option value="Tidak Ada" {{ old('pelanggaran_berat', $wawancara->pelanggaran_berat) == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                    <option value="Ada" {{ old('pelanggaran_berat', $wawancara->pelanggaran_berat) == 'Ada' ? 'selected' : '' }}>Ada</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Rumah</label>
                                <select name="kondisi_rumah" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Rumah Sendiri', 'Kontrak / Kos', 'Numpang', 'Kurang Layak'] as $item)
                                        <option value="{{ $item }}" {{ old('kondisi_rumah', $wawancara->kondisi_rumah) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Kedhuafaan</label>
                                <select name="tingkat_keduafaan" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Sangat Dhuafa', 'Dhuafa', 'Cukup', 'Mampu'] as $item)
                                        <option value="{{ $item }}" {{ old('tingkat_keduafaan', $wawancara->tingkat_keduafaan) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai</label>
                                <input type="number" name="nilai_manajemen" min="0" max="100"
                                    value="{{ old('nilai_manajemen', $wawancara->nilai_manajemen) }}"
                                    class="w-28 rounded-xl border-gray-300 text-center text-lg font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan_manajemen" rows="4"
                                        class="w-full rounded-xl border-gray-300">{{ old('catatan_manajemen', $wawancara->catatan_manajemen) }}</textarea>
                            </div>
                        </div>
                    @endif

                    @if($unsur === 'scc')
                        <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i data-lucide="home" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Bagian SCC / Asrama</h3>
                                <p class="text-sm text-gray-500">Penilaian kebiasaan dan kesiapan tinggal di asrama.</p>
                            </div>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pewawancara</label>
                                <input type="text" name="nama_pewawancara_scc"
                                    value="{{ old('nama_pewawancara_scc', $wawancara->nama_pewawancara_scc) }}"
                                    class="w-full rounded-xl border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Merokok</label>
                                <select name="merokok" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Tidak', 'Ya'] as $item)
                                        <option value="{{ $item }}" {{ old('merokok', $wawancara->merokok) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mengaji</label>
                                <select name="mengaji" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Mahir', 'Lancar', 'Terbata-bata', 'Belum'] as $item)
                                        <option value="{{ $item }}" {{ old('mengaji', $wawancara->mengaji) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sholat</label>
                                <select name="sholat" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Lengkap', 'Sering', 'Jarang-Jarang', 'Tidak Pernah'] as $item)
                                        <option value="{{ $item }}" {{ old('sholat', $wawancara->sholat) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai</label>
                                <input type="number" name="nilai_scc" min="0" max="100"
                                    value="{{ old('nilai_scc', $wawancara->nilai_scc) }}"
                                    class="w-28 rounded-xl border-gray-300 text-center text-lg font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan_scc" rows="4"
                                        class="w-full rounded-xl border-gray-300">{{ old('catatan_scc', $wawancara->catatan_scc) }}</textarea>
                            </div>
                        </div>
                    @endif

                    @if($unsur === 'instruktur')
                        <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Bagian Instruktur</h3>
                                <p class="text-sm text-gray-500">Penilaian kemampuan dasar dan motivasi belajar.</p>
                            </div>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Instruktur</label>
                                <input type="text" name="nama_instruktur"
                                    value="{{ old('nama_instruktur', $wawancara->nama_instruktur) }}"
                                    class="w-full rounded-xl border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Keahlian</label>
                                <input type="text"
                                    value="{{ $pendaftaran->jurusan->nama_jurusan ?? '-' }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100 text-gray-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lulus dari RGI Mau Berbuat Apa</label>
                                <textarea name="rencana_setelah_lulus" rows="3"
                                        class="w-full rounded-xl border-gray-300">{{ old('rencana_setelah_lulus', $wawancara->rencana_setelah_lulus) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Level Pengetahuan Materi</label>
                                <select name="level_pengetahuan_materi" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                        <option value="{{ $item }}" {{ old('level_pengetahuan_materi', $wawancara->level_pengetahuan_materi) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kemampuan Dasar</label>
                                <select name="kemampuan_dasar" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                        <option value="{{ $item }}" {{ old('kemampuan_dasar', $wawancara->kemampuan_dasar) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Motivasi Belajar</label>
                                <select name="motivasi_belajar" class="w-full rounded-xl border-gray-300 text-black">
                                    @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                        <option value="{{ $item }}" {{ old('motivasi_belajar', $wawancara->motivasi_belajar) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai</label>
                                <input type="number" name="nilai_instruktur" min="0" max="100"
                                    value="{{ old('nilai_instruktur', $wawancara->nilai_instruktur) }}"
                                    class="w-28 rounded-xl border-gray-300 text-center text-lg font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan_instruktur" rows="4"
                                        class="w-full rounded-xl border-gray-300">{{ old('catatan_instruktur', $wawancara->catatan_instruktur) }}</textarea>
                            </div>
                        </div>
                    @endif

                    @if($unsur === 'selesai')
                        <div class="p-8 text-center">
                            <div class="mx-auto w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                                <i data-lucide="badge-check" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Semua Unsur Wawancara Selesai</h3>
                            <p class="text-gray-500 mt-2">Data wawancara sudah lengkap dan siap diverifikasi kelulusannya.</p>
                        </div>
                    @endif

                    @if($unsur !== 'selesai')
                        <div class="px-5 py-4 bg-gray-50 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-indigo-600"></i>
                                Setelah disimpan, sistem akan kembali ke daftar wawancara.
                            </p>

                            <button type="submit"
                                    name="action"
                                    value="submit"
                                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold">
                                Simpan Penilaian →
                            </button>
                        </div>
                    @endif
                </form>

            </div>
        </div>

    </div>
</x-app-layout>