<x-app-layout>

    <div class="w-full mx-auto py-5 ">
        {{-- <div class="max-w-6xl mx-auto bg-white rounded-xl p-10"> --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            {{-- Header --}}
            <div class="flex items-center gap-6 pb-10 border-b">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Kewenangan User</h1>
                    <p class="text-gray-600 mt-1">Tambahkan Kewenangan user baru ke sistem</p>
                </div>
            </div>

            {{-- FORM --}}
            <form id = "createRoleForm" action="{{ route('admin.roles.store') }}" method="POST" class=" pt-8">

                @csrf

                {{-- Nama --}}
                <div class="mb-6">

                    <label class="block text-sm mb-2">
                        Nama Kewenangan / role
                    </label>

                    <input type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">

                </div>

                {{-- Deskripsi --}}
                <div class="mb-10">

                    <label class="block text-sm mb-2">
                        Deskripsi Kewenangan / role
                    </label>

                    <input type="text"
                        name="deskripsi"
                        value="{{ old('deskripsi') }}"
                        class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">

                </div>

                {{-- Permission --}}
                <h2 class="text-xl font-bold text-gray-900 mb-8">
                    Hak Akses Menu
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>
                            <tr class="text-left">

                                <th class="pb-6 font-semibold">
                                    Nama Menu
                                </th>

                                <th class="pb-6 font-semibold text-center">
                                    Read
                                </th>

                                <th class="pb-6 font-semibold text-center">
                                    Create
                                </th>

                                <th class="pb-6 font-semibold text-center">
                                    Update
                                </th>

                                <th class="pb-6 font-semibold text-center">
                                    Delete
                                </th>

                                <th class="pb-6 font-semibold text-center">
                                    Download
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($menus as $menu)

                                <tr class="border-t">

                                    <td class="py-5">
                                        {{ $menu->nama }}
                                    </td>

                                    {{-- READ --}}
                                    <td class="text-center">

                                        <label class="inline-flex items-center gap-2">

                                            <input type="checkbox"
                                                name="permissions[{{ $menu->id }}][read]"
                                                class="rounded text-blue-500">

                                            Aktif

                                        </label>

                                    </td>

                                    {{-- CREATE --}}
                                    <td class="text-center">

                                        <label class="inline-flex items-center gap-2">

                                            <input type="checkbox"
                                                name="permissions[{{ $menu->id }}][create]"
                                                class="rounded text-blue-500">

                                            Aktif

                                        </label>

                                    </td>

                                    {{-- UPDATE --}}
                                    <td class="text-center">

                                        <label class="inline-flex items-center gap-2">

                                            <input type="checkbox"
                                                name="permissions[{{ $menu->id }}][update]"
                                                class="rounded text-blue-500">

                                            Aktif

                                        </label>

                                    </td>

                                    {{-- DELETE --}}
                                    <td class="text-center">

                                        <label class="inline-flex items-center gap-2">

                                            <input type="checkbox"
                                                name="permissions[{{ $menu->id }}][delete]"
                                                class="rounded text-blue-500">

                                            Aktif

                                        </label>

                                    </td>

                                    {{-- DOWNLOAD --}}
                                    <td class="text-center">

                                        <label class="inline-flex items-center gap-2">

                                            <input type="checkbox"
                                                name="permissions[{{ $menu->id }}][download]"
                                                class="rounded text-blue-500">

                                            Aktif

                                        </label>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 mt-12">

                    <button type="button" onclick="confirmCancel('{{ route('admin.roles.index') }}')"
                    class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Batal
                    </button>

                    <button type="button" onclick="confirmSubmit('createRoleForm')"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                        Simpan

                    </button>

                </div>

            </form>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</x-app-layout>