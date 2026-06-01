<x-app-layout>
    <div class="w-full mx-auto py-5 ">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            {{-- Header --}}
            <div class="flex items-center gap-6 pb-10 border-b">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Edit Kewenangan
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Kelola hak akses role pengguna
                    </p>
                </div>

            </div>

            {{-- FORM --}}
            <form id="editRoleForm" action="{{ route('admin.roles.update', $role->id) }}" method="POST" class=" pt-8">

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-6">
                    <label class="block text-sm mb-2">
                        Nama Kewenangan / role
                    </label>

                    <input type="text" name="nama" value="{{ old('nama', $role->nama) }}" class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">

                </div>

                {{-- Deskripsi --}}
                <div class="mb-6">

                    <label class="block text-sm mb-2">
                        Deskripsi Kewenangan / role
                    </label>

                    <input type="text" name="deskripsi" value="{{ old('deskripsi', $role->deskripsi) }}" class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Is Active --}}
                <div class="mb-10">
                    <label class="block text-sm mb-3">Is Active ?</label>

                    <label class="inline-flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">

                        <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $role->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                        <span>Aktif</span>
                    </label>
                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 mt-12">

                    <button type="button" onclick="confirmCancel('{{ route('admin.roles.index') }}')"
                        class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Batal
                    </button>

                    <button type="button" onclick="confirmSubmit('editRoleForm', 'Yakin ingin memperbarui data?' )"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                        Update
                    </button>

                </div>

            </form>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</x-app-layout>