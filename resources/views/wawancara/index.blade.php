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
        @php
            $isAdmin = auth()->user()->role_id == 2;
        @endphp
        @if($isAdmin)
            <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            @if($jadwals->count())
                <button type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between gap-4">

                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="video" class="w-7 h-7"></i>
                        </div>

                        <div class="text-left">
                            <h2 class="font-bold text-gray-900">Link Meeting Wawancara</h2>
                            <p class="text-sm text-gray-500">
                                {{ $jadwals->whereNotNull('link_wawancara')->count() }} link meeting aktif
                            </p>
                        </div>
                    </div>

                    <i data-lucide="chevron-down"
                    class="w-5 h-5 text-gray-400 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open"
                    x-transition
                    class="mt-5 space-y-3">

                    @foreach($jadwals as $jadwal)
                        @if($jadwal->link_wawancara)
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border border-gray-100 rounded-2xl p-4">

                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="video" class="w-5 h-5"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $jadwal->unsur)) }}
                                        </h3>

                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $jadwal->gelombang->nama_gelombang ?? '' }}

                                            @if($jadwal->cabang)
                                                · {{ $jadwal->cabang->nama_cabang }}
                                            @endif

                                            @if($jadwal->jurusan)
                                                · {{ $jadwal->jurusan->nama_jurusan }}
                                            @endif

                                            @if($jadwal->waktu_mulai)
                                                · {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('d M Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1 flex-1 min-w-0">
                                    <p class="text-xs text-gray-400">Link Meeting</p>
                                    <a href="{{ $jadwal->link_wawancara }}"
                                    target="_blank"
                                    class="text-sm font-semibold text-blue-600 hover:underline truncate">
                                        {{ $jadwal->link_wawancara }}
                                    </a>
                                </div>

                                <a href="{{ $jadwal->link_wawancara }}"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shrink-0">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                    Buka
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="flex items-center gap-3 text-gray-400">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0">
                        <i data-lucide="video-off" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">Belum ada link meeting aktif</p>
                        <p class="text-sm">Link meeting wawancara belum diatur atau belum diaktifkan.</p>
                    </div>
                </div>
            @endif
            </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6"> 
            @if($jadwals->count()) 
            <div class="space-y-4"> @foreach($jadwals as $jadwal) @if($jadwal->link_wawancara) 
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 border border-gray-100 rounded-2xl p-4"> 
                    <div class="flex items-center gap-3 shrink-0"> 
                        <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center"> 
                            <i data-lucide="video" class="w-7 h-7"></i> 
                        </div> 
                        <div> 
                            <h2 class="font-bold text-gray-900"> Link Meeting {{ ucwords(str_replace('_', ' ', $jadwal->unsur)) }} </h2> 
                            <p class="text-sm text-gray-500"> {{ $jadwal->gelombang->nama_gelombang ?? '' }} @if($jadwal->cabang) · {{ $jadwal->cabang->nama_cabang }} @endif @if($jadwal->jurusan) · {{ $jadwal->jurusan->nama_jurusan }} @endif @if($jadwal->waktu_mulai) · {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('d M Y, H:i') }} @endif </p> 
                        </div> 
                    </div> 
                    <div class="flex flex-col gap-1 flex-1 min-w-0"> 
                        <p class="text-xs text-gray-400">Link Meeting</p> 
                        <a href="{{ $jadwal->link_wawancara }}" target="_blank" class="text-sm font-semibold text-blue-600 hover:underline truncate"> {{ $jadwal->link_wawancara }} </a> 
                    </div> 
                    <a href="{{ $jadwal->link_wawancara }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shrink-0"> 
                        <i data-lucide="external-link" class="w-4 h-4"></i> Buka Meeting 
                    </a> 
                </div> 
                @endif 
                @endforeach 
            </div> 
            @else 
            <div class="flex items-center gap-3 text-gray-400"> 
                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0"> 
                    <i data-lucide="video-off" class="w-7 h-7"></i> 
                </div> 
                <div> 
                    <p class="font-semibold text-gray-600">Belum ada link meeting aktif</p> 
                    <p class="text-sm">Link meeting wawancara belum diatur atau belum diaktifkan.</p> 
                </div> 
            </div> 
            @endif 
        </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- TOOLBAR --}}
            <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-900">Pendaftar Status Wawancara</h2>
                    <p class="text-sm text-gray-500">Daftar santri yang masuk tahap wawancara</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET"
                        action="{{ route('admin.wawancara.index') }}"
                        class="flex flex-wrap items-center gap-2">

                        <a href="{{ route('admin.wawancara.index') }}"
                        class="px-4 py-2 rounded-full text-xs font-semibold border
                        {{ request('status_penilaian') == null ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-white text-gray-500 border-gray-200' }}">
                            Semua
                        </a>

                        <a href="{{ route('admin.wawancara.index', ['status_penilaian' => 'belum', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-full text-xs font-semibold border
                        {{ request('status_penilaian') == 'belum' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-white text-gray-500 border-gray-200' }}">
                            Belum Dinilai
                        </a>

                        <a href="{{ route('admin.wawancara.index', ['status_penilaian' => 'selesai', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-full text-xs font-semibold border
                        {{ request('status_penilaian') == 'selesai' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-white text-gray-500 border-gray-200' }}">
                            Selesai
                        </a>

                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / kode..."
                            class="rounded-xl border-gray-200 text-sm px-4 py-2">

                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold">
                            Cari
                        </button>
                    </form>
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
                                        @if(auth()->user()->canUpdateMenu('Wawancara'))
                                            <a href="{{ route('admin.wawancara.edit', $item->id) }}"
                                            class="px-4 py-2 rounded-lg border border-indigo-500 text-indigo-600 hover:bg-indigo-50 text-xs font-semibold">
                                                Input Penilaian
                                            </a>
                                        @endif
                                        
                                        @if(auth()->user()->canReadMenu('Wawancara'))
                                            <a href="{{ route('admin.wawancara.show', $item->id) }}"
                                            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold">
                                                Detail
                                            </a>
                                        @endif
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
                <div class="flex justify-end px-6 py-4 text-gray-500 ">
                {{ $pendaftarans->links() }}
            </div>
            @endif
        </div>

        

    </div>
</x-app-layout>