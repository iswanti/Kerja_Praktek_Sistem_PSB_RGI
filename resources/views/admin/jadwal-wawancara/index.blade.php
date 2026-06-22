<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white p-8 rounded-lg shadow">

            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="video" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Jadwal Wawancara</h1>
                        <p class="text-gray-600 mt-1">Kelola link dan jadwal wawancara per cabang, jurusan, dan unsur</p>
                    </div>
                </div>
                @if(auth()->user()->canCreateMenu('Jadwal Wawancara'))
                    <a href="{{ route('admin.jadwal-wawancara.create') }}"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Tambah Jadwal
                    </a>
                @endif
            </div>

            <form action="{{ route('admin.jadwal-wawancara.index') }}" method="GET"
                  class="flex items-center justify-between mb-8">
                <div class="relative w-96">
                    <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-gray-400"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari jadwal wawancara..."
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-200 focus:border-blue-500">
                </div>

                <a href="{{ route('admin.jadwal-wawancara.index') }}"
                   class="border border-gray-300 rounded-lg p-3 hover:bg-gray-100">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </a>
            </form>

            <div class="overflow-x-auto bg-white rounded-2xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Gelombang</th>
                            <th class="px-6 py-4 text-left font-semibold">Cabang</th>
                            <th class="px-6 py-4 text-left font-semibold">Jurusan</th>
                            <th class="px-6 py-4 text-left font-semibold">Unsur</th>
                            <th class="px-6 py-4 text-left font-semibold">Waktu</th>
                            <th class="px-6 py-4 text-left font-semibold">Link</th>
                            <th class="px-6 py-4 text-left font-semibold">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jadwals as $jadwal)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-6 py-5">
                                    {{ $jadwal->gelombang->nama_gelombang ?? '-' }}
                                </td>

                                <td class="px-6 py-5">
                                    {{ $jadwal->cabang->nama_cabang ?? '-' }}
                                </td>

                                <td class="px-6 py-5">
                                    {{ $jadwal->jurusan->nama_jurusan ?? 'Semua Jurusan' }}
                                </td>

                                <td class="px-6 py-5">
                                    {{ ucwords(str_replace('_', ' ', $jadwal->unsur)) }}
                                </td>

                                <td class="px-6 py-5">
                                    {{ $jadwal->waktu_mulai?->format('d/m/Y H:i') ?? '-' }}
                                    <br>
                                    <span class="text-gray-400">s/d</span>
                                    {{ $jadwal->waktu_selesai?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="px-6 py-5">
                                    @if ($jadwal->link_wawancara)
                                        <a href="{{ $jadwal->link_wawancara }}" target="_blank"
                                           class="text-blue-600 hover:underline">
                                            Buka Link
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if ($jadwal->is_active)
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-4">
                                        @if(auth()->user()->canUpdateMenu('Jadwal Wawancara'))
                                            <a href="{{ route('admin.jadwal-wawancara.edit', $jadwal->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 transition">
                                                <i data-lucide="square-pen" class="w-5 h-5"></i>
                                            </a>
                                        @endif
                                        
                                        @if(auth()->user()->canDeleteMenu('Jadwal Wawancara'))
                                            <form id="delete-jadwal-{{ $jadwal->id }}"
                                                action="{{ route('admin.jadwal-wawancara.destroy', $jadwal->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        onclick="confirmDelete('delete-jadwal-{{ $jadwal->id }}')"
                                                        class="text-red-500 hover:text-red-700 transition">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-500">
                                    Data jadwal wawancara belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end px-6 py-4 text-gray-500 ">
                {{ $jadwals->links() }}
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>