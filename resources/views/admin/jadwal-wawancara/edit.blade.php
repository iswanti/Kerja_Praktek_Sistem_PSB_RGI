<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white p-8 rounded-lg shadow">

            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="video" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Jadwal Wawancara</h1>
                    <p class="text-gray-600 mt-1">Perbarui link dan jadwal wawancara</p>
                </div>
            </div>
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold mb-2">Data gagal diperbarui:</p>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="jadwalEditForm"
                action="{{ route('admin.jadwal-wawancara.update', $jadwal->id) }}"
                method="POST"
                class="border border-gray-200 rounded-2xl p-8">
                @csrf
                @method('PUT')

                <div x-data="kampusJurusan()"
                    x-init='initData(
                        @json($cabangs),
                        @json((string) old("cabang_id", $jadwal->cabang_id)),
                        @json((string) old("jurusan_id", $jadwal->jurusan_id))
                    )'>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Gelombang --}}
                        <div>
                            <label class="block text-sm mb-2">Gelombang</label>
                            <select name="gelombang_id" class="w-full rounded-xl border-gray-300 px-4 py-3">
                                <option value="">Pilih Gelombang</option>
                                @foreach ($gelombangs as $gelombang)
                                    <option value="{{ $gelombang->id }}"
                                        {{ old('gelombang_id', $jadwal->gelombang_id) == $gelombang->id ? 'selected' : '' }}>
                                        {{ $gelombang->nama_gelombang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cabang --}}
                        <div>
                            <label class="block text-sm mb-2">Cabang</label>
                            <select x-model="kampus"
                                    @change="updateJurusan()"
                                    name="cabang_id"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3">
                                <option value="">Pilih Kampus</option>
                                <template x-for="c in cabangs" :key="c.id">
                                    <option :value="String(c.id)" x-text="c.nama_cabang"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Jurusan --}}
                        <div>
                            <label class="block text-sm mb-2">Jurusan</label>
                            <select name="jurusan_id"
                                    x-model="jurusan"
                                    :disabled="!kampus"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3 disabled:bg-gray-100 disabled:text-gray-400">
                                <option value="">Pilih Jurusan</option>
                                <template x-for="j in jurusanList" :key="j.id">
                                    <option :value="String(j.id)" x-text="j.nama_jurusan"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Unsur --}}
                        <div>
                            <label class="block text-sm mb-2">Unsur Wawancara</label>
                            <select name="unsur" class="w-full rounded-xl border-gray-300 px-4 py-3">
                                <option value="">Pilih Unsur</option>
                                @foreach ($unsurs as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('unsur', $jadwal->unsur) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Waktu Mulai --}}
                        <div>
                            <label class="block text-sm mb-2">Waktu Mulai</label>
                            <input type="datetime-local"
                                name="waktu_mulai"
                                value="{{ old('waktu_mulai', $jadwal->waktu_mulai?->format('Y-m-d\TH:i')) }}"
                                class="w-full rounded-xl border-gray-300 px-4 py-3">
                        </div>

                        {{-- Waktu Selesai --}}
                        <div>
                            <label class="block text-sm mb-2">Waktu Selesai</label>
                            <input type="datetime-local"
                                name="waktu_selesai"
                                value="{{ old('waktu_selesai', $jadwal->waktu_selesai?->format('Y-m-d\TH:i')) }}"
                                class="w-full rounded-xl border-gray-300 px-4 py-3">
                        </div>

                        {{-- Link --}}
                        <div>
                            <label class="block text-sm mb-2">Link Wawancara</label>
                            <input type="url"
                                name="link_wawancara"
                                value="{{ old('link_wawancara', $jadwal->link_wawancara) }}"
                                placeholder="https://meet.google.com/..."
                                class="w-full rounded-xl border-gray-300 px-4 py-3">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm mb-3">Status</label>
                            <label class="inline-flex items-center gap-3">
                                <input type="checkbox"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', $jadwal->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                                <span>Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-12">
                    <button type="button"
                            onclick="confirmCancel('{{ route('admin.jadwal-wawancara.index') }}')"
                            class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Batal
                    </button>

                    <button type="button"
                            onclick="confirmSubmit('jadwalEditForm', 'Yakin ingin memperbarui jadwal wawancara?')"
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