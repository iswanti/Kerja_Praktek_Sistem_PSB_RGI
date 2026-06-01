<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white p-8 rounded-lg shadow">

            {{-- HEADER --}}
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 border-b border-gray-200 pb-6">

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-green-600 flex items-center justify-center text-white shadow-sm">
                        <i data-lucide="clipboard-check" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Hasil Pretest
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Daftar peserta yang telah mengikuti pretest
                        </p>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <form method="GET"
                class="grid grid-cols-1 md:grid-cols-4 xl:grid-cols-6 gap-4 mt-8 items-end">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama / kode..."
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm">

                @if($isSuperadmin)
                <select name="cabang_id"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm">
                    <option value="">Semua Cabang</option>

                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}"
                            {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
                @endif

                <select name="jurusan_id"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm">
                    <option value="">Semua Jurusan</option>

                    @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->id }}"
                            {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->nama_jurusan }}
                        </option>
                    @endforeach
                </select>

                <select name="gelombang_id"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm">
                    <option value="">Semua Gelombang</option>

                    @foreach($gelombangs as $gelombang)
                        <option value="{{ $gelombang->id }}"
                            {{ request('gelombang_id') == $gelombang->id ? 'selected' : '' }}>
                            {{ $gelombang->nama_gelombang }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="rounded-2xl bg-blue-600 text-white py-3 font-semibold hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('admin.pretest.index') }}"
                   class="rounded-2xl border border-gray-200 py-3 text-center font-semibold hover:bg-gray-50">
                    Reset
                </a>

            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto mt-8 border border-gray-100 rounded-2xl">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left">Kode</th>
                            <th class="px-6 py-4 text-left">Nama</th>
                            <th class="px-6 py-4 text-left">Cabang</th>
                            <th class="px-6 py-4 text-left">Jurusan</th>
                            <th class="px-6 py-4 text-left">Gelombang</th>
                            <th class="px-6 py-4 text-center">Nilai</th>
                            <th class="px-6 py-4 text-center">Kategori</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pendaftarans as $item)

                            @php
                                $nilai = $item->nilai_pretest ?? 0;

                                if ($nilai >= 80) {
                                    $badge = 'bg-green-100 text-green-700';
                                    $label = 'Sangat Baik';
                                } elseif ($nilai >= 70) {
                                    $badge = 'bg-blue-100 text-blue-700';
                                    $label = 'Baik';
                                } elseif ($nilai >= 60) {
                                    $badge = 'bg-yellow-100 text-yellow-700';
                                    $label = 'Cukup';
                                } else {
                                    $badge = 'bg-red-100 text-red-700';
                                    $label = 'Kurang';
                                }
                            @endphp

                            <tr class="border-t hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $loop->iteration + ($pendaftarans->currentPage()-1) * $pendaftarans->perPage() }}
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    {{ $item->kode_pendaftaran }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->nama }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->cabang->nama_cabang }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->jurusan->nama_jurusan }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->gelombang->nama_gelombang ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-lg text-blue-600">
                                        {{ $nilai }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ $label }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="py-10 text-center text-gray-500">
                                    Belum ada peserta yang mengikuti pretest
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-6">
                {{ $pendaftarans->links() }}
            </div>

        </div>
    </div>
</x-app-layout>