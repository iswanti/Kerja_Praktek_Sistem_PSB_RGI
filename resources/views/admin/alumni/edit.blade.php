<x-app-layout>
    <div class="w-full py-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                    <i data-lucide="user-pen" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Data Alumni</h1>
                    <p class="text-gray-600 mt-1">Perbarui data alumni</p>
                </div>
            </div>

            <form form id="editAlumniForm"  action="{{ route('admin.alumni.update', $alumni->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama', $alumni->nama) }}"
                           class="w-full rounded-xl border-gray-200 text-sm py-3">

                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jurusan <span class="text-red-500">*</span>
                    </label>
                    <select name="jurusan_id"
                            class="w-full rounded-xl border-gray-200 text-sm py-3">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}"
                                {{ old('jurusan_id', $alumni->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    @error('jurusan_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Angkatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="angkatan"
                           value="{{ old('angkatan', $alumni->angkatan) }}"
                           class="w-full rounded-xl border-gray-200 text-sm py-3">

                    @error('angkatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                   
                    <button type="button" onclick="confirmCancel('{{ route('admin.alumni.index') }}')"
                        class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold">
                            Batal
                    </button>

                    <button type="button" onclick="confirmSubmit('editAlumniForm', 'Yakin ingin memperbarui data?' )"
                            class="px-5 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>