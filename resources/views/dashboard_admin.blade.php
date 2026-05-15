<x-app-layout>
    <div class="w-full mx-auto sm:px-6 lg:px-8 py-8 space-y-5">
        {{-- <div class="w-full mx-auto sm:px-6 lg:px-8 py-8"> --}}

        {{-- HEADER --}}
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan data pendaftaran calon siswa berdasarkan cabang dan jurusan
            </p>
        </div>

        {{-- FILTER --}}
        <form method="GET"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

              
            <div>
                <label class="text-xs text-gray-500">Tahun Ajaran</label>
                <select name="tahun_ajaran" class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black">
                    <option value="2024/2025">2024/2025</option>
                    <option value="2025/2026">2025/2026</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Cabang</label>
                <select name="cabang_id" class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Jurusan</label>
                <select name="jurusan_id" class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->nama_jurusan ?? $jurusan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Periode</label>
                <select name="periode" class="mt-1 w-full rounded-xl border-gray-200 text-sm text-black">
                    <option value="2026-05">Mei 2026</option>
                    <option value="2026-04">April 2026</option>
                    <option value="2026-03">Maret 2026</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-5 py-3 font-semibold">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter
                </button>
            </div>
        </form>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pendaftar</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ number_format($totalPendaftar ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-gray-500">Semua cabang / jurusan</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center">
                    <i data-lucide="clock" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pendaftar Hari Ini</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $pendaftarHariIni ?? 0 }}
                    </h2>
                    <p class="text-xs text-green-600 font-semibold">↑ 12% dari kemarin</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Cabang</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $totalCabang ?? 0 }}
                    </h2>
                    <p class="text-xs text-gray-500">Cabang Aktif</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i data-lucide="book-open" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Jurusan</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $totalJurusan ?? 0 }}
                    </h2>
                    <p class="text-xs text-gray-500">Jurusan Aktif</p>
                </div>
            </div>

        </div>
        
        {{-- Status Tahapan Pendaftaran --}}

       <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-6 w-full">
            <h2 class="text-sm font-bold text-gray-900 mb-8">
                Status Tahapan Pendaftaran
            </h2>

            @php
                $total = ($totalPendaftar ?? 0) > 0 ? $totalPendaftar : 1248;

                $steps = [
                    [
                        'label' => 'Menunggu Verifikasi',
                        'count' => ($menungguVerifikasi ?? 0) > 0 ? $menungguVerifikasi : 320,
                        'color' => '#f59e0b',
                    ],
                    [
                        'label' => 'Terverifikasi',
                        'count' => ($terverifikasi ?? 0) > 0 ? $terverifikasi : 280,
                        'color' => '#22c55e',
                    ],
                    [
                        'label' => 'Seleksi Pretest',
                        'count' => ($seleksiPretest ?? 0) > 0 ? $seleksiPretest : 276,
                        'color' => '#2563eb',
                    ],
                    [
                        'label' => 'Wawancara',
                        'count' => ($wawancara ?? 0) > 0 ? $wawancara : 192,
                        'color' => '#8b5cf6',
                    ],
                    [
                        'label' => 'Verifikasi Kelulusan Siswa',
                        'count' => ($verifikasiKelulusan ?? 0) > 0 ? $verifikasiKelulusan : 180,
                        'color' => '#14b8a6',
                    ],
                ];
            @endphp

            {{-- TIMELINE --}}
            <div class="relative w-full">

                {{-- GARIS MEMANJANG --}}
                <div class="absolute top-5 left-0 right-0 h-[3px] z-0">
                    <div class="w-full h-full rounded-full bg-gradient-to-r
                                from-amber-400
                                via-green-500
                                via-blue-600
                                via-violet-500
                                to-teal-600">
                    </div>
                </div>

                {{-- STEP ITEM --}}
                <div class="relative z-10 flex justify-between items-start w-full">

                    @foreach($steps as $index => $step)
                        @php
                            $percent = round(($step['count'] / max($total, 1)) * 100, 1);
                        @endphp

                        <div class="flex flex-col items-center text-center flex-1">

                            {{-- BULATAN --}}
                            <div class="w-10 h-10 rounded-full
                                        flex items-center justify-center
                                        text-white font-bold text-sm
                                        border-4 border-white
                                        ring-2 ring-gray-200
                                        shadow-md"
                                style="background-color: {{ $step['color'] }};">
                                {{ $index + 1 }}
                            </div>

                            {{-- LABEL --}}
                            <p class="mt-3 text-xs font-semibold text-gray-800 leading-tight whitespace-nowrap">
                                {{ $step['label'] }}
                            </p>

                            {{-- ANGKA --}}
                            <div class="mt-1 flex items-baseline gap-1 justify-center">
                                <span class="text-2xl font-bold text-gray-900">
                                    {{ number_format($step['count'], 0, ',', '.') }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    ({{ str_replace('.', ',', $percent) }}%)
                                </span>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- TOTAL --}}
            <div class="relative mt-8 pt-4">
                <div class="absolute left-0 right-0 top-0 h-px bg-gray-200"></div>

                <div class="relative bg-white px-4 text-center text-sm text-gray-500">
                    Total Pendaftar:
                    <span class="font-bold text-gray-900">
                        {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

        {{-- CHART --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Jumlah Pendaftar per Cabang</h3>
                    <select class="text-xs border-gray-200 rounded-lg text-black">
                        <option>Semua Cabang</option>
                    </select>
                </div>
                <div class="relative h-56">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Jumlah Pendaftar per Jurusan</h3>
                    <select class="text-xs border-gray-200 rounded-lg text-black">
                        <option>Semua Jurusan</option>
                    </select>
                </div>
                <div class="relative h-56">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Pendaftar per Bulan</h3>
                    <select class="text-xs border-gray-200 rounded-lg text-black">
                        <option>Semua Cabang</option>
                    </select>
                </div>
                <div class="relative h-56">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Ringkasan Pendaftar per Cabang & Jurusan
                    </h2>
                    <p class="text-sm text-gray-500">
                        Data pendaftaran berdasarkan cabang dan jurusan
                    </p>
                </div>

                <a href="#"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Export
                </a>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-[1000px] w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2" class="px-4 py-3 text-left text-xs font-bold text-gray-600 border-b">No</th>
                            <th rowspan="2" class="px-4 py-3 text-left text-xs font-bold text-gray-600 border-b">Cabang</th>
                            <th colspan="{{ $jurusans->count() }}" class="px-4 py-3 text-center text-xs font-bold text-blue-600 bg-blue-50 border-b">
                                Jumlah Pendaftar per Jurusan
                            </th>
                            <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-600 border-b">Total</th>
                            <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-600 border-b">Aksi</th>
                        </tr>

                        <tr>
                            @foreach($jurusans as $jurusan)
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 border-b whitespace-nowrap">
                                    {{ $jurusan->nama_jurusan ?? $jurusan->nama }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ringkasanCabangJurusan as $index => $cabang)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3.5 text-gray-500">{{ $index + 1 }}</td>

                                <td class="px-4 py-3.5 font-semibold text-gray-900">
                                    {{ $cabang->nama_cabang }}
                                </td>

                                @foreach($jurusans as $jurusan)
                                    <td class="px-4 py-3.5 text-center text-gray-700">
                                        {{ $cabang->pendaftarans->where('jurusan_id', $jurusan->id)->count() }}
                                    </td>
                                @endforeach

                                <td class="px-4 py-3.5 text-center font-extrabold text-blue-600">
                                    {{ $cabang->total_pendaftar }}
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('pendaftaran.index', ['cabang_id' => $cabang->id]) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot class="bg-gray-50">
                        <tr class="border-t-2 border-gray-200 font-extrabold text-blue-600">
                            <td colspan="2" class="px-4 py-3.5">Total</td>

                            @foreach($jurusans as $jurusan)
                                <td class="px-4 py-3.5 text-center">
                                    {{ $pendaftarPerJurusan->where('id', $jurusan->id)->first()->total ?? 0 }}
                                </td>
                            @endforeach

                            <td class="px-4 py-3.5 text-center">
                                {{ number_format($totalPendaftar ?? 0, 0, ',', '.') }}
                            </td>

                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <script>
        const cabangLabels = @json($pendaftarPerCabang->pluck('nama_cabang') ?? []);
        const cabangData = @json($pendaftarPerCabang->pluck('total') ?? []);

        const jurusanLabels = @json($pendaftarPerJurusan->pluck('nama_jurusan') ?? []);
        const jurusanData = @json($pendaftarPerJurusan->pluck('total') ?? []);

        const bulanLabels = @json(($pendaftarPerBulan ?? collect())->pluck('bulan'));
        const bulanData = @json(($pendaftarPerBulan ?? collect())->pluck('total'));

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: cabangLabels,
                datasets: [{
                    data: cabangData,
                    backgroundColor: '#2563eb',
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f3f4f6' }, beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: jurusanLabels,
                datasets: [{
                    data: jurusanData,
                    backgroundColor: ['#2563eb', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, font: { size: 11 } }
                    }
                }
            }
        });

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    data: bulanData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.08)',
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4,
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f3f4f6' }, beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>