<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white p-8 rounded-lg shadow">

            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="calendar-plus" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Gelombang</h1>
                    <p class="text-gray-600 mt-1">Tambahkan jadwal gelombang PSB baru</p>
                </div>
            </div>

            <form id="gelombangCreateForm"
                  action="{{ route('admin.gelombang.store') }}"
                  method="POST"
                  class="border border-gray-200 rounded-2xl p-8">
                @csrf

                <div class="grid grid-cols-2 gap-x-8 gap-y-5">

                    <div>
                        <label class="block text-sm mb-2">Nama Gelombang</label>
                        <input type="text" name="nama_gelombang" value="{{ old('nama_gelombang') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500"
                               placeholder="Contoh: Gelombang 1">
                        @error('nama_gelombang') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Tahun Periode</label>
                        <input type="number" name="tahun_periode" value="{{ old('tahun_periode', date('Y')) }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500"
                               placeholder="Contoh: 2026">
                        @error('tahun_periode') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Pendaftaran Mulai</label>
                        <input type="datetime-local" name="pendaftaran_mulai" value="{{ old('pendaftaran_mulai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Pendaftaran Selesai</label>
                        <input type="datetime-local" name="pendaftaran_selesai" value="{{ old('pendaftaran_selesai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Pretest Mulai</label>
                        <input type="datetime-local" name="pretest_mulai" value="{{ old('pretest_mulai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Pretest Selesai</label>
                        <input type="datetime-local" name="pretest_selesai" value="{{ old('pretest_selesai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Durasi Pretest</label>
                        <input type="number" name="durasi_pretest" value="{{ old('durasi_pretest') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500"
                               placeholder="Dalam menit, contoh: 90">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Pengumuman Mulai</label>
                        <input type="datetime-local" name="pengumuman_mulai" value="{{ old('pengumuman_mulai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Wawancara Mulai</label>
                        <input type="datetime-local" name="wawancara_mulai" value="{{ old('wawancara_mulai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Wawancara Selesai</label>
                        <input type="datetime-local" name="wawancara_selesai" value="{{ old('wawancara_selesai') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm mb-3">Status</label>
                        <label class="inline-flex items-center gap-3">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                            <span>Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-12">
                    <button type="button"
                            onclick="confirmCancel('{{ route('admin.gelombang.index') }}')"
                            class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Batal
                    </button>

                    <button type="button"
                            onclick="confirmSubmit('gelombangCreateForm', 'Yakin ingin menyimpan gelombang?')"
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