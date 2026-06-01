<x-app-layout>
<div class="w-full">
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5">
            <div class="flex items-center justify-between">
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
                        <h2 class="text-white font-bold text-lg">{{ $pendaftaran->nama }}</h2>
                        <p class="text-blue-100 text-sm">
                            {{ $pendaftaran->kode_pendaftaran }} ·
                            {{ $pendaftaran->cabang->nama_cabang ?? '-' }} ·
                            {{ $pendaftaran->jurusan->nama_jurusan ?? '-' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.wawancara.index') }}"
                   class="px-4 py-2 rounded-xl bg-white/20 text-white text-sm font-semibold hover:bg-white/30">
                    ← Kembali
                </a>
            </div>
        </div>

        <div class="p-6 space-y-5">

            @if(!$wawancara)
                <div class="text-center py-10 text-gray-400">Belum ada data wawancara.</div>
            @else

            {{-- OPERATOR --}}
            <div class="rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">Operator</h3>
                    @if($wawancara->rekomendasi_operator)
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                    @else
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">Nama Operator</p><p class="font-medium text-gray-800">{{ $wawancara->nama_operator ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Rekomendasi Awal</p><p class="font-medium text-gray-800">{{ $wawancara->rekomendasi ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Rekomendasi Operator</p>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $wawancara->rekomendasi_operator === 'direkomendasikan' ? 'bg-green-100 text-green-700' :
                               ($wawancara->rekomendasi_operator === 'dipertimbangkan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                            {{ ucwords(str_replace('_', ' ', $wawancara->rekomendasi_operator ?? '-')) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- MANAJEMEN --}}
            <div class="rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">Manajemen</h3>
                    @if(!is_null($wawancara->nilai_manajemen))
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                    @else
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">Pewawancara</p><p class="font-medium text-gray-800">{{ $wawancara->nama_pewawancara_manajemen ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Pekerjaan Ayah</p><p class="font-medium text-gray-800">{{ $wawancara->pekerjaan_ayah ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Pekerjaan Ibu</p><p class="font-medium text-gray-800">{{ $wawancara->pekerjaan_ibu ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Pendapatan Keluarga</p><p class="font-medium text-gray-800">{{ $wawancara->pendapatan_orangtua ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Pelanggaran Berat</p><p class="font-medium text-gray-800">{{ $wawancara->pelanggaran_berat ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Kondisi Rumah</p><p class="font-medium text-gray-800">{{ $wawancara->kondisi_rumah ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Tingkat Keduafaan</p><p class="font-medium text-gray-800">{{ $wawancara->tingkat_keduafaan ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Nilai</p>
                        <span class="text-2xl font-bold text-indigo-600">{{ $wawancara->nilai_manajemen ?? '-' }}</span>
                    </div>
                    <div class="col-span-2 md:col-span-3"><p class="text-xs text-gray-400">Catatan</p><p class="font-medium text-gray-800">{{ $wawancara->catatan_manajemen ?? '-' }}</p></div>
                </div>
            </div>

            {{-- SCC --}}
            <div class="rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i data-lucide="home" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">SCC / Asrama</h3>
                    @if(!is_null($wawancara->nilai_scc))
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                    @else
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">Pewawancara</p><p class="font-medium text-gray-800">{{ $wawancara->nama_pewawancara_scc ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Merokok</p><p class="font-medium text-gray-800">{{ $wawancara->merokok ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Mengaji</p><p class="font-medium text-gray-800">{{ $wawancara->mengaji ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Sholat</p><p class="font-medium text-gray-800">{{ $wawancara->sholat ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Nilai</p>
                        <span class="text-2xl font-bold text-purple-600">{{ $wawancara->nilai_scc ?? '-' }}</span>
                    </div>
                    <div class="col-span-2 md:col-span-3"><p class="text-xs text-gray-400">Catatan</p><p class="font-medium text-gray-800">{{ $wawancara->catatan_scc ?? '-' }}</p></div>
                </div>
            </div>

            {{-- INSTRUKTUR --}}
            <div class="rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">Instruktur</h3>
                    @if(!is_null($wawancara->nilai_instruktur))
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                    @else
                        <span class="ml-auto px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">Nama Instruktur</p><p class="font-medium text-gray-800">{{ $wawancara->nama_instruktur ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Level Pengetahuan</p><p class="font-medium text-gray-800">{{ $wawancara->level_pengetahuan_materi ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Kemampuan Dasar</p><p class="font-medium text-gray-800">{{ $wawancara->kemampuan_dasar ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Motivasi Belajar</p><p class="font-medium text-gray-800">{{ $wawancara->motivasi_belajar ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Nilai</p>
                        <span class="text-2xl font-bold text-teal-600">{{ $wawancara->nilai_instruktur ?? '-' }}</span>
                    </div>
                    <div class="col-span-2 md:col-span-3"><p class="text-xs text-gray-400">Catatan</p><p class="font-medium text-gray-800">{{ $wawancara->catatan_instruktur ?? '-' }}</p></div>
                </div>
            </div>

            {{-- NILAI AKHIR --}}
            <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 p-6 flex items-center justify-between text-white">
                <div>
                    <p class="text-blue-100 text-sm">Nilai Akhir Wawancara</p>
                    <p class="text-4xl font-bold mt-1">{{ $wawancara->nilai_akhir ? number_format($wawancara->nilai_akhir, 0) : '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm">Rekomendasi Akhir</p>
                    <p class="text-xl font-bold mt-1">{{ $wawancara->rekomendasi_akhir ?? '-' }}</p>
                </div>
            </div>

            @endif
        </div>
    </div>
</div>
</x-app-layout>