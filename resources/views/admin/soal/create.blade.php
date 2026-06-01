<x-app-layout>
    <x-slot name="pageTitle">Buat Pertanyaan Baru</x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

        {{-- Header --}}
        <div class="flex items-start gap-4 border-b border-gray-200 pb-6 mb-6">
            <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                <i data-lucide="edit-3" class="w-6 h-6"></i>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Buat Pertanyaan Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Buat Pertanyaan seleksi beserta pilihan jawaban dan nilai benar
                </p>
            </div>
        </div>

        <form action="{{ route('admin.soal.store') }}"
            method="POST"
            x-data="{
                tipe: @js(old('tipe', 'pilihan_ganda')),
                tipesoal: @js(old('tipe_soal', 'kejuruan')),
                opsi: [
                    { teks: @js(old('pilihan_a')), nilai: @js(old('nilai_a', 0)) },
                    { teks: @js(old('pilihan_b')), nilai: @js(old('nilai_b', 0)) },
                    { teks: @js(old('pilihan_c')), nilai: @js(old('nilai_c', 0)) },
                    { teks: @js(old('pilihan_d')), nilai: @js(old('nilai_d', 0)) }
                ],
                benar: @js(old('jawaban_benar')),

                init() {
                    this.$nextTick(() => lucide.createIcons());
                },

                tambahOpsi() {
                    if (this.opsi.length >= 5) { alert('Maksimal 5 opsi jawaban.'); return; }
                    this.opsi.push({ teks: '', nilai: 0 });
                    this.$nextTick(() => lucide.createIcons());
                },

                hapusOpsi(index) {
                    if (this.opsi.length <= 2) { alert('Minimal harus ada 2 opsi jawaban.'); return; }
                    this.opsi.splice(index, 1);
                    this.$nextTick(() => lucide.createIcons());
                },

                huruf(index) {
                    return String.fromCharCode(65 + index);
                }
            }">
            @csrf

            {{-- Tipe Soal & Jurusan — hapus x-data di div ini --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Soal *</label>
                    <select name="tipe_soal"
                            x-model="tipesoal"
                            required
                            class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                        <option value="kejuruan">Kejuruan</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>

                <div x-show="tipesoal === 'kejuruan'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan *</label>
                    <select name="jurusan_id"
                            :required="tipesoal === 'kejuruan'"
                            class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusans as $j)
                            <option value="{{ $j->id }}"
                                    {{ old('jurusan_id', $jurusanId ?? null) == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Soal kejuruan hanya tampil untuk jurusan yang dipilih.</p>
                </div>

                <div x-show="tipesoal === 'umum'" x-transition x-cloak>
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 h-full">
                        <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0"></i>
                        <p class="text-sm text-gray-600">Soal umum akan tampil untuk semua jurusan.</p>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Soal (Bobot)*</label>
                    <input type="number" name="bobot" value="{{ old('bobot', 0) }}" min="0" max="100" required
                        class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pertanyaan *</label>
                <input type="text" name="pertanyaan" value="{{ old('pertanyaan') }}" required
                    placeholder="Contoh: Apa kepanjangan dari HTML?"
                    class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pertanyaan (Opsional)</label>
                <textarea name="deskripsi" rows="5"
                        class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Jawaban *</label>
                    <select name="tipe" x-model="tipe" required
                            class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                        <option value="pilihan_ganda">Pilihan Ganda (Satu Jawaban)</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                <div x-show="tipe === 'pilihan_ganda'"
                    class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                    <p class="text-sm text-gray-600">Pastikan hanya satu opsi yang dipilih sebagai jawaban benar.</p>
                </div>
            </div>

            {{-- Pilihan Ganda --}}
            <div x-show="tipe === 'pilihan_ganda'" class="mb-8">
                <h3 class="text-sm font-bold text-gray-800">Daftar Opsi Jawaban</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Tambahkan opsi jawaban, lalu pilih jawaban yang benar.
                </p>

                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left w-1/2">Opsi Jawaban</th>
                                <th class="px-4 py-3 text-center">Jawaban Benar</th>
                                <th class="px-4 py-3 text-center">Nilai</th>
                                <th class="px-4 py-3 text-center w-16">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(item, index) in opsi" :key="index">
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="cursor-move text-gray-400">
                                                <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                            </span>

                                            <input type="text"
                                                :name="'pilihan_' + huruf(index).toLowerCase()"
                                                x-model="item.teks"
                                                :required="tipe === 'pilihan_ganda' && index < 2"
                                                class="w-full rounded-lg border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="Masukkan opsi jawaban">
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <input type="radio"
                                            name="jawaban_benar"
                                            :value="huruf(index)"
                                            x-model="benar"
                                            :required="tipe === 'pilihan_ganda'"
                                            class="text-blue-600 focus:ring-blue-500">
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <input type="number"
                                            :name="'nilai_' + huruf(index).toLowerCase()"
                                            x-model="item.nilai"
                                            min="0"
                                            max="100"
                                            class="w-28 rounded-lg border-gray-300 text-sm text-gray-700 text-center focus:border-blue-500 focus:ring-blue-500">
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <button type="button"
                                                @click="hapusOpsi(index)"
                                                class="text-red-500 hover:text-red-700">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button"
                        @click="tambahOpsi()"
                        class="mt-4 inline-flex items-center gap-2 border border-blue-500 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl text-sm font-semibold">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Opsi Jawaban
                </button>
            </div>

            {{-- Essay --}}
            <div x-show="tipe === 'essay'" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Jawaban Acuan (Kunci Jawaban)</h3>
                        <p class="text-sm text-gray-500">Jawaban ini digunakan sebagai acuan dalam penilaian.</p>
                    </div>

                    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                        <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                        <p class="text-sm text-gray-600">
                            Berikan jawaban acuan agar penilaian essay lebih objektif.
                        </p>
                    </div>
                </div>

                <textarea name="jawaban_essay"
                        rows="7"
                        :required="tipe === 'essay'"
                        placeholder="Tulis jawaban acuan essay..."
                        class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">{{ old('jawaban_essay') }}</textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.soal.index') }}"
                class="px-8 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold text-sm">
                    Batal
                </a>

                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm">
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>