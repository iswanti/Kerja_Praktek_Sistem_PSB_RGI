<x-app-layout>
    <x-slot name="pageTitle">Pretest</x-slot>

    <div x-data="{
        current: 0,
        total: {{ $soals->count() }},
        jawaban: {},
        next() { if (this.current < this.total - 1) this.current++ },
        prev() { if (this.current > 0) this.current-- },
        goTo(i) { this.current = i },
        sudahJawab(i) { return this.jawaban[i] !== undefined && this.jawaban[i] !== '' }
    }">

        {{-- Top Bar --}}
        <div class="mb-4 flex items-center justify-between rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('seleksi.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                    Kembali
                </a>

                <span class="text-sm font-semibold text-gray-700">
                    Pretest
                </span>
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 px-5 py-2">
                <span class="text-sm font-medium text-gray-700">Waktu Tersisa :</span>
                <span id="timer" class="text-sm font-bold text-gray-900">30:00</span>
            </div>
        </div>

        {{-- Judul --}}
        <div class="mb-4 rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-base font-bold text-gray-900">
                    Pretest : {{ $pendaftaran->jurusan->nama_jurusan }}
                </p>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">
                        <i data-lucide="globe" class="w-3 h-3"></i>
                        {{ $soalUmum->count() }} Soal Umum
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                        <i data-lucide="book-open" class="w-3 h-3"></i>
                        {{ $soalKejuruan->count() }} Soal Kejuruan
                    </span>
                </div>
            </div>
        </div>

        <form id="pretestForm" action="{{ route('seleksi.submit') }}" method="POST" id="pretestForm">
            @csrf

            {{-- Hidden input jawaban --}}
            @foreach($soals as $soal)
                <input type="hidden" name="soal_{{ $soal->id }}" id="hidden_{{ $soal->id }}" value="">
            @endforeach

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_240px]">

                {{-- KIRI: Soal --}}
                <div>
                    @foreach($soals as $index => $soal)
                        <div x-show="current === {{ $index }}"
                            x-cloak
                            class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                            {{-- Badge tipe soal --}}
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-bold text-gray-900">
                                    Pertanyaan {{ $index + 1 }}
                                </p>

                                @if($soal->tipe_soal === 'umum')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                        <i data-lucide="globe" class="w-3 h-3"></i>
                                        Umum
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        <i data-lucide="book-open" class="w-3 h-3"></i>
                                        Kejuruan
                                    </span>
                                @endif
                            </div>

                            <p class="mb-5 text-base leading-relaxed text-gray-700">
                                {{ $soal->pertanyaan }}
                            </p>

                            @if($soal->tipe === 'pilihan_ganda')
                                @php
                                    $pilihanAcak = collect([
                                        ['key' => 'A', 'text' => $soal->pilihan_a],
                                        ['key' => 'B', 'text' => $soal->pilihan_b],
                                        ['key' => 'C', 'text' => $soal->pilihan_c],
                                        ['key' => 'D', 'text' => $soal->pilihan_d],
                                        ['key' => 'E', 'text' => $soal->pilihan_e],
                                    ])
                                    ->filter(fn($item) => !empty($item['text']))
                                    ->shuffle();
                                @endphp

                                <div class="space-y-3">
                                    @foreach($pilihanAcak as $pilihan)
                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 transition"
                                            :class="jawaban[{{ $index }}] === '{{ $pilihan['key'] }}'
                                                ? 'border-blue-500 bg-blue-50'
                                                : 'border-gray-200 bg-white hover:bg-gray-50'">

                                            <input type="radio"
                                                name="pg_{{ $soal->id }}"
                                                value="{{ $pilihan['key'] }}"
                                                x-on:change="jawaban[{{ $index }}] = '{{ $pilihan['key'] }}'; document.getElementById('hidden_{{ $soal->id }}').value = '{{ $pilihan['key'] }}'"
                                                :checked="jawaban[{{ $index }}] === '{{ $pilihan['key'] }}'"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">

                                            <span class="text-sm text-gray-700">
                                                {{ $pilihan['text'] }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <textarea
                                    x-on:input="jawaban[{{ $index }}] = $event.target.value; document.getElementById('hidden_{{ $soal->id }}').value = $event.target.value"
                                    rows="5"
                                    placeholder="Tulis jawaban Anda di sini..."
                                    class="w-full rounded-xl border-gray-300 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @endif

                            {{-- Navigasi --}}
                            <div class="mt-6 flex items-center justify-between">
                                <button
                                    type="button"
                                    x-on:click="prev()"
                                    :disabled="current === 0"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                    Sebelumnya
                                </button>

                                <button
                                    type="button"
                                    x-on:click="next()"
                                    :disabled="current === total - 1"
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-40">
                                    Selanjutnya
                                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- KANAN --}}
                <div class="space-y-4">

                    {{-- Nomor Soal --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="mb-4 text-sm font-bold text-gray-900">
                            Pertanyaan <span x-text="current + 1"></span>/{{ $soals->count() }}
                        </p>

                        <div class="grid grid-cols-5 gap-2">
                            @foreach($soals as $index => $soal)

                                {{-- Pemisah setelah soal umum --}}
                                @if($index === $soalUmum->count())
                                    <div class="col-span-5 border-t border-gray-200 pt-2 mb-1">
                                        <p class="text-xs text-gray-400 font-medium">Soal Kejuruan</p>
                                    </div>
                                @endif

                                @if($index === 0)
                                    <div class="col-span-5 mb-1">
                                        <p class="text-xs text-gray-400 font-medium">Soal Umum</p>
                                    </div>
                                @endif

                                <button type="button"
                                        x-on:click="goTo({{ $index }})"
                                        class="flex h-9 w-9 items-center justify-center rounded-full border text-xs font-semibold transition"
                                        :class="current === {{ $index }}
                                            ? 'border-blue-600 bg-blue-600 text-white'
                                            : sudahJawab({{ $index }})
                                                ? 'border-blue-300 bg-blue-100 text-blue-700'
                                                : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50'">
                                    {{ $index + 1 }}
                                </button>

                            @endforeach
                        </div>

                        {{-- Legenda --}}
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="h-3.5 w-3.5 rounded-full bg-blue-600"></div>
                                <span class="text-xs text-gray-500">Sedang dikerjakan</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="h-3.5 w-3.5 rounded-full border border-blue-300 bg-blue-100"></div>
                                <span class="text-xs text-gray-500">Sudah dijawab</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="h-3.5 w-3.5 rounded-full border border-gray-200 bg-white"></div>
                                <span class="text-xs text-gray-500">Belum dijawab</span>
                            </div>
                        </div>
                    </div>

                    {{-- Simpan --}}
                    <button type="button"
                            onclick="confirmSubmitPretest()"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-4 text-sm font-bold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Jawaban
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Timer --}}
    <script>
        let totalSeconds = {{ $durasiPretest }} * 60;
        const timerEl = document.getElementById('timer');

        const interval = setInterval(() => {
            totalSeconds--;

            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = (totalSeconds % 60).toString().padStart(2, '0');

            timerEl.textContent = `${m}:${s}`;

            if (totalSeconds <= 300) {
                timerEl.classList.remove('text-gray-900');
                timerEl.classList.add('text-red-600');
            }

            if (totalSeconds <= 0) {
                clearInterval(interval);
                document.getElementById('pretestForm').submit();
            }
        }, 1000);
    </script>
</x-app-layout>