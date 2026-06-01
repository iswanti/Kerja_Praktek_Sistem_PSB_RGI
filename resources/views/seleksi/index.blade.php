<x-app-layout>
    <x-slot name="pageTitle">Seleksi</x-slot>

    {{-- Banner Info --}}
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-500">
            <i data-lucide="info" class="h-4 w-4 text-white"></i>
        </div>
        <p class="text-sm text-blue-700">
            Ikuti setiap tahapan seleksi sesuai jadwal yang telah ditentukan. Pastikan Anda menyiapkan diri dengan baik.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- KIRI --}}
        <div class="space-y-4 lg:col-span-2">

            {{-- Tahapan Seleksi --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                <h3 class="mb-5 text-sm font-bold text-gray-800">Tahapan Seleksi</h3>

                @php
                    $pendaftaranAktif = $pendaftaran !== null;

                    $pretestAktif = $pendaftaran && (
                        $sudahMengerjakan ||
                        in_array($pendaftaran->status, [
                            'seleksi_pretest',
                            'wawancara',
                            'verifikasi_kelulusan_siswa',
                            'diterima',
                            'ditolak',
                            'cadangan',
                        ])
                    );

                    $wawancaraAktif = $pendaftaran && in_array($pendaftaran->status, [
                        'wawancara',
                        'verifikasi_kelulusan_siswa',
                        'diterima',
                        'ditolak',
                        'cadangan',
                    ]);

                    $pengumumanAktif = $pendaftaran && in_array($pendaftaran->status, [
                        'diterima',
                        'ditolak',
                        'cadangan',
                    ]);
                @endphp

                {{-- Step 1 --}}
                <div class="mb-5 flex items-center gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        {{ $pendaftaranAktif ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}
                        text-sm font-bold">
                        1
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $pendaftaranAktif ? 'text-gray-800' : 'text-gray-400' }}">
                            Pendaftaran
                        </p>

                        <p class="text-xs {{ $pendaftaranAktif ? 'text-gray-500' : 'text-gray-400' }}">
                            {{ $pendaftaranAktif ? 'Pendaftaran berhasil' : 'Belum melakukan pendaftaran' }}
                        </p>
                    </div>

                    @if($pendaftaranAktif)
                        <div class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                            Selesai
                            <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>
                        </div>
                    @else
                        <span class="text-xs text-gray-400">Belum Dimulai</span>
                    @endif
                </div>

                {{-- Step 2 --}}
                <div class="mb-5 flex items-center gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        {{ $pretestAktif ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}
                        text-sm font-bold">
                        2
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $pretestAktif ? 'text-gray-800' : 'text-gray-400' }}">
                            Pretest
                        </p>
                        <p class="text-xs text-gray-400">
                            Kerjakan Pretest Sesuai Jadwal
                        </p>
                    </div>

                    @if($pendaftaran && $sudahMengerjakan)
                        <div class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                            Selesai
                            <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>
                        </div>
                    @elseif($pendaftaran && $pendaftaran->status == 'seleksi_pretest')
                        <div class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                            Aktif
                        </div>
                    @else
                        <span class="text-xs text-gray-400">Belum Dimulai</span>
                    @endif
                </div>

                {{-- Step 3 --}}
                <div class="mb-5 flex items-center gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        {{ $wawancaraAktif ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}
                        text-sm font-bold">
                        3
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $wawancaraAktif ? 'text-gray-800' : 'text-gray-400' }}">
                            Wawancara
                        </p>

                        <p class="text-xs text-gray-400">
                            Menunggu jadwal wawancara
                        </p>
                    </div>

                    @if($pendaftaran && $pendaftaran->status == 'wawancara')
                        <div class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                            Aktif
                        </div>
                    @elseif($pendaftaran && in_array($pendaftaran->status, [
                        'verifikasi_kelulusan_siswa',
                        'diterima',
                        'ditolak',
                        'cadangan'
                    ]))
                        <div class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                            Selesai
                            <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>
                        </div>
                    @else
                        <span class="text-xs text-gray-400">Belum Dimulai</span>
                    @endif
                </div>

                {{-- Step 4 --}}
                <div class="flex items-center gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        {{ $pengumumanAktif ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}
                        text-sm font-bold">
                        4
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $pengumumanAktif ? 'text-gray-800' : 'text-gray-400' }}">
                            Pengumuman
                        </p>

                        <p class="text-xs text-gray-400">
                            Menunggu pengumuman hasil seleksi
                        </p>
                    </div>

                    @if($pendaftaran && $pendaftaran->status == 'diterima')
                        <div class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                            Lulus
                        </div>
                    @elseif($pendaftaran && $pendaftaran->status == 'ditolak')
                        <div class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">
                            Tidak Lulus
                        </div>
                    @elseif($pendaftaran && $pendaftaran->status == 'cadangan')
                        <div class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                            Cadangan
                        </div>
                    @else
                        <span class="text-xs text-gray-400">Belum Dimulai</span>
                    @endif
                </div>
            </div>

            {{-- Petunjuk Pretest --}}
            <div class="rounded-2xl bg-blue-50 p-5 shadow-sm border border-blue-100">
                <p class="mb-3 text-sm font-bold text-blue-700">Petunjuk Pretest</p>

                <div class="space-y-2">
                    @foreach([
                        'Pastikan Anda berada di lingkungan yang nyaman dan tidak terganggu.',
                        'Waktu akan berjalan setelah Anda menekan tombol "Kerjakan Pretest".',
                        'Pastikan setiap jawaban sudah terisi sebelum menekan tombol selesai.',
                        'Pretest hanya dapat diikuti satu kali, pastikan Anda siap sebelum memulai.'
                    ] as $idx => $petunjuk)
                        <div class="flex items-start gap-2">
                            <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-500 text-[10px] font-bold text-white">
                                {{ $idx + 1 }}
                            </div>
                            <p class="text-xs leading-relaxed text-blue-800">
                                {{ $petunjuk }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        

            {{-- Butuh Bantuan --}}
            <div class="flex items-center justify-between gap-4 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-5 text-white shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20">
                        <i data-lucide="headphones" class="h-8 w-8 text-white"></i>
                    </div>

                    <div>
                        <p class="text-sm font-bold">Butuh Bantuan?</p>
                        <p class="mt-1 text-xs text-blue-100">
                            Jika Anda mengalami kendala silakan hubungi panitia.
                        </p>
                    </div>
                </div>

                {{-- Kanan: Button --}}
                <a href="https://wa.me/6289512205187?text=Assalamu'alaikum%20Panitia%20PSB%20RGI%20saya%20ingin%20bertanya%20terkait%20pretest." target="_blank" rel="noopener noreferrer"
                class="mt-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">
                    <i data-lucide="phone" class="h-3.5 w-3.5"></i>
                        Hubungi Panitia
                </a>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="space-y-4">

            @if($pendaftaran)
                {{-- Info Pretest --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                                <i data-lucide="file-text" class="h-5 w-5 text-blue-600"></i>
                            </div>

                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    Pretest {{ $pendaftaran->jurusan->nama_jurusan }}
                                </p>
                                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                                    Pretest bertujuan untuk mengukur kemampuan dasar calon santri pada bidang {{ $pendaftaran->jurusan->nama_jurusan }}.
                                </p>
                            </div>
                        </div>

                        @if($sudahMengerjakan)
                            <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-[10px] font-semibold text-green-600">
                                Sudah Dikerjakan
                            </span>
                        @else
                            <span class="shrink-0 rounded-full bg-yellow-100 px-3 py-1 text-[10px] font-semibold text-yellow-600">
                                Belum Dikerjakan
                            </span>
                        @endif
                    </div>

                    @php
                        $details = [
                            ['icon'=>'clock-3','label'=>'Durasi','value'=>($gelombang->durasi_pretest ?? 0) . ' Menit'],
                            ['icon'=>'file','label'=>'Jumlah Soal','value'=>$jumlahSoal . ' Soal'],
                            ['icon'=>'list','label'=>'Bentuk Soal','value'=>'Pilihan Ganda'],
                            ['icon'=>'calendar','label'=>'Tanggal Mulai', 'value'=>$gelombang ? \Carbon\Carbon::parse($gelombang->pretest_mulai)->translatedFormat('d F Y') : '-' ],
                            ['icon'=>'timer','label'=>'Waktu Pengerjaan', 'value'=>$gelombang ? \Carbon\Carbon::parse($gelombang->pretest_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($gelombang->pretest_selesai)->format('H:i') . ' WIB' : '-' ],
                            ['icon'=>'star','label'=>'Nilai Pretest','value'=>$sudahMengerjakan ? number_format($pendaftaran->nilai_pretest ?? 0, 2) : '-'],
                        ];
                    @endphp

                    <div class="space-y-3 border-t border-gray-100 pt-4">
                        @foreach($details as $d)
                            <div class="flex items-center gap-3 text-xs">
                                <i data-lucide="{{ $d['icon'] }}" class="h-4 w-4 text-gray-400"></i>
                                <span class="w-28 text-gray-500">{{ $d['label'] }}</span>
                                <span class="font-medium text-gray-800">: {{ $d['value'] }}</span>
                            </div>
                        @endforeach

                        <div class="flex items-center gap-3 text-xs">
                            <i data-lucide="info" class="h-4 w-4 text-gray-400"></i>
                            <span class="w-28 text-gray-500">Status</span>
                            @if($sudahMengerjakan)
                                <span class="font-semibold text-green-600">: Sudah Dikerjakan</span>
                            @else
                                <span class="font-semibold text-yellow-600">: Belum Dikerjakan</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tombol Kerjakan --}}
                @if($pretestAktif && !$sudahMengerjakan)
                    <a href="{{ route('seleksi.pretest') }}"
                    class="flex items-center justify-center gap-3 rounded-xl bg-blue-600 px-5 py-4 text-sm font-bold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700">
                        <i data-lucide="play" class="h-4 w-4 fill-white"></i>
                        Kerjakan Pretest
                    </a>

                @elseif(!$pretestAktif && !$sudahMengerjakan)
                    <div class="flex items-center justify-center gap-3 rounded-xl bg-yellow-100 px-5 py-4 text-sm font-bold text-yellow-700">
                        <i data-lucide="clock-3" class="h-5 w-5"></i>
                        Pretest Belum Dibuka
                    </div>
                @else
                    <div class="flex items-center justify-center gap-3 rounded-xl bg-green-100 px-5 py-4 text-sm font-bold text-green-700">
                        <i data-lucide="check-circle" class="h-5 w-5"></i>
                        Pretest Sudah Dikerjakan
                    </div>

                @endif
            @else
                {{-- Belum Mendaftar --}}
                <div class="rounded-2xl bg-white p-8 text-center shadow-sm border border-gray-100">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                        <i data-lucide="file-plus" class="h-7 w-7 text-blue-600"></i>
                    </div>

                    <p class="mb-4 text-sm leading-relaxed text-gray-500">
                        Seleksi tidak dapat dilakukan karena Anda belum melakukan pendaftaran
                        atau akun Anda belum terverifikasi oleh panitia.
                    </p>

                    <a href="{{ route('pendaftaran.create') }}"
                       class="inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Daftar Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <div class = "mt-4">
    {{-- Hasil Jawaban Pretest --}}
        @if($sudahMengerjakan && $jawabanPeserta->count())
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100">
                <h3 class="mb-4 text-sm font-bold text-gray-800">
                    Hasil Jawaban Pretest
                </h3>

                <div class="max-h-[500px] space-y-4 overflow-y-auto pr-1">
                    @foreach($jawabanPeserta as $index => $jawaban)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <p class="mb-2 text-xs font-bold text-blue-600">
                                Soal {{ $index + 1 }}
                            </p>

                            <p class="mb-3 text-sm text-gray-700">
                                {{ $jawaban->soal->pertanyaan ?? '-' }}
                            </p>

                            <div class="space-y-1 text-xs text-gray-600">
                                <p>
                                    <span class="font-semibold text-gray-800">Jawaban Anda:</span>
                                    {{ $jawaban->jawaban }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-800">Kunci Jawaban:</span>
                                    {{ $jawaban->soal->jawaban_benar ?? '-' }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-800">Nilai:</span>
                                    {{ $jawaban->nilai ?? 0 }}
                                </p>
                            </div>

                            @if($jawaban->is_benar)
                                <span class="mt-3 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                                    Benar
                                </span>
                            @else
                                <span class="mt-3 inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">
                                    Salah
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Banner Jadwal --}}
    <div class="mt-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4">
        <i data-lucide="calendar-days" class="h-5 w-5 text-blue-600"></i>
        <p class="text-sm text-blue-700">
            Jadwal tahap selanjutnya (Wawancara) akan diumumkan setelah Anda menyelesaikan pretest.
        </p>
    </div>
</x-app-layout>