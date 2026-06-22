<x-app-layout>
    <div class="w-full mx-auto py-8 space-y-5">

        {{-- FILTER --}}
        <form method="GET"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-2 {{ $isSuperadmin ? 'xl:grid-cols-5' : 'xl:grid-cols-4' }} gap-4 items-end">

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Tahun Ajaran</label>
                    <select name="tahun_periode"
                            class="w-full h-12 rounded-xl border-gray-200 text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                        <option value="">Semua Tahun Ajaran</option>

                        @foreach($tahunPeriodes as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun_periode') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($isSuperadmin)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Cabang</label>
                        <select name="cabang_id"
                                class="w-full h-12 rounded-xl border-gray-200 text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Semua Cabang</option>

                            @foreach($cabangs as $cabang)
                                <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Jurusan</label>
                    <select name="nama_jurusan"
                            class="w-full h-12 rounded-xl border-gray-200 text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                        <option value="">Semua Jurusan</option>

                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->nama_jurusan }}" {{ request('nama_jurusan') == $jurusan->nama_jurusan ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Gelombang</label>
                    <select name="gelombang_id"
                            class="w-full h-12 rounded-xl border-gray-200 text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                        <option value="">Semua Gelombang</option>

                        @foreach($gelombangs as $gelombang)
                            <option value="{{ $gelombang->id }}" {{ request('gelombang_id') == $gelombang->id ? 'selected' : '' }}>
                                {{ $gelombang->nama_gelombang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                             class="h-12 px-5 inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold">
                        Filter
                    </button>

                    <a href="{{ url()->current() }}"
                    class="h-12 inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-5 text-sm font-semibold">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

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
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Diterima</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $diterima ?? 0 }}
                    </h2>
                    <p class="text-xs text-gray-500">Peserta Lulus</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i data-lucide="bookmark" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cadangan</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $cadangan ?? 0 }}
                    </h2>
                    <p class="text-xs text-gray-500">Peserta Cadangan</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ $ditolak ?? 0 }}
                    </h2>
                    <p class="text-xs text-gray-500">Peserta Tidak Lulus</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Alumni</p>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ number_format($totalAlumni ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-gray-500">
                        {{ request('tahun_periode')
                            ? 'Angkatan ' . request('tahun_periode')
                            : 'Semua angkatan' }}
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Status Tahapan Pendaftaran --}}

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-4 sm:px-8 py-6 w-full overflow-x-auto">

            <h2 class="text-sm font-bold text-gray-900 mb-6 sm:mb-8">
                Status Tahapan Pendaftaran
            </h2>

            @php
                $total = ($totalPendaftar ?? 0) > 0 ? $totalPendaftar : 0;
            @endphp

            @php
                $steps = [
                    ['label' => 'Menunggu Verifikasi', 'count' => ($menungguVerifikasi ?? 0), 'color' => '#f59e0b'],
                    ['label' => 'Seleksi Pretest', 'count' => ($seleksiPretest ?? 0), 'color' => '#2563eb'],
                    ['label' => 'Wawancara', 'count' => ($wawancara ?? 0), 'color' => '#8b5cf6'],
                    ['label' => 'Verifikasi Kelulusan', 'count' => ($verifikasiKelulusan ?? 0), 'color' => '#14b8a6'],
                    ['label' => 'Diterima', 'count' => ($diterima ?? 0), 'color' => '#22c55e'],
                ];
            @endphp

            {{-- TIMELINE --}}
            <div class="relative w-full min-w-[700px] sm:min-w-0">

                {{-- LINE --}}
                <div class="absolute top-5 left-4 right-4 h-[3px] z-0 hidden sm:block">
                    <div class="w-full h-full rounded-full bg-gradient-to-r
                                from-amber-400 via-blue-500 via-violet-500 to-teal-600">
                    </div>
                </div>

                {{-- MOBILE SCROLL LINE --}}
                <div class="absolute top-5 left-0 right-0 h-[2px] z-0 sm:hidden bg-gray-200"></div>

                <div class="relative z-10 flex gap-6 sm:gap-0 sm:justify-between items-start w-max sm:w-full">

                    @foreach($steps as $index => $step)
                        @php
                            $percent = round(($step['count'] / max($total, 1)) * 100, 1);
                        @endphp

                        <div class="flex flex-col items-center text-center w-[140px] sm:flex-1">

                            {{-- BULAT --}}
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm border-2 sm:border-4 border-white ring-1 sm:ring-2 ring-gray-200 shadow-md"
                                style="background-color: {{ $step['color'] }};">
                                {{ $index + 1 }}
                            </div>

                            {{-- LABEL --}}
                            <p class="mt-2 text-[11px] sm:text-xs font-semibold text-gray-800 leading-tight break-words">
                                {{ $step['label'] }}
                            </p>

                            {{-- COUNT --}}
                            <div class="mt-1 flex flex-col sm:flex-row items-center gap-0 sm:gap-1 justify-center">
                                <span class="text-lg sm:text-2xl font-bold text-gray-900">
                                    {{ number_format($step['count'], 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] sm:text-sm text-gray-500">
                                    ({{ str_replace('.', ',', $percent) }}%)
                                </span>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

            {{-- TOTAL --}}
            <div class="relative mt-6 sm:mt-8 pt-4">
                <div class="absolute left-0 right-0 top-0 h-px bg-gray-200"></div>

                <div class="relative bg-white px-2 sm:px-4 text-center text-xs sm:text-sm text-gray-500">
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
                <div class="relative h-80">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Pendaftar & Alumni per Jurusan</h3>
                    <select class="text-xs border-gray-200 rounded-lg text-black">
                        <option>Semua Jurusan</option>
                    </select>
                </div>
                <div class="relative h-80">
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
                <div class="relative h-80">
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

                <a href="{{ route('admin.dashboard.export-ringkasan', request()->query()) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Export
                </a>
                
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-[1200px] w-full text-sm">
                    <thead class="bg-gray-50">

                        <tr>
                            <th rowspan="2"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 border-b">
                                No
                            </th>

                            <th rowspan="2"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 border-b">
                                Cabang
                            </th>

                            <th colspan="{{ $jurusansRingkasan->count() }}"
                                class="px-4 py-3 text-center text-xs font-bold text-blue-600 bg-blue-50 border-b">
                                Jumlah Pendaftar per Jurusan
                            </th>

                            <th rowspan="2"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-600 border-b">
                                Total
                            </th>

                            <th rowspan="2"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-600 border-b">
                                Aksi
                            </th>
                        </tr>

                        <tr>
                            @foreach($jurusansRingkasan as $jurusan)
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 border-b whitespace-nowrap">
                                    {{ $jurusan->nama_jurusan }}
                                </th>
                            @endforeach
                        </tr>

                    </thead>

                    <tbody>
                        @forelse($ringkasanCabangJurusan as $index => $cabang)

                            <tr class="border-t hover:bg-gray-50">

                                <td class="px-4 py-3.5 text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3.5 font-semibold text-gray-900">
                                    {{ $cabang->nama_cabang }}
                                </td>

                                @foreach($jurusansRingkasan as $jurusan)

                                    @php
                                        $jumlah = $cabang->pendaftarans
                                            ->filter(function ($pendaftaran) use ($jurusan) {
                                                return optional($pendaftaran->jurusan)->nama_jurusan
                                                    === $jurusan->nama_jurusan;
                                            })
                                            ->count();
                                    @endphp

                                    <td class="px-4 py-3.5 text-center text-gray-700">
                                        {{ $jumlah }}
                                    </td>

                                @endforeach

                                <td class="px-4 py-3.5 text-center font-extrabold text-blue-600">
                                    {{ $cabang->total_pendaftar }}
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('admin.pendaftaran.index', ['cabang_id' => $cabang->id]) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="{{ $jurusansRingkasan->count() + 4 }}"
                                    class="px-4 py-10 text-center text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                    <tfoot class="bg-gray-50">

                        <tr class="border-t-2 border-gray-200 font-extrabold text-blue-600">

                            <td colspan="2" class="px-4 py-3.5">
                                Total
                            </td>

                            @foreach($jurusansRingkasan as $jurusan)

                                <td class="px-4 py-3.5 text-center">

                                    {{
                                        $pendaftarPerJurusan
                                            ->where('nama_jurusan', $jurusan->nama_jurusan)
                                            ->first()
                                            ?->total ?? 0
                                    }}

                                </td>

                            @endforeach

                            <td class="px-4 py-3.5 text-center">
                                {{ number_format($totalPendaftar, 0, ',', '.') }}
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

        @php
            $jurusanChartLabels = $pendaftarPerJurusan
                ->pluck('nama_jurusan')
                ->merge($alumniPerJurusan->pluck('nama_jurusan'))
                ->unique()
                ->values();

            $jurusanPendaftarData = $jurusanChartLabels->map(function ($namaJurusan) use ($pendaftarPerJurusan) {
                return $pendaftarPerJurusan
                    ->firstWhere('nama_jurusan', $namaJurusan)
                    ?->total ?? 0;
            });

            $jurusanAlumniData = $jurusanChartLabels->map(function ($namaJurusan) use ($alumniPerJurusan) {
                return $alumniPerJurusan
                    ->firstWhere('nama_jurusan', $namaJurusan)
                    ?->total ?? 0;
            });
        @endphp

        const jurusanLabels = @json($jurusanChartLabels);
        const jurusanPendaftarData = @json($jurusanPendaftarData);
        const jurusanAlumniData = @json($jurusanAlumniData);

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
            type: 'bar',
            data: {
                labels: jurusanLabels,
                datasets: [
                    {
                        label: 'Pendaftar',
                        data: jurusanPendaftarData,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barThickness: 14
                    },
                    {
                        label: 'Alumni',
                        data: jurusanAlumniData,
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                        barThickness: 14
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return label.length > 28 ? label.substring(0, 28) + '...' : label;
                            }
                        }
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