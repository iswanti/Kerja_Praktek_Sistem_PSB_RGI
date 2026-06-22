<x-app-layout>
    <div class="w-full mx-auto py-5 ">
        <div class="bg-white p-8 rounded-lg shadow">

                {{-- HEADER --}}
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 border-b border-gray-200 pb-6">

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-sm">
                        <i data-lucide="menu" class="w-7 h-7"></i>
                    </div>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                Data Pendaftaran
                            </h1>

                            <p class="text-sm text-gray-500 mt-1">
                                Kelola semua data pendaftaran calon siswa yang mendaftar
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:justify-end">
                        @if(auth()->user()->canCreateMenu('Pendaftaran'))
                            <a href="{{ route('pendaftaran.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                                Tambah Data
                            </a>
                        @endif

                        <a href="{{ route('admin.pendaftaran.download', request()->all()) }}"
                           class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                            <i data-lucide="download" class="w-5 h-5"></i> Download
                        </a>

                    </div>
                </div>

                {{-- FILTER --}}
                <form method="GET"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-4 mt-8">

                    {{-- SEARCH --}}
                    <div class="relative">
                        <i data-lucide="search"
                        class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>

                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari kode / nama..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>

                    {{-- TAHUN AJARAN --}}
                    <select name="tahun_periode"
                            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm">

                        <option value="">Semua Tahun</option>

                        @foreach($tahunPeriodes as $tahun)
                            <option value="{{ $tahun }}"
                                {{ request('tahun_periode') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach

                    </select>

                    {{-- CABANG --}}
                    @if($isSuperadmin)
                        <select name="cabang_id"
                                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm">

                            <option value="">Semua Cabang</option>

                            @foreach($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                    {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach

                        </select>
                    @endif

                    {{-- JURUSAN --}}
                    <select name="jurusan_id"
                            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm">

                        <option value="">Semua Jurusan</option>

                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}"
                                {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach

                    </select>

                    {{-- GELOMBANG --}}
                    <select name="gelombang_id"
                            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm">

                        <option value="">Semua Gelombang</option>

                        @foreach($gelombangs as $gelombang)
                            <option value="{{ $gelombang->id }}"
                                {{ request('gelombang_id') == $gelombang->id ? 'selected' : '' }}>
                                {{ $gelombang->nama_gelombang }}
                            </option>
                        @endforeach

                    </select>

                    {{-- STATUS --}}
                    <select name="status"
                            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm">

                        <option value="">Semua Status</option>

                        <option value="menunggu_verifikasi"
                            {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                            Menunggu Verifikasi
                        </option>

                        <option value="seleksi_pretest"
                            {{ request('status') == 'seleksi_pretest' ? 'selected' : '' }}>
                            Seleksi Pretest
                        </option>

                        <option value="wawancara"
                            {{ request('status') == 'wawancara' ? 'selected' : '' }}>
                            Wawancara
                        </option>

                        <option value="verifikasi_kelulusan_siswa"
                            {{ request('status') == 'verifikasi_kelulusan_siswa' ? 'selected' : '' }}>
                            Verifikasi Kelulusan
                        </option>

                        <option value="diterima"
                            {{ request('status') == 'diterima' ? 'selected' : '' }}>
                            Diterima
                        </option>

                        <option value="cadangan"
                            {{ request('status') == 'cadangan' ? 'selected' : '' }}>
                            Cadangan
                        </option>

                        <option value="ditolak"
                            {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>

                    </select>

                    {{-- BUTTON --}}
                    <div class="flex gap-2">

                        <button type="submit"
                                class="flex-1 rounded-2xl bg-blue-600 text-white py-3 font-semibold hover:bg-blue-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.pendaftaran.index') }}"
                        class="px-4 rounded-2xl border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            Reset
                        </a>

                    </div>

                </form>

                {{-- TABLE --}}
                <div class="overflow-x-auto mt-8 bg-white rounded-2xl border border-gray-100">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 text-gray-700">
                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">No</th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Kode Daftar
                                </th>
                                <th class="px-6 py-4 text-left font-semibold">
                                    Nama
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Jurusan
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Cabang
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Tanggal Buat
                                </th>

                                {{-- <th class="px-6 py-4 text-left font-semibold">
                                    Gelombang
                                </th> --}}

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($pendaftarans as $item)

                                <tr class="border-t hover:bg-gray-50 transition">

                                    <td class="px-6 py-5">
                                        {{ $loop->iteration + ($pendaftarans->currentPage() - 1) * $pendaftarans->perPage() }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="font-medium text-gray-800">
                                            {{ $item->kode_pendaftaran }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        {{ $item->nama }}
                                    </td>

                                    <td class="px-6 py-5">
                                        {{ $item->jurusan->nama_jurusan }}
                                    </td>

                                    <td class="px-6 py-5">
                                        {{ $item->cabang->nama_cabang }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @php
                                            $statusLabels = [
                                                'menunggu_verifikasi' => [
                                                    'text' => 'Menunggu Verifikasi',
                                                    'class' => 'bg-yellow-100 text-yellow-700',
                                                ],
                                                'seleksi_pretest' => [
                                                    'text' => 'Seleksi Pretest',
                                                    'class' => 'bg-blue-100 text-blue-700',
                                                ],
                                                'wawancara' => [
                                                    'text' => 'Wawancara',
                                                    'class' => 'bg-purple-100 text-purple-700',
                                                ],
                                                'verifikasi_kelulusan_siswa' => [
                                                    'text' => 'Verifikasi Kelulusan',
                                                    'class' => 'bg-teal-100 text-teal-700',
                                                ],
                                                'diterima' => [
                                                    'text' => 'Diterima',
                                                    'class' => 'bg-green-100 text-green-700',
                                                ],
                                                'cadangan' => [
                                                    'text' => 'Cadangan',
                                                    'class' => 'bg-yellow-100 text-yellow-700',
                                                ],

                                                'ditolak' => [
                                                    'text' => 'Ditolak',
                                                    'class' => 'bg-red-100 text-red-700',
                                                ],
                                            ];

                                            $status = $statusLabels[$item->status] ?? [
                                                'text' => 'Menunggu Verifikasi',
                                                'class' => 'bg-yellow-100 text-yellow-700',
                                            ];
                                        @endphp

                                        <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                            {{ $status['text'] }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- <td class="px-6 py-5">
                                        {{ substr($item->gelombang->nama_gelombang, -1) }}
                                    </td> --}}

                                    <td class="px-6 py-5">

                                        <div class="flex items-center justify-center gap-4">

                                            {{-- Lihat --}}
                                            @if(auth()->user()->canReadMenu('Pendaftaran'))
                                                <a href="{{ route('admin.pendaftaran.show', $item->id) }}"
                                                    class="relative group text-blue-600 hover:text-blue-800 inline-flex items-center justify-center transition">
                                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 hidden group-hover:block bg-blue-800 text-white text-xs px-2 py-1 rounded-md whitespace-nowrap">
                                                        Lihat Pendaftaran
                                                    </span>
                                                </a>
                                            @endif

                                            {{-- EDIT --}}
                                            @if(auth()->user()->canUpdateMenu('Pendaftaran'))
                                                <a href="{{ route('admin.pendaftaran.edit', $item->id) }}"
                                                class="relative group text-indigo-600 hover:text-indigo-800 inline-flex items-center justify-center">
                                                    <i data-lucide="square-pen" class="w-5 h-5"></i>
                                                    {{-- TOOLTIP --}}
                                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 hidden group-hover:block bg-indigo-800 text-white text-xs px-2 py-1 rounded-md whitespace-nowrap">
                                                        Edit Pendaftaran
                                                    </span>
                                                </a>
                                            @endif

                                            {{-- DELETE --}}
                                            @if(auth()->user()->canDeleteMenu('Pendaftaran'))
                                                <form id="delete-pendaftaran-{{ $item->id }}"
                                                    action="{{ route('admin.pendaftaran.destroy', $item->id) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                            onclick="confirmDelete('delete-pendaftaran-{{ $item->id }}')"
                                                            class="relative group text-red-500 hover:text-red-700 inline-flex items-center justify-center">

                                                        <i data-lucide="trash-2" class="w-5 h-5"></i>

                                                        {{-- TOOLTIP --}}
                                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2
                                                                    hidden group-hover:block
                                                                    bg-red-700 text-white text-xs px-2 py-1 rounded-md whitespace-nowrap">
                                                            Hapus Pendaftaran
                                                        </span>

                                                    </button>

                                                </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7"
                                        class="text-center py-10 text-gray-500">
                                        Data pendaftaran belum tersedia
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- FOOTER --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-6">

                    <p class="text-sm text-gray-500">
                        Menampilkan
                        {{ $pendaftarans->firstItem() ?? 0 }}
                        sampai
                        {{ $pendaftarans->lastItem() ?? 0 }}
                        dari
                        {{ $pendaftarans->total() }} data
                    </p>

                    <div>
                        {{ $pendaftarans->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>