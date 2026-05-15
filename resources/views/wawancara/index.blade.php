<x-app-layout>
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
<div class="w-full py-2">
        {{-- ZOOM CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div class="flex items-center gap-2">
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                        <i data-lucide="video" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">Link Zoom Wawancara</h2>
                        <p class="text-sm text-gray-500">
                            Gunakan link berikut untuk sesi wawancara online
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1">
                    <div>
                        <p class="text-xs text-gray-400">Link Meeting</p>
                        <a href="#" class="text-sm font-semibold text-blue-600">
                            https://zoom.us/j/12345
                        </a>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Meeting ID</p>
                        <p class="text-sm font-semibold text-gray-900">123 456 7890</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Passcode</p>
                        <p class="text-sm font-semibold text-gray-900">RGIWAW2024</p>
                    </div>
                </div>

                <a href="#"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Buka Zoom
                </a>

            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- TOOLBAR --}}
            <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-900">Pendaftar Status Wawancara</h2>
                    <p class="text-sm text-gray-500">Daftar santri yang masuk tahap wawancara</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button class="px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold border border-indigo-200">
                        Semua
                    </button>

                    <button class="px-4 py-2 rounded-full bg-white text-gray-500 text-xs font-semibold border border-gray-200">
                        Belum Dinilai
                    </button>

                    <button class="px-4 py-2 rounded-full bg-white text-gray-500 text-xs font-semibold border border-gray-200">
                        Selesai
                    </button>

                    <input type="text"
                           placeholder="Cari nama / kode..."
                           class="rounded-xl border-gray-200 text-sm px-4 py-2">
                </div>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-[950px] w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">Pendaftar</th>
                            <th class="px-5 py-4 text-left font-semibold">Jurusan / Cabang</th>
                            <th class="px-5 py-4 text-left font-semibold">Progress Penilaian</th>
                            <th class="px-5 py-4 text-center font-semibold">Nilai Rata-rata</th>
                            <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pendaftarans as $item)
                            @php
                                $w = $item->wawancara;

                                // Hitung jumlah unsur yang sudah diisi
                                $completed = 0;

                                if ($w && !empty($w->rekomendasi_operator)) $completed++;
                                if ($w && !is_null($w->nilai_manajemen)) $completed++;
                                if ($w && !is_null($w->nilai_scc)) $completed++;
                                if ($w && !is_null($w->nilai_instruktur)) $completed++;

                                $status =
                                    $completed == 0 ? 'belum' :
                                    ($completed == 4 ? 'selesai' : 'proses');

                                $progressText =
                                    $completed == 0 ? 'Belum ada penilaian' :
                                    ($completed == 4 ? 'Semua selesai' : $completed . ' dari 4 selesai');

                                $nilai = $w?->nilai_akhir;
                            @endphp

                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                {{-- Pendaftar --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900">
                                        {{ $item->nama }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->kode_pendaftaran }}
                                    </p>
                                </td>

                                {{-- Jurusan / Cabang --}}
                                <td class="px-5 py-4">
                                    <p class="text-gray-900">
                                        {{ $item->jurusan->nama_jurusan ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->cabang->nama_cabang ?? '-' }}
                                    </p>
                                </td>

                                {{-- Progress Penilaian --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        @for($i = 1; $i <= 4; $i++)
                                            <div
                                                class="w-7 h-2 rounded-full
                                                {{
                                                    $i <= $completed
                                                        ? 'bg-indigo-600'
                                                        : 'bg-gray-200'
                                                }}">
                                            </div>
                                        @endfor

                                        <span class="text-xs text-gray-500">
                                            {{ $progressText }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap gap-1">
                                        <span class="px-2 py-1 rounded-full text-[10px]
                                            {{ $w && !empty($w->rekomendasi_operator)
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-gray-100 text-gray-500' }}
                                            font-semibold">
                                            Operator
                                        </span>

                                        <span class="px-2 py-1 rounded-full text-[10px]
                                            {{ $w && !is_null($w->nilai_manajemen)
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'bg-gray-100 text-gray-500' }}
                                            font-semibold">
                                            Manajemen
                                        </span>

                                        <span class="px-2 py-1 rounded-full text-[10px]
                                            {{ $w && !is_null($w->nilai_scc)
                                                ? 'bg-purple-100 text-purple-700'
                                                : 'bg-gray-100 text-gray-500' }}
                                            font-semibold">
                                            SCC
                                        </span>

                                        <span class="px-2 py-1 rounded-full text-[10px]
                                            {{ $w && !is_null($w->nilai_instruktur)
                                                ? 'bg-teal-100 text-teal-700'
                                                : 'bg-gray-100 text-gray-500' }}
                                            font-semibold">
                                            Instruktur
                                        </span>
                                    </div>
                                </td>

                                {{-- Nilai --}}
                                <td class="px-5 py-4 text-center">
                                    @if(!is_null($nilai))
                                        <span class="text-lg font-bold
                                            {{ $status == 'selesai'
                                                ? 'text-green-600'
                                                : 'text-indigo-600' }}">
                                            {{ number_format($nilai, 0) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('wawancara.edit', $item->id) }}"
                                        class="px-4 py-2 rounded-lg border border-indigo-500 text-indigo-600 hover:bg-indigo-50 text-xs font-semibold">
                                            Input Penilaian
                                        </a>

                                        <a href="{{ route('pendaftaran.show', $item->id) }}"
                                        class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada data wawancara.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($pendaftarans, 'links'))
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $pendaftarans->links() }}
                </div>
            @endif
        </div>

        

    </div>
</x-app-layout>