<x-app-layout>
    <div class="min-h-screen bg-white p-8">

        {{-- Header --}}
        <div class="flex justify-between items-start mb-10">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Ubah Kewenangan Hak Akses Menu
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Atur hak akses setiap menu untuk role
                        <span class="text-blue-500 font-semibold">{{ $role->nama }}</span>
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                
                <button type="button" onclick="confirmCancel('{{ route('admin.roles.index') }}')"
                    class="px-8 py-3 rounded-lg border border-gray-400 text-gray-600 hover:bg-gray-100">
                        Batal
                </button>

                <button type="button" onclick="confirmSubmit('permissionForm', 'Yakin ingin memperbarui hak akses?' )"
                    class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                    Simpan
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form id="permissionForm"
              action="{{ route('admin.roles.permission.update', $role->id) }}"
              method="POST"
              class="border border-gray-200 rounded-2xl p-6">

            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="px-2 py-4 font-semibold">Nama Menu</th>
                            <th class="px-2 py-4 font-semibold text-center">Read</th>
                            <th class="px-2 py-4 font-semibold text-center">Create</th>
                            <th class="px-2 py-4 font-semibold text-center">Update</th>
                            <th class="px-2 py-4 font-semibold text-center">Delete</th>
                            <th class="px-2 py-4 font-semibold text-center">Download</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($menus as $menu)
                            @php
                                $permission = $role->permissions
                                    ->where('menu_id', $menu->id)
                                    ->first();
                            @endphp

                            <tr>
                                <td class="px-2 py-5">
                                    {{ $menu->nama }}
                                </td>

                                @foreach ([
                                    'read' => 'can_read',
                                    'create' => 'can_create',
                                    'update' => 'can_update',
                                    'delete' => 'can_delete',
                                    'download' => 'can_download',
                                ] as $key => $field)
                                    <td class="px-2 py-5 text-center">
                                        <label class="inline-flex items-center gap-3">
                                            <input type="checkbox"
                                                   name="permissions[{{ $menu->id }}][{{ $key }}]"
                                                   class="rounded border-gray-300 text-blue-500 focus:ring-blue-400"
                                                   {{ $permission?->$field ? 'checked' : '' }}>
                                            <span>Aktif</span>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <p class="mt-10 text-sm text-gray-700">
            Role yang sedang diatur :
            <span class="text-blue-500">{{ $role->nama }}</span>
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>