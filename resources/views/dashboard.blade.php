<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- HEADER WELCOME --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">
                        Hello, {{ Auth::user()->name }} !
                    </h1>
                    <p class="text-gray-600 mt-3 leading-relaxed">
                        Selamat datang di sistem pendaftaran santri baru<br>
                        Rumah Gemilang Indonesia
                    </p>
                    <a href="https://rumahgemilang.id/" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition"> 
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        Lihat Info Lebih Lengkap
                    </a>
                </div>

                <img src="{{ asset('images/dashboard-student.png') }}" alt="Dashboard" class="hidden md:block w-64">
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Butuh Bantuan?
                    </h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Jika Anda mengalami kendala silakan hubungi panitia.
                    </p>
                    
                    <a href="https://wa.me/6289512205187?text=Assalamu'alaikum%20Panitia%20PSB%20RGI%20saya%20ingin%20bertanya%20terkait%20pretest." target="_blank" rel="noopener noreferrer"
                    class="mt-4 inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700">
                        <i data-lucide="headphones" class="w-4 h-4"></i>
                            Hubungi Panitia
                    </a>
                </div>

                <img src="{{ asset('images/helpdesk.png') }}" alt="Bantuan" class="hidden md:block w-36">
            </div>
        </div>

        {{-- STATISTIC CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Status Pendaftaran --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center text-white">
                    <i data-lucide="file-text" class="w-7 h-7"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Status Pendaftaran</p>
                    <h3 class="text-xl font-bold text-blue-600">
                        {{ $pendaftaran->status ?? 'Belum Daftar' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $pendaftaran ? 'Data Anda telah tersimpan' : 'Silakan lakukan pendaftaran' }}
                    </p>
                </div>
            </div>

            {{-- Jadwal Seleksi --}}
            @php
                $jadwalTerdekat = null;
                $namaJadwal = '-';

                if ($gelombang) {
                    $jadwals = collect([
                        [
                            'nama' => 'Pretest',
                            'tanggal' => $gelombang->pretest_mulai,
                        ],
                        [
                            'nama' => 'Wawancara',
                            'tanggal' => $gelombang->wawancara_mulai,
                        ],
                        [
                            'nama' => 'Pengumuman Hasil',
                            'tanggal' => $gelombang->pengumuman_mulai,
                        ],
                    ])
                    ->filter(fn ($item) => !empty($item['tanggal']))
                    ->filter(fn ($item) => \Carbon\Carbon::parse($item['tanggal'])->gte(now()))
                    ->sortBy('tanggal')
                    ->first();

                    if ($jadwals) {
                        $jadwalTerdekat = \Carbon\Carbon::parse($jadwals['tanggal']);
                        $namaJadwal = $jadwals['nama'];
                    }
                }
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-violet-600 flex items-center justify-center text-white">
                    <i data-lucide="calendar-days" class="w-7 h-7"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Jadwal Seleksi Terdekat</p>

                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $jadwalTerdekat
                            ? $jadwalTerdekat->translatedFormat('d F Y')
                            : '-' }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $namaJadwal }}
                    </p>
                </div>
            </div>

            {{-- Pretest --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-orange-500 flex items-center justify-center text-white">
                    <i data-lucide="clock-3" class="w-7 h-7"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Pretest</p>
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ number_format($pendaftaran->nilai_pretest ?? 0, 0) }} / 100
                    </h3>
                    <p class="text-sm text-gray-500">Nilai Anda</p>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT CONTENT --}}
            <div class="lg:col-span-2 space-y-6">
                @if($pendaftaran?->status === 'wawancara' && $jadwalWawancaras->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-5">
                            Jadwal Wawancara
                        </h3>

                        <div class="space-y-4">
                            @foreach($jadwalWawancaras as $jadwal)
                                <div class="flex items-center justify-between rounded-2xl bg-blue-50 p-4">

                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i data-lucide="video" class="w-6 h-6"></i>
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                {{ $unsurLabels[$jadwal->unsur] ?? ucfirst($jadwal->unsur) }}
                                            </h4>

                                            <p class="text-sm text-gray-500">
                                                {{ $jadwal->waktu_mulai?->translatedFormat('d F Y · H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <a href="{{ $jadwal->link_wawancara }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                        Masuk
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                {{-- HASIL KELULUSAN --}}
                @if(isset($pendaftaran))
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
                @endif

                {{-- DATA PENDAFTARAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-900">
                        Data Pendaftaran Saya
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Berikut adalah data pendaftaran yang telah Anda submit.
                    </p>

                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-left text-gray-700">
                                    <th class="px-5 py-4 font-bold">Kode Daftar</th>
                                    <th class="px-5 py-4 font-bold">Nama</th>
                                    <th class="px-5 py-4 font-bold">Jurusan</th>
                                    <th class="px-5 py-4 font-bold">Cabang</th>
                                    <th class="px-5 py-4 font-bold">Status</th>
                                    <th class="px-5 py-4 font-bold">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if($pendaftaran)
                                    <tr class="border-b border-gray-100">
                                        <td class="px-5 py-4">
                                            {{ $pendaftaran->kode_pendaftaran ?? '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            {{ $pendaftaran->nama ?? Auth::user()->name }}
                                        </td>
                                        <td class="px-5 py-4">
                                            {{ $pendaftaran->jurusan->nama_jurusan ?? '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            {{ $pendaftaran->cabang->nama_cabang ?? '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                {{ $pendaftaran->status }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('pendaftaran.show', $pendaftaran->id) }}"
                                                   class="text-blue-600 hover:text-blue-700">
                                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                            Anda belum melakukan pendaftaran.
                                            <a href="{{ route('pendaftaran.create') }}"
                                               class="text-blue-600 font-semibold hover:underline">
                                                Daftar sekarang
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- INFO --}}
                <div class="bg-blue-50 border border-blue-300 rounded-2xl px-6 py-4 flex items-center gap-4 text-blue-700">
                    <i data-lucide="info" class="w-6 h-6"></i>
                    <p class="text-sm font-semibold">
                        Pastikan data pendaftaran Anda sudah benar. Jika ada kesalahan, hubungi panitia.
                    </p>
                </div>
            </div>

            {{-- RIGHT CONTENT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    Jadwal Seleksi
                </h3>

                @php
                    $pretestSelesai = $gelombang?->pretest_selesai
                        ? now()->gt($gelombang->pretest_selesai)
                        : false;

                    $wawancaraSelesai = $gelombang?->wawancara_selesai
                        ? now()->gt($gelombang->wawancara_selesai)
                        : false;

                    $pengumumanSelesai = $gelombang?->pengumuman_mulai
                        ? now()->gt($gelombang->pengumuman_mulai)
                        : false;
                @endphp

                <div class="space-y-4">
                    {{-- PRETEST --}}
                    <div class="flex items-center gap-4 rounded-2xl p-4 {{ $pretestSelesai ? 'bg-green-50' : 'bg-blue-50' }}">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center
                            {{ $pretestSelesai ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                            <i data-lucide="calendar-check" class="w-6 h-6"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">Pretest</h4>

                            <p class="text-xs text-gray-500">
                                @if ($gelombang?->pretest_mulai)
                                    {{ \Carbon\Carbon::parse($gelombang->pretest_mulai)->translatedFormat('d F Y · H:i') }}

                                    @if ($gelombang?->pretest_selesai)
                                        - {{ \Carbon\Carbon::parse($gelombang->pretest_selesai)->format('H:i') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $pretestSelesai ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $pretestSelesai ? 'Selesai' : 'Akan Datang' }}
                        </span>
                    </div>

                    {{-- WAWANCARA --}}
                    <div class="flex items-center gap-4 rounded-2xl p-4 {{ $wawancaraSelesai ? 'bg-green-50' : 'bg-blue-50' }}">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center
                            {{ $wawancaraSelesai ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                            <i data-lucide="calendar-days" class="w-6 h-6"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">Wawancara</h4>

                            <p class="text-xs text-gray-500">
                                @if ($gelombang?->wawancara_mulai)
                                    {{ \Carbon\Carbon::parse($gelombang->wawancara_mulai)->translatedFormat('d F Y · H:i') }}

                                    @if ($gelombang?->wawancara_selesai)
                                        - {{ \Carbon\Carbon::parse($gelombang->wawancara_selesai)->format('H:i') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $wawancaraSelesai ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $wawancaraSelesai ? 'Selesai' : 'Akan Datang' }}
                        </span>
                    </div>

                    {{-- PENGUMUMAN --}}
                    <div class="flex items-center gap-4 rounded-2xl p-4 {{ $pengumumanSelesai ? 'bg-green-50' : 'bg-orange-50' }}">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center
                            {{ $pengumumanSelesai ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                            <i data-lucide="megaphone" class="w-6 h-6"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">
                                Pengumuman Hasil
                            </h4>

                            <p class="text-xs text-gray-500">
                                @if ($gelombang?->pengumuman_mulai)
                                    {{ \Carbon\Carbon::parse($gelombang->pengumuman_mulai)->translatedFormat('d F Y · H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $pengumumanSelesai ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $pengumumanSelesai ? 'Selesai' : 'Akan Datang' }}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>