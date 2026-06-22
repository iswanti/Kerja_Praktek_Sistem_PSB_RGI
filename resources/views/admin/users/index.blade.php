<x-app-layout>
    
    <div class="w-full mx-auto py-5 ">
        <div class="bg-white p-8 rounded-lg shadow">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Manajemen Pengguna
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Kelola seluruh pengguna sistem
                        </p>
                    </div>
                </div>
                @if(auth()->user()->canCreateMenu('Pengguna'))
                    <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Tambah User
                    </a>
                @endif
            </div>
            

            {{-- Filter --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center justify-between mb-10">
                <div class="relative w-96">
                    <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-200 focus:border-blue-500">
                </div>

                <a href="{{ route('admin.users.index') }}" class="border border-gray-300 rounded-lg p-3 hover:bg-gray-100">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </a>

            </form>

            {{-- Table --}}
            <div class="bg-white border rounded-2xl overflow-hidden">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b">
                        <tr>

                            <th class="px-6 py-4 text-left">
                                Nama
                            </th>

                            <th class="px-6 py-4 text-left">
                                Email
                            </th>

                            <th class="px-6 py-4 text-left">
                                Role
                            </th>

                            <th class="px-6 py-4 text-left">
                                Tanggal Dibuat
                            </th>

                            <th class="px-6 py-4 text-center">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach ($users as $user)

                            <tr class="hover:bg-gray-50">

                                {{-- Nama --}}
                                <td class="px-6 py-4">
                                    {{ $user->name }}
                                </td>

                                {{-- Email --}}
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>

                                {{-- Role --}}
                                <td class="px-6 py-4">
                                    {{ $user->role->nama ?? '-' }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-center gap-4">

                                        {{-- EDIT --}}
                                        @if(auth()->user()->canUpdateMenu('Pengguna'))
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 transition">
                                                <i data-lucide="square-pen" class="w-5 h-5"></i>
                                            </a>
                                        @endif

                                        {{-- UBAH PASSWORD --}}
                                        @if(auth()->user()->canUpdateMenu('Pengguna'))
                                            <a href="{{ route('admin.users.password.edit', $user->id) }}"
                                            class="text-yellow-500 hover:text-yellow-700 transition">
                                                <i data-lucide="key-round" class="w-5 h-5"></i>
                                            </a>
                                        @endif

                                        {{-- DELETE --}}
                                        @if(auth()->user()->canDeleteMenu('Pengguna'))
                                            <form id="delete-user-{{ $user->id }}"
                                                action="{{ route('admin.users.destroy', $user->id) }}"
                                                method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        onclick="confirmDelete('delete-user-{{ $user->id }}')"
                                                        class="text-red-500 hover:text-red-700 transition">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="flex justify-end px-6 py-4 text-gray-500 ">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>