<x-app-layout>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                        <i data-lucide="list" class="w-6 h-6"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Daftar Pertanyaan
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">
                            Kelola seluruh soal pretest berdasarkan jurusan dan tipe jawaban.
                        </p>
                    </div>
                </div>
                @if(auth()->user()->canCreateMenu('Bank Soal'))
                    <a href="{{ route('admin.soal.create', ['jurusan_id' => request('jurusan_id')]) }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-semibold transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Soal
                    </a>
                @endif
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET"
              action="{{ route('admin.soal.index') }}"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Search --}}
            <div>
                <label class="text-xs text-gray-500">Cari Pertanyaan</label>
                <div class="relative mt-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari pertanyaan..."
                           class="w-full rounded-xl border-gray-200 pl-10 pr-4 py-3 text-sm text-black">
                    <i data-lucide="search"
                       class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>

            {{-- Jurusan --}}
            <div>
                <label class="text-xs text-gray-500">Jurusan</label>
                <select name="jurusan_id"
                        class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black py-3">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->id }}"
                            {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->nama_jurusan ?? $jurusan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe Soal --}}
            <div>
                <label class="text-xs text-gray-500">Tipe Soal</label>
                <select name="tipe_soal"
                        class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black py-3">
                    <option value="">Semua Tipe Soal</option>
                    <option value="umum"
                        {{ request('tipe_soal') == 'umum' ? 'selected' : '' }}>
                        Umum
                    </option>
                    <option value="kejuruan"
                        {{ request('tipe_soal') == 'kejuruan' ? 'selected' : '' }}>
                        Kejuruan
                    </option>
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 border border-blue-500 text-blue-600 hover:bg-blue-50 rounded-xl px-5 py-3 font-semibold">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter
                </button>

                <a href="{{ route('admin.soal.index') }}"
                   class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>

        {{-- Tabel Soal --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-4 font-semibold w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Pertanyaan</th>
                            <th class="px-6 py-4 font-semibold">Jurusan</th>
                            <th class="px-6 py-4 font-semibold">Tipe Jawaban</th>
                            <th class="px-6 py-4 font-semibold">Jawaban</th>
                            <th class="px-6 py-4 font-semibold">Nilai</th>
                            <th class="px-6 py-4 font-semibold text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($soals as $index => $soal)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-5 text-gray-700">
                                    {{ $soals->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-800">
                                        {{ \Illuminate\Support\Str::limit($soal->pertanyaan, 100) }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $soal->jurusan->nama_jurusan ?? '-' }}
                                </td>

                                <td class="px-6 py-5">
                                    @if($soal->tipe === 'pilihan_ganda')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Pilihan Ganda
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-600">
                                            Essay
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if($soal->jawaban_benar)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-700 font-bold">
                                            {{ $soal->jawaban_benar }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 font-medium text-gray-700">
                                    {{ $soal->bobot }}
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(auth()->user()->canUpdateMenu('Bank Soal'))
                                            <a href="{{ route('admin.soal.edit', $soal) }}"
                                            class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        @if(auth()->user()->canDeleteMenu('Bank Soal'))
                                            <form id="delete-soal-{{ $soal->id }}"
                                                action="{{ route('admin.soal.destroy', $soal) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus soal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-soal-{{ $soal->id }}')"
                                                        class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada soal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($soals->count())
                
                <div class="flex justify-end px-6 py-4 text-gray-500 ">
                    {{ $soals->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>