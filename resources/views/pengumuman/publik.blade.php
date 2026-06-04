<x-app-layout>
    <x-slot name="pageTitle">
        Pengumuman Hasil Seleksi
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Card Hasil --}}
        @if(isset($pendaftaran) && $pengumumanAktif)
                {{-- Informasi Ringkas --}}
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Kode Pendaftaran</p>
                            <p class="font-semibold text-gray-900">
                                {{ $pendaftaran->kode_pendaftaran ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Nama Peserta</p>
                            <p class="font-semibold text-gray-900">
                                {{ $pendaftaran->nama ?? auth()->user()->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Nilai Pretest</p>
                            <p class="font-semibold text-gray-900">
                                {{ $pendaftaran->nilai_pretest !== null ? number_format($pendaftaran->nilai_pretest, 2) : '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Program Keahlian</p>
                            <p class="font-semibold text-gray-900">
                                {{ $pendaftaran->jurusan->nama_jurusan ?? '-' }}
                            </p>
                        </div>

                    </div>
                </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 pt-5">
                            <h3 class="text-xl font-bold text-gray-900">
                                Hasil Kelulusan
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="border-l-4 rounded-2xl p-6
                                @if($pendaftaran->status === 'diterima')
                                    border-green-500 bg-green-50
                                @elseif($pendaftaran->status === 'ditolak')
                                    border-red-500 bg-red-50
                                @else
                                    border-yellow-500 bg-yellow-50
                                @endif">

                                <div class="flex items-center gap-6">

                                    @if($pendaftaran->status === 'diterima')
                                        <div class="w-28 h-28 rounded-full bg-green-500 flex items-center justify-center text-white">
                                            <i data-lucide="file-check" class="w-14 h-14"></i>
                                        </div>
                                    @elseif($pendaftaran->status === 'ditolak')
                                        <div class="w-28 h-28 rounded-full bg-red-500 flex items-center justify-center text-white">
                                            <i data-lucide="file-x" class="w-14 h-14"></i>
                                        </div>
                                    @else
                                        <div class="w-28 h-28 rounded-full bg-yellow-500 flex items-center justify-center text-white">
                                            <i data-lucide="clock" class="w-14 h-14"></i>
                                        </div>
                                    @endif

                                    <div>
                                        @if($pendaftaran->status === 'diterima')
                                            <p class="text-sm text-gray-700 mb-2">Selamat Anda dinyatakan</p>

                                            <div class="flex items-center gap-4">
                                                <h2 class="text-5xl font-extrabold tracking-wide text-green-600">
                                                    LULUS
                                                </h2>

                                                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                                    diterima
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mt-3">
                                                Anda diterima sebagai calon santri di Rumah Gemilang Indonesia.
                                            </p>

                                        @elseif($pendaftaran->status === 'ditolak')
                                            <p class="text-sm text-gray-700 mb-2">
                                                Hasil seleksi Anda
                                            </p>

                                            <div class="flex items-center gap-4">
                                                <h2 class="text-4xl font-extrabold tracking-wide text-red-600">
                                                    TIDAK LULUS
                                                </h2>

                                                <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                                    ditolak
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mt-3">
                                                Terima kasih telah mengikuti proses seleksi.
                                            </p>

                                            {{-- Alasan Penolakan --}}
                                            @if(!empty($pendaftaran->alasan_ditolak))
                                                <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200">
                                                    <div class="flex items-start gap-3">
                                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                                                        </div>

                                                        <div>
                                                            <p class="text-sm font-semibold text-red-700 mb-1">
                                                                Alasan Tidak Lulus
                                                            </p>
                                                            <p class="text-sm text-red-600 leading-relaxed">
                                                                {{ $pendaftaran->alasan_ditolak }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        

                                        @else
                                            <p class="text-sm text-gray-700 mb-2">Hasil kelulusan</p>

                                            <div class="flex items-center gap-4">
                                                <h2 class="text-4xl font-extrabold tracking-wide text-yellow-600">
                                                    MENUNGGU
                                                </h2>

                                                <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                                    belum diumumkan
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mt-3">
                                                Hasil kelulusan masih dalam proses penilaian panitia.
                                            </p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        @elseif(isset($pendaftaran) && !$pengumumanAktif)

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-5">
                    <i data-lucide="clock-3" class="w-10 h-10 text-yellow-600"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-3">
                    Pengumuman Belum Dibuka
                </h2>

                <p class="text-gray-500 max-w-lg mx-auto leading-relaxed">
                    Hasil kelulusan belum dapat ditampilkan karena jadwal pengumuman
                    belum dimulai.
                </p>

                @if($pendaftaran->gelombang?->pengumuman_mulai)
                    <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700">
                        <i data-lucide="calendar-days" class="w-4 h-4"></i>

                        Dibuka :
                        {{ \Carbon\Carbon::parse($pendaftaran->gelombang->pengumuman_mulai)->translatedFormat('d F Y H:i') }}
                    </div>
                @endif

            </div>
        @else

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-5">
                <i data-lucide="info" class="w-10 h-10 text-yellow-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                Pengumuman Belum Tersedia
            </h2>
            <p class="text-gray-500 max-w-lg mx-auto leading-relaxed">
                Anda belum melakukan pendaftaran atau data Anda belum diverifikasi
                oleh panitia, sehingga hasil pengumuman belum dapat ditampilkan.
            </p>
            <div class="mt-6">
                <a href="{{ route('pendaftaran.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                    <i data-lucide="file-plus" class="w-4 h-4"></i>
                    Daftar Sekarang
                </a>
            </div>

        </div>

    @endif

    </div>
</x-app-layout>