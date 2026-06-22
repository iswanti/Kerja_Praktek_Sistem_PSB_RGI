<x-app-layout>
<div class="w-full mx-auto py-5" x-data="{ activeTab: '{{ $defaultTab }}' }">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $pendaftaran->nama }}</h1>
                    <p class="text-blue-100 text-sm mt-1">Kode Daftar: {{ $pendaftaran->kode_pendaftaran }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @php
                        $statusLabels = [
                            'menunggu_verifikasi'        => ['text' => 'Menunggu Verifikasi', 'class' => 'bg-yellow-100 text-yellow-700'],
                            'seleksi_pretest'             => ['text' => 'Seleksi Pretest', 'class' => 'bg-blue-100 text-blue-700'],
                            'wawancara'                   => ['text' => 'Wawancara', 'class' => 'bg-purple-100 text-purple-700'],
                            'verifikasi_kelulusan_siswa'  => ['text' => 'Verifikasi Kelulusan', 'class' => 'bg-teal-100 text-teal-700'],
                            'diterima'                    => ['text' => 'Diterima', 'class' => 'bg-green-100 text-green-700'],
                            'cadangan'                    => ['text' => 'Cadangan', 'class' => 'bg-amber-100 text-amber-700'],
                            'ditolak'                     => ['text' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
                        ];
                        $status = $statusLabels[$pendaftaran->status] ?? ['text' => 'Menunggu Verifikasi', 'class' => 'bg-yellow-100 text-yellow-700'];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $status['class'] }}">{{ $status['text'] }}</span>
                    <a href="{{ route('admin.pendaftaran.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/20 hover:bg-white/30 text-white font-semibold transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-6">

            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
            @endif

            @if($pendaftaran->status === 'ditolak' && $pendaftaran->alasan_ditolak)
                <div class="p-5 rounded-2xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Alasan Penolakan</h3>
                        <p class="text-sm text-red-700 mt-1">{{ $pendaftaran->alasan_ditolak }}</p>
                    </div>
                </div>
            @endif

            {{-- TAB BAR --}}
            @php
                $isAdmin = in_array(Auth::user()->role?->nama, ['Admin', 'Superadmin']);

                $statusOrder = [
                    'menunggu_verifikasi' => 0, 'seleksi_pretest' => 1, 'wawancara' => 2,
                    'verifikasi_kelulusan_siswa' => 3, 'cadangan' => 3, 'diterima' => 3, 'ditolak' => 3,
                ];
                $currentOrder = $statusOrder[$pendaftaran->status] ?? 0;

                $tabs = [
                    ['key' => 'pendaftaran', 'label' => 'Data Pendaftaran', 'icon' => 'file-text', 'locked' => false],
                ];

                if ($isAdmin) {
                    $tabs[] = ['key' => 'pretest',   'label' => 'Pretest',   'icon' => 'clipboard-check', 'locked' => $currentOrder < 1];
                    $tabs[] = ['key' => 'wawancara', 'label' => 'Wawancara', 'icon' => 'mic',              'locked' => $currentOrder < 2];
                    $tabs[] = ['key' => 'kelulusan', 'label' => 'Kelulusan', 'icon' => 'graduation-cap',   'locked' => $currentOrder < 3];
                }
            @endphp

            <div class="flex flex-wrap gap-2 bg-gray-50 p-2 rounded-2xl border border-gray-100">
                @foreach($tabs as $tab)
                    <button type="button"
                        @if(!$tab['locked']) @click="activeTab = '{{ $tab['key'] }}'" @endif
                        @disabled($tab['locked'])
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition"
                        :class="activeTab === '{{ $tab['key'] }}' ? 'bg-blue-600 text-white shadow-sm' : '{{ $tab['locked'] ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-white' }}'">
                        <i data-lucide="{{ $tab['locked'] ? 'lock' : $tab['icon'] }}" class="w-4 h-4"></i>
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>

            {{-- TAB 1: PENDAFTARAN --}}
            <div x-show="activeTab === 'pendaftaran'" x-cloak class="space-y-4">
                @if($isAdmin && $pendaftaran->status === 'menunggu_verifikasi')
                    <div class="p-5 rounded-2xl bg-yellow-50 border border-yellow-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-gray-900">Verifikasi Pendaftaran</h3>
                            <p class="text-sm text-gray-500">Setujui atau tolak pendaftaran setelah memeriksa data.</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <form id="lanjutForm1" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="seleksi_pretest">
                                <button type="button" onclick="confirmLanjut('Verifikasi pendaftaran ini?', 'lanjutForm1')"
                                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                    <i data-lucide="badge-check" class="w-4 h-4"></i> Verifikasi
                                </button>
                            </form>
                            <form id="tolakForm1" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <input type="hidden" name="alasan_ditolak" id="alasan_ditolak1">
                                <button type="button" onclick="confirmTolak('tolakForm1', 'alasan_ditolak1')"
                                    class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @include('pendaftaran.partials._data-pendaftaran', ['pendaftaran' => $pendaftaran])
            </div>

            @if($isAdmin)
                {{-- TAB 2: PRETEST --}}
                <div x-show="activeTab === 'pretest'" x-cloak class="space-y-4">

                    @if($pendaftaran->status === 'seleksi_pretest' && $pendaftaran->nilai_pretest)
                        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900">Lanjut ke Wawancara</h3>
                                <p class="text-sm text-gray-500">Peserta sudah menyelesaikan pretest, lanjutkan ke tahap wawancara.</p>
                            </div>
                            <form id="lanjutForm2" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="wawancara">
                                <button type="button" onclick="confirmLanjut('Lanjutkan ke tahap wawancara?', 'lanjutForm2')"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                    <i data-lucide="arrow-right-circle" class="w-4 h-4"></i> Lanjut ke Wawancara
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(!$pendaftaran->nilai_pretest)
                        <div class="text-center py-10 text-gray-400">Peserta belum ada nilai pretest.</div>
                    @else
                        @php
                            $nilai = $pendaftaran->nilai_pretest;
                            if ($nilai >= 80)      { $badge = 'bg-green-100 text-green-700';  $label = 'Sangat Baik'; }
                            elseif ($nilai >= 70)  { $badge = 'bg-blue-100 text-blue-700';    $label = 'Baik'; }
                            elseif ($nilai >= 60)  { $badge = 'bg-yellow-100 text-yellow-700';$label = 'Cukup'; }
                            else                   { $badge = 'bg-red-100 text-red-700';      $label = 'Kurang'; }
                        @endphp
                        <div class="rounded-2xl bg-blue-500 p-6 flex items-center justify-between text-white">
                            <div>
                                <p class="text-blue-100 text-sm">Nilai Pretest</p>
                                <p class="text-4xl font-bold mt-1">{{ $nilai }}</p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $badge }} !text-gray-800 bg-white">{{ $label }}</span>
                        </div>
                    @endif
                </div>

                {{-- TAB 3: WAWANCARA --}}
                <div x-show="activeTab === 'wawancara'" x-cloak class="space-y-4">

                    @if($pendaftaran->status === 'wawancara' && $pendaftaran->wawancara?->semua_unsur_selesai)
                        <div class="p-5 rounded-2xl bg-purple-50 border border-purple-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900">Lanjut ke Verifikasi Kelulusan</h3>
                                <p class="text-sm text-gray-500">Semua unsur wawancara sudah diisi.</p>
                            </div>
                            <form id="lanjutForm3" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="verifikasi_kelulusan_siswa">
                                <button type="button" onclick="confirmLanjut('Lanjutkan ke verifikasi kelulusan?', 'lanjutForm3')"
                                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                    <i data-lucide="arrow-right-circle" class="w-4 h-4"></i> Lanjut ke Verifikasi Kelulusan
                                </button>
                            </form>
                        </div>
                    @endif

                    @include('pendaftaran.partials._data-wawancara', ['wawancara' => $pendaftaran->wawancara])
                </div>

                {{-- TAB 4: KELULUSAN --}}
                <div x-show="activeTab === 'kelulusan'" x-cloak class="space-y-6">

                    @php
                        $isKelulusan = $pendaftaran->status === 'verifikasi_kelulusan_siswa';
                        $isCadangan  = $pendaftaran->status === 'cadangan';
                    @endphp

                    @if(($isKelulusan || $isCadangan) && $verifikasiKelulusanAktif)
                        <div class="p-5 rounded-2xl {{ $isCadangan ? 'bg-amber-50 border-amber-200' : 'bg-teal-50 border-teal-200' }} border flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $isCadangan ? 'Peserta Cadangan' : 'Verifikasi Kelulusan' }}</h3>
                                <p class="text-sm text-gray-500">Tentukan keputusan akhir penerimaan peserta ini.</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <form id="diterimaForm4" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="button" onclick="confirmLanjut('Terima peserta ini?', 'diterimaForm4')"
                                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i> Diterima
                                    </button>
                                </form>
                                @if($isKelulusan)
                                    <form id="cadanganForm4" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="cadangan">
                                        <button type="button" onclick="confirmLanjut('Jadikan peserta ini cadangan?', 'cadanganForm4')"
                                            class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                            <i data-lucide="bookmark" class="w-4 h-4"></i> Cadangan
                                        </button>
                                    </form>
                                @endif
                                <form id="tolakForm4" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="alasan_ditolak" id="alasan_ditolak4">
                                    <button type="button" onclick="confirmTolak('tolakForm4', 'alasan_ditolak4')"
                                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i> {{ $isCadangan ? 'Tidak Diterima' : 'Tidak Lulus' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($isKelulusan && !$verifikasiKelulusanAktif)
                        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Verifikasi Kelulusan Belum Dibuka</h3>
                                <p class="text-sm text-gray-500">
                                    Jadwal verifikasi kelulusan belum tiba.
                                    @if($pendaftaran->gelombang?->pengumuman_mulai)
                                        Dibuka pada: <span class="font-semibold text-blue-700">{{ \Carbon\Carbon::parse($pendaftaran->gelombang->pengumuman_mulai)->translatedFormat('d F Y H:i') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- RECAP: pendaftaran + nilai pretest + wawancara, full --}}
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Ringkasan Data Pendaftaran</h3>
                        @include('pendaftaran.partials._data-pendaftaran', ['pendaftaran' => $pendaftaran])
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Nilai Pretest</h3>
                        @if($pendaftaran->nilai_pretest)
                            <div class="rounded-2xl bg-blue-500 p-6 flex items-center justify-between text-white">
                                <p class="text-blue-100 text-sm">Nilai Pretest</p>
                                <p class="text-3xl font-bold mt-1">{{ $pendaftaran->nilai_pretest }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Belum ada nilai pretest.</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Hasil Wawancara</h3>
                        @if(!$pendaftaran->wawancara)
                            <p class="text-sm text-gray-400">Belum ada hasil wawancara.</p> 
                        @else
                            @include('pendaftaran.partials._data-wawancara', ['wawancara' => $pendaftaran->wawancara])
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLanjut(message, formId) {
        Swal.fire({
            title: 'Konfirmasi', text: message, icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya, Lanjutkan', cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb', cancelButtonColor: '#ef4444', reverseButtons: true,
            customClass: { popup: 'rounded-3xl' }
        }).then(result => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    }

    function confirmTolak(formId, inputId) {
        Swal.fire({
            title: 'Alasan Penolakan', input: 'textarea', inputPlaceholder: 'Tuliskan alasan penolakan...',
            inputAttributes: { rows: 3 }, showCancelButton: true, confirmButtonText: 'Tolak', cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true,
            customClass: { popup: 'rounded-3xl' },
            preConfirm: (value) => { if (!value) Swal.showValidationMessage('Alasan penolakan wajib diisi'); return value; }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(inputId).value = result.value;
                document.getElementById(formId).submit();
            }
        });
    }
</script>
</x-app-layout>