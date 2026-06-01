<x-app-layout>
     <div class="w-full mx-auto py-5 ">
        <div class="bg-white p-8 rounded-lg shadow">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Manajemen Kewenangan
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Kelola role dan hak akses untuk setiap pengguna
                        </p>
                    </div>
                </div>
                @if(auth()->user()->canCreateMenu('Kewenangan'))
                    <a href="{{ route('admin.roles.create') }}"
                    class="flex items-center gap-3 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Tambah Role
                    </a>
                @endif
            </div>

            {{-- Filter --}}
            <form action="{{ route('admin.roles.index') }}" method="GET" class="flex items-center justify-between mb-10">
                <div class="relative w-96">
                    <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama role" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-200 focus:border-blue-500">
                </div>

                <a href="{{ route('admin.roles.index') }}" class="border border-gray-300 rounded-lg p-3 hover:bg-gray-100">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </a>

            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto mt-8 bg-white rounded-2xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">
                                Nama Role
                            </th>

                            <th class="px-6 py-4 text-left font-semibold">
                                Deskripsi
                            </th>

                            <th class="px-6 py-4 text-center font-semibold">
                                Jumlah User
                            </th>

                            <th class="px-6 py-4 text-left font-semibold">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left font-semibold">
                                Tanggal Buat
                            </th>

                            <th class="px-6 py-4 text-center font-semibold">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($roles as $role)

                            <tr class="border-t hover:bg-gray-50 transition">

                                {{-- Nama Role --}}
                                <td class="px-6 py-5 font-medium text-gray-800">
                                    {{ $role->nama }}
                                </td>
                                {{-- Deskripsi --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $role->deskripsi ?? '-' }}
                                </td>
                                {{-- Jumlah User --}}
                                <td class="px-6 py-5 text-center">
                                    {{ $role->users_count ?? 0 }}
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-5">

                                    @if ($role->is_active)
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

                                {{-- Tanggal --}}
                                <td class="px-6 py-5">
                                    {{ $role->created_at->format('d M Y, H:i') }}
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-4">

                                        {{-- EDIT --}}
                                        @if(auth()->user()->canUpdateMenu('Kewenangan'))
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-800 transition">
                                                <i data-lucide="square-pen" class="w-5 h-5"></i>
                                            </a>
                                        @endif

                                        {{-- DELETE --}}
                                        @if(auth()->user()->canDeleteMenu('Kewenangan'))
                                            <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="button" onclick="confirmDelete('delete-form-{{ $role->id }}')" class="text-red-500 hover:text-red-700 transition">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>

                                            </form>
                                        @endif

                                        {{-- PERMISSION --}}
                                        @if(auth()->user()->canUpdateMenu('Kewenangan'))
                                            <a href="{{ route('admin.roles.permission', $role->id) }}" class="text-yellow-500 hover:text-yellow-700 transition">
                                                <i data-lucide="list" class="w-5 h-5"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>

                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    Data role belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination text --}}
            <div class="flex justify-between items-center mt-4 text-sm">
                <p>
                    Menampilkan {{ $roles->firstItem() ?? 0 }} sampai {{ $roles->lastItem() ?? 0 }}
                    dari {{ $roles->total() ?? 0 }} data
                </p>

                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>