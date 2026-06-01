<x-app-layout>
    <x-slot name="pageTitle">Dashboard Tim Wawancara</x-slot>

    <div class="space-y-6">

        {{-- HEADER --}}

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-2">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">
                        Hello, {{ Auth::user()->name }} !
                    </h1>
                    <p class="text-gray-600 mt-3 leading-relaxed">
                        Selamat datang di sistem pendaftaran santri baru Rumah Gemilang Indonesia
                    </p>
                    <p>
                        Berikut daftar peserta yang perlu diwawancara.
                    </p>
                </div>

                <img src="{{ asset('images/dashboard-student.png') }}" alt="Dashboard" class="hidden md:block w-64">
            </div>

        </div>

        {{-- STATISTIC --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center text-white">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Peserta</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalPeserta }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-green-600 flex items-center justify-center text-white">
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Sudah Wawancara</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $sudahWawancara }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-orange-500 flex items-center justify-center text-white">
                    <i data-lucide="clock" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Belum Wawancara</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $belumWawancara }}</h3>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Daftar Peserta Wawancara
                    </h3>
                    <p class="text-sm text-gray-500">
                        Peserta yang sudah masuk tahap wawancara.
                    </p>
                </div>

                <a href="{{ route('admin.wawancara.index') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-700">
                            <th class="px-5 py-4 font-bold">Kode</th>
                            <th class="px-5 py-4 font-bold">Nama</th>
                            <th class="px-5 py-4 font-bold">Jurusan</th>
                            <th class="px-5 py-4 font-bold">Cabang</th>
                            <th class="px-5 py-4 font-bold">Status</th>
                            <th class="px-5 py-4 font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pesertaWawancara as $peserta)
                            <tr class="border-b border-gray-100">
                                <td class="px-5 py-4">
                                    {{ $peserta->kode_pendaftaran }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-800">
                                    {{ $peserta->nama }}
                                </td>
                                <td class="px-5 py-4">
                                    {{ $peserta->jurusan->nama_jurusan ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    {{ $peserta->cabang->nama_cabang ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                                        Wawancara
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if(auth()->user()->canUpdateMenu('Wawancara'))
                                            <a href="{{ route('admin.wawancara.edit', $peserta->id) }}"
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
                                                <i data-lucide="clipboard-edit" class="w-5 h-5"></i>
                                            </a>
                                        @endif

                                        @if(auth()->user()->canReadMenu('Wawancara'))
                                            <a href="{{ route('admin.wawancara.show', $peserta->id) }}"
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
                                                <i data-lucide="eye" class="w-5 h-5"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                    Belum ada peserta wawancara.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>