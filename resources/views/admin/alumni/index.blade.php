<x-app-layout>
    <div class="w-full py-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                        <i data-lucide="list" class="w-6 h-6"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Data Alumni
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">
                            Data pendaftar tahun sebelumnya
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.alumni.create') }}"
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-semibold transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Data
                    </a>
                </div>
            </div>

            <div class="p-5 border-b border-gray-100">
                <form method="GET" action="{{ route('admin.alumni.index') }}"
                      class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama..."
                           class="rounded-xl border-gray-200 text-sm px-4 py-2.5">

                    <select name="jurusan_id"
                            class="rounded-xl border-gray-200 text-sm px-4 py-2.5">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}"
                                {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text"
                           name="angkatan"
                           value="{{ request('angkatan') }}"
                           placeholder="Angkatan, contoh: 2024"
                           class="rounded-xl border-gray-200 text-sm px-4 py-2.5">

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5">
                            Filter
                        </button>
                        <a href="{{ route('admin.alumni.index') }}"
                            class="px-4 rounded-2xl border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                                Reset
                            </a>
                        </div>

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[800px] w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">No</th>
                            <th class="px-5 py-4 text-left font-semibold">Nama</th>
                            <th class="px-5 py-4 text-left font-semibold">Jurusan</th>
                            <th class="px-5 py-4 text-left font-semibold">Angkatan</th>
                            <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($alumnis as $item)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $loop->iteration + ($alumnis->currentPage() - 1) * $alumnis->perPage() }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900">{{ $item->nama }}</p>
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->jurusan->nama_jurusan ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                                        {{ $item->angkatan }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.alumni.edit', $item->id) }}"
                                           class="text-blue-600 hover:bg-blue-50 transition">
                                            <i data-lucide="pencil" class="w-5 h-5"></i>
                                        </a>

                                        <form id="delete-alumni-{{ $item->id }}"
                                                action="{{ route('admin.alumni.destroy', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        onclick="confirmDelete('delete-alumni-{{ $item->id }}')"
                                                        class="text-red-500 hover:text-red-700 transition">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada data alumni.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-gray-100">
                {{ $alumnis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>