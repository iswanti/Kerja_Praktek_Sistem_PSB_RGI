<x-app-layout>
    <x-slot name="pageTitle">Edit Soal Pretest</x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

        {{-- Header --}}
        <div class="flex items-start gap-4 border-b border-gray-200 pb-6 mb-6">
            <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                <i data-lucide="edit-3" class="w-6 h-6"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">Edit Soal Pretest</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah pertanyaan, pilihan jawaban, dan bobot nilai soal.</p>
            </div>
            <a href="{{ route('admin.soal.index', ['jurusan_id' => $soal->jurusan_id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.soal.update', $soal) }}" method="POST"
            x-data="{
                tipe: @js(old('tipe', $soal->tipe)),
                tipesoal: @js(old('tipe_soal', $soal->tipe_soal ?? 'kejuruan')),
                opsi: [
                        { teks: @js(old('pilihan_a', $soal->pilihan_a)) },
                        { teks: @js(old('pilihan_b', $soal->pilihan_b)) },
                        { teks: @js(old('pilihan_c', $soal->pilihan_c)) },
                        { teks: @js(old('pilihan_d', $soal->pilihan_d)) },
                    ],
                    bobot: @js(old('bobot', $soal->bobot ?? 0)),
                    benar: @js(old('jawaban_benar', $soal->jawaban_benar)),

                    nilaiOpsi(index) {
                        return this.huruf(index) === this.benar ? this.bobot : 0;
                    },
                benar: @js(old('jawaban_benar', $soal->jawaban_benar)),

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
            @method('PUT')

            {{-- Tipe Soal & Jurusan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Soal *</label>
                    <select name="tipe_soal" x-model="tipesoal" required
                            class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                        <option value="kejuruan">Kejuruan</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>

                <div x-show="tipesoal === 'kejuruan'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan *</label>
                    <div class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
                        {{ $soal->jurusan->nama_jurusan ?? '-' }}
                    </div>
                    <input type="hidden" name="jurusan_id" value="{{ $soal->jurusan_id }}">
                    <p class="text-xs text-gray-400 mt-1">Jurusan tidak dapat diubah.</p>
                </div>

                <div x-show="tipesoal === 'umum'" x-transition x-cloak>
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 h-full">
                        <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0"></i>
                        <p class="text-sm text-gray-600">Soal umum akan tampil untuk semua jurusan.</p>
                    </div>
                </div>
            </div>

            {{-- Bobot --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Soal (Bobot) *</label>
                    <input type="number" name="bobot" x-model="bobot"
                        value="{{ old('bobot', $soal->bobot) }}" min="0" max="100" required
                        class="w-full rounded-xl border-gray-300 text-sm text-gray-700">
                </div>
            </div>

            {{-- Pertanyaan --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pertanyaan *</label>
                <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $soal->pertanyaan) }}" required
                       placeholder="Contoh: Apa kepanjangan dari HTML?"
                       class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Deskripsi --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pertanyaan (Opsional)</label>
                <textarea name="deskripsi" rows="5"
                          class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $soal->deskripsi) }}</textarea>
            </div>

            {{-- Tipe Jawaban --}}
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
                <p class="text-sm text-gray-500 mb-4">Tambahkan opsi jawaban, lalu pilih jawaban yang benar.</p>

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
                                        <span class="inline-flex items-center justify-center w-16 h-9 rounded-lg text-sm font-bold"
                                            :class="huruf(index) === benar 
                                                ? 'bg-green-100 text-green-700' 
                                                : 'bg-gray-100 text-gray-400'">
                                            <span x-text="nilaiOpsi(index)"></span>
                                        </span>
                                        {{-- hidden input untuk dikirim ke server jika perlu --}}
                                        <input type="hidden"
                                            :name="'nilai_' + huruf(index).toLowerCase()"
                                            :value="nilaiOpsi(index)">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="hapusOpsi(index)"
                                                class="text-red-500 hover:text-red-700">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button" @click="tambahOpsi()"
                        class="mt-4 inline-flex items-center gap-2 border border-blue-500 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl text-sm font-semibold">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Opsi Jawaban
                </button>
            </div>

            {{-- Essay --}}
            <div x-show="tipe === 'essay'" x-cloak class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Jawaban Acuan (Kunci Jawaban)</h3>
                        <p class="text-sm text-gray-500">Jawaban ini digunakan sebagai acuan dalam penilaian.</p>
                    </div>
                    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                        <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                        <p class="text-sm text-gray-600">Berikan jawaban acuan agar penilaian essay lebih objektif.</p>
                    </div>
                </div>
                <textarea name="jawaban_benar" rows="7"
                          :required="tipe === 'essay'"
                          placeholder="Tulis jawaban acuan essay..."
                          class="w-full rounded-xl border-gray-300 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">{{ old('jawaban_benar', $soal->jawaban_benar) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.soal.index', ['jurusan_id' => $soal->jurusan_id]) }}"
                   class="px-8 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition">
                    Update Soal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>