<x-app-layout>
    <div class="w-full">
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div x-data="{ fotoOpen: false }">
                        {{-- Trigger foto --}}
                        <div class="w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center font-bold text-lg overflow-hidden cursor-pointer"
                            @click="$pendaftaran->pas_foto ? fotoOpen = true : null">
                            @if($pendaftaran->pas_foto)
                                <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}"
                                    alt="{{ $pendaftaran->nama }}"
                                    class="w-full h-full object-cover hover:opacity-80 transition"
                                    @click="fotoOpen = true"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <span style="display:none" class="w-full h-full items-center justify-center font-bold text-lg">
                                    @php
                                        $kata = preg_split('/\s+/', trim($pendaftaran->nama ?? ''), -1, PREG_SPLIT_NO_EMPTY);
                                        echo count($kata) >= 2
                                            ? strtoupper(substr($kata[0],0,1).substr($kata[1],0,1))
                                            : strtoupper(substr($kata[0] ?? 'NA', 0, 2));
                                    @endphp
                                </span>
                            @else
                                @php
                                    $kata = preg_split('/\s+/', trim($pendaftaran->nama ?? ''), -1, PREG_SPLIT_NO_EMPTY);
                                    $inisial = count($kata) >= 2
                                        ? strtoupper(substr($kata[0],0,1).substr($kata[1],0,1))
                                        : strtoupper(substr($kata[0] ?? 'NA', 0, 2));
                                @endphp
                                {{ $inisial }}
                            @endif
                        </div>

                        {{-- Modal Lightbox --}}
                        @if($pendaftaran->pas_foto)
                            <div x-show="fotoOpen"
                                x-transition.opacity
                                @click.self="fotoOpen = false"
                                @keydown.escape.window="fotoOpen = false"
                                class="fixed inset-0 z-[999] bg-black/70 flex items-center justify-center p-4"
                                style="display:none">

                                <div class="relative max-w-sm w-full" @click.stop>

                                    {{-- Tombol tutup --}}
                                    <button @click="fotoOpen = false"
                                            class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-gray-700 flex items-center justify-center shadow hover:bg-gray-100 z-10">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>

                                    {{-- Foto --}}
                                    <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}"
                                        alt="{{ $pendaftaran->nama }}"
                                        class="w-full rounded-2xl shadow-2xl object-cover">

                                    {{-- Nama di bawah foto --}}
                                    <div class="mt-3 text-center text-white">
                                        <p class="font-semibold">{{ $pendaftaran->nama }}</p>
                                        <p class="text-sm text-white/70">{{ $pendaftaran->kode_pendaftaran }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                        <div>
                            <h2 class="text-white font-bold text-lg">{{ $pendaftaran->nama ?? '-' }}</h2>
                            <p class="text-blue-100 text-sm">
                                {{ $pendaftaran->kode_pendaftaran ?? '-' }} ·
                                {{ $pendaftaran->jurusan->nama_jurusan ?? '-' }} ·
                                {{ $pendaftaran->cabang->nama_cabang ?? '-' }} · Tahap Wawancara
                            </p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full bg-white/20 text-white text-sm font-semibold">Wawancara</span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                {{-- STAGE PROGRESS --}}
                <div class="rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center">
                        @php
                            $stages = ['Operator', 'Manajemen', 'SCC/Asrama', 'Instruktur'];
                            $completed = [
                                !empty($wawancara->rekomendasi_operator),
                                !is_null($wawancara->nilai_manajemen),
                                !is_null($wawancara->nilai_scc),
                                !is_null($wawancara->nilai_instruktur),
                            ];
                            $currentIndex = array_search(false, $completed, true);
                            if ($currentIndex === false) $currentIndex = count($stages);
                        @endphp

                        @foreach($stages as $index => $stage)
                            <div class="flex-1 flex flex-col items-center text-center relative">
                                @if(!$loop->last)
                                    <div class="absolute top-5 left-1/2 w-full h-0.5 {{ $index < $currentIndex ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                @endif
                                <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2
                                    {{ $completed[$index] ? 'bg-blue-600 text-white border-blue-600' : ($index == $currentIndex ? 'bg-white text-blue-600 border-blue-600 ring-4 ring-blue-100' : 'bg-gray-100 text-gray-400 border-gray-200') }}">
                                    {{ $completed[$index] ? '✓' : $index + 1 }}
                                </div>
                                <p class="text-xs mt-2 font-semibold {{ ($completed[$index] || $index == $currentIndex) ? 'text-blue-600' : 'text-gray-400' }}">
                                    {{ $stage }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SWEETALERT --}}
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if(session('success'))
                    <script>
                        Swal.fire({ title: 'Berhasil!', text: '{{ session("success") }}', icon: 'success', confirmButtonText: 'OK' });
                    </script>
                @endif

                {{-- FORM --}}
                <form action="{{ route('admin.wawancara.store', $pendaftaran->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="unsur" value="{{ $unsur }}">

                    {{-- ===================== OPERATOR ===================== --}}
                    @if($unsur === 'operator')
                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="user-check" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Bagian Operator</h3>
                                    <p class="text-sm text-gray-500">Verifikasi data dasar dan rekomendasi awal.</p>
                                </div>
                            </div>

                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Input --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Operator <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_operator"
                                        value="{{ old('nama_operator', $wawancara->nama_operator) }}"
                                        class="w-full rounded-xl border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rekomendasi <span class="text-red-500">*</span></label>
                                    <select name="rekomendasi_operator" class="w-full rounded-xl border-gray-300">
                                        <option value="">Pilih Rekomendasi</option>
                                        @foreach(['direkomendasikan', 'dipertimbangkan', 'tidak_direkomendasikan'] as $item)
                                            <option value="{{ $item }}" {{ old('rekomendasi_operator', $wawancara->rekomendasi_operator) == $item ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $item)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Data dari pendaftaran (readonly) --}}
                                <div class="md:col-span-2 border-t pt-4">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Data Santri dari Pendaftaran</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Santri</label>
                                            <input type="text" value="{{ $pendaftaran->nama ?? '-' }}" readonly
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Usia Santri</label>
                                            <input type="text" value="{{ $pendaftaran->umur ?? '-' }} tahun" readonly
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anak Ke-</label>
                                            <input type="text" value="{{ $pendaftaran->anak_ke ?? '-' }}" readonly
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Terakhir</label>
                                            <input type="text" value="{{ $pendaftaran->pendidikan ?? '-' }}" readonly
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                            <input type="text"
                                                value="{{ implode(', ', array_filter([$pendaftaran->alamat, $pendaftaran->kelurahan_nama, $pendaftaran->kecamatan_nama, $pendaftaran->kabupaten_nama, $pendaftaran->provinsi_nama])) }}"
                                                readonly
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rekomendasi Awal
                                            </label>

                                            <input type="text"
                                            name="rekomendasi"
                                            value="{{ old('rekomendasi',  $wawancara->rekomendasi ?? $pendaftaran->rekomendasi ?: 'Tidak ada') }}"
                                            class="w-full rounded-xl border-gray-300">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- ===================== MANAJEMEN ===================== --}}
                    @if($unsur === 'manajemen')
                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Bagian Manajemen</h3>
                                    <p class="text-sm text-gray-500">Penilaian oleh tim manajemen RGI.</p>
                                </div>
                            </div>

                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Data dari pendaftaran (readonly) --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Santri</label>
                                    <input type="text" value="{{ $pendaftaran->nama ?? '-' }}" readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pewawancara <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_pewawancara_manajemen"
                                        value="{{ old('nama_pewawancara_manajemen', $wawancara->nama_pewawancara_manajemen) }}"
                                        class="w-full rounded-xl border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ayah/Wali</label>
                                    <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $wawancara->pekerjaan_ayah ?? $pendaftaran->pekerjaan_wali) }}" readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $wawancara->pekerjaan_ibu ?? $pendaftaran->pekerjaan_ibu) }}"readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                @php
                                    $pendapatanOptions = [
                                        'Rp 500.000',
                                        'Rp 501.000 - Rp 1.000.000',
                                        'Rp 1.000.001 - Rp 1.500.000',
                                        'Rp 1.500.001 - Rp 2.000.000',
                                        'Rp 2.000.001 - Rp 2.500.000',
                                        'Rp 2.500.001 - Rp 3.000.000',
                                        'Rp 3.000.000',
                                        'UMR/UMP',
                                    ];

                                    $pendapatanValue = old('pendapatan_orangtua', $wawancara->pendapatan_orangtua ?? $pendaftaran->pendapatan_keluarga ?? '');
                                    $isLainnya = $pendapatanValue && !in_array($pendapatanValue, $pendapatanOptions);
                                @endphp

                                <div x-data="{
                                    pendapatan: '{{ $isLainnya ? 'Lainnya' : $pendapatanValue }}',
                                    pendapatanLainnya: '{{ $isLainnya ? $pendapatanValue : '' }}'
                                }">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Rata-rata Pendapatan Keluarga Setiap Bulan <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        x-model="pendapatan"
                                        @change="
                                            if (pendapatan !== 'Lainnya') {
                                                $refs.pendapatanFinal.value = pendapatan
                                            } else {
                                                $refs.pendapatanFinal.value = pendapatanLainnya
                                            }
                                        "
                                        class="w-full rounded-xl border-gray-300">

                                        <option value="">Pilih Rata-rata Pendapatan</option>

                                        @foreach($pendapatanOptions as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach

                                        <option value="Lainnya">Lainnya</option>
                                    </select>

                                    <div x-show="pendapatan === 'Lainnya'" x-cloak class="mt-3">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Isi Pendapatan Lainnya
                                        </label>

                                        <input type="text"
                                            x-model="pendapatanLainnya"
                                            @input="$refs.pendapatanFinal.value = pendapatanLainnya"
                                            class="w-full rounded-xl border-gray-300"
                                            placeholder="Contoh: Rp 4.500.000">
                                    </div>

                                    <input type="hidden"
                                        name="pendapatan_orangtua"
                                        x-ref="pendapatanFinal"
                                        value="{{ $pendapatanValue }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pelanggaran Berat <span class="text-red-500">*</span></label>
                                    <select name="pelanggaran_berat" class="w-full rounded-xl border-gray-300">
                                        <option value="Tidak Ada" {{ old('pelanggaran_berat', $wawancara->pelanggaran_berat ?? 'Tidak Ada') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                        <option value="Ada" {{ old('pelanggaran_berat', $wawancara->pelanggaran_berat) == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Rumah <span class="text-red-500">*</span></label>
                                    <select name="kondisi_rumah" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Rumah Sendiri', 'Kontrak / Kos', 'Numpang', 'Kurang Layak'] as $item)
                                            <option value="{{ $item }}" {{ old('kondisi_rumah', $wawancara->kondisi_rumah) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Keduafaan <span class="text-red-500">*</span></label>
                                    <select name="tingkat_keduafaan" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Sangat Dhuafa', 'Dhuafa', 'Cukup', 'Mampu'] as $item)
                                            <option value="{{ $item }}" {{ old('tingkat_keduafaan', $wawancara->tingkat_keduafaan) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Dari pendaftaran --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Motivasi Masuk RGI <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="motivasi" rows="3"
                                        class="w-full rounded-xl border-gray-300">{{ old('motivasi', $wawancara->motivasi ?? $pendaftaran->motivasi) }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Riwayat Penyakit <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        name="riwayat_penyakit"
                                        value="{{ old('riwayat_penyakit',  $wawancara->riwayat_penyakit ?? $pendaftaran->penyakit ?: 'Tidak ada') }}"
                                        class="w-full rounded-xl border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai <span class="text-red-500">*</span></label>
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
                        </div>
                    @endif

                    {{-- ===================== SCC ===================== --}}
                    @if($unsur === 'scc')
                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="home" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Bagian SCC / Asrama</h3>
                                    <p class="text-sm text-gray-500">Penilaian kebiasaan dan kesiapan tinggal di asrama.</p>
                                </div>
                            </div>

                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Santri</label>
                                    <input type="text" value="{{ $pendaftaran->nama ?? '-' }}" readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pewawancara <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_pewawancara_scc"
                                        value="{{ old('nama_pewawancara_scc', $wawancara->nama_pewawancara_scc) }}"
                                        class="w-full rounded-xl border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Merokok <span class="text-red-500">*</span></label>
                                    <select name="merokok" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Tidak', 'Ya'] as $item)
                                            <option value="{{ $item }}" {{ old('merokok', $wawancara->merokok) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mengaji <span class="text-red-500">*</span></label>
                                    <select name="mengaji" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Mahir', 'Lancar', 'Terbata-bata', 'Belum'] as $item)
                                            <option value="{{ $item }}" {{ old('mengaji', $wawancara->mengaji) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sholat <span class="text-red-500">*</span></label>
                                    <select name="sholat" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Lengkap', 'Sering', 'Jarang-Jarang', 'Tidak Pernah'] as $item)
                                            <option value="{{ $item }}" {{ old('sholat', $wawancara->sholat) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai <span class="text-red-500">*</span></label>
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
                        </div>
                    @endif

                    {{-- ===================== INSTRUKTUR ===================== --}}
                    @if($unsur === 'instruktur')
                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-5 py-4 border-b flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Bagian Instruktur Kelas</h3>
                                    <p class="text-sm text-gray-500">Penilaian kemampuan dasar dan motivasi belajar.</p>
                                </div>
                            </div>

                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Santri</label>
                                    <input type="text" value="{{ $pendaftaran->nama ?? '-' }}" readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Instruktur <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_instruktur"
                                        value="{{ old('nama_instruktur', $wawancara->nama_instruktur) }}"
                                        class="w-full rounded-xl border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Keahlian</label>
                                    <input type="text" value="{{ $pendaftaran->jurusan->nama_jurusan ?? '-' }}" readonly
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Level Pengetahuan Materi <span class="text-red-500">*</span></label>
                                    <select name="level_pengetahuan_materi" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                            <option value="{{ $item }}" {{ old('level_pengetahuan_materi', $wawancara->level_pengetahuan_materi) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kemampuan Dasar <span class="text-red-500">*</span></label>
                                    <select name="kemampuan_dasar" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                            <option value="{{ $item }}" {{ old('kemampuan_dasar', $wawancara->kemampuan_dasar) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Motivasi Belajar <span class="text-red-500">*</span></label>
                                    <select name="motivasi_belajar" class="w-full rounded-xl border-gray-300">
                                        @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $item)
                                            <option value="{{ $item }}" {{ old('motivasi_belajar', $wawancara->motivasi_belajar) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai <span class="text-red-500">*</span></label>
                                    <input type="number" name="nilai_instruktur" min="0" max="100"
                                        value="{{ old('nilai_instruktur', $wawancara->nilai_instruktur) }}"
                                        class="w-28 rounded-xl border-gray-300 text-center text-lg font-bold">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lulus dari RGI Mau Berbuat Apa</label>
                                    <textarea name="rencana_setelah_lulus" rows="3"
                                        class="w-full rounded-xl border-gray-300">{{ old('rencana_setelah_lulus', $wawancara->rencana_setelah_lulus) }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                    <textarea name="catatan_instruktur" rows="4"
                                        class="w-full rounded-xl border-gray-300">{{ old('catatan_instruktur', $wawancara->catatan_instruktur) }}</textarea>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- ===================== SELESAI ===================== --}}
                    @if($unsur === 'selesai')
                        <div class="rounded-2xl border border-gray-100 p-8 text-center">
                            <div class="mx-auto w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                                <i data-lucide="badge-check" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Semua Unsur Wawancara Selesai</h3>
                            <p class="text-gray-500 mt-2">Data wawancara sudah lengkap dan siap diverifikasi kelulusannya.</p>
                        </div>
                    @endif

                    @if($unsur === 'sudah_diisi')
                        <div class="rounded-2xl border border-yellow-100 bg-yellow-50 p-8 text-center">
                            <div class="mx-auto w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                                <i data-lucide="lock" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Penilaian Sudah Diisi</h3>
                            <p class="text-gray-500 mt-2">Unsur wawancara Anda sudah tersimpan dan tidak dapat diubah.</p>
                            <a href="{{ route('admin.wawancara.index') }}"
                            class="mt-4 inline-block px-6 py-2 rounded-xl bg-yellow-500 text-white font-semibold hover:bg-yellow-600">
                                Kembali ke Daftar
                            </a>
                        </div>
                    @endif

                    {{-- TOMBOL SIMPAN --}}
                    @if(!in_array($unsur, ['selesai', 'sudah_diisi']))
                        <div class="px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                                Setelah disimpan, sistem akan melanjutkan ke tahap berikutnya.
                            </p>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors">
                                Simpan Penilaian →
                            </button>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</x-app-layout>