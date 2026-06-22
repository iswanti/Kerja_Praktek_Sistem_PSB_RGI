@php
    $sections = [
        ['id' => 'kampus',    'icon' => 'building',   'title' => 'Kampus dan jurusan', 'sub' => ($pendaftaran->jurusan?->nama_jurusan ?? '-') . ' · ' . ($pendaftaran->cabang?->nama_cabang ?? '-'), 'open' => true],
        ['id' => 'datadiri',  'icon' => 'user',        'title' => 'Data diri dan media sosial', 'sub' => $pendaftaran->nama . ' · ' . $pendaftaran->no_hp, 'open' => false],
        ['id' => 'orangtua',  'icon' => 'users',       'title' => 'Data orang tua / wali', 'sub' => ($pendaftaran->nama_wali ?? '-') . ' · ' . ($pendaftaran->nama_ibu ?? '-'), 'open' => false],
        ['id' => 'lainlain',  'icon' => 'file-text',   'title' => 'Lain-lain', 'sub' => 'Motivasi, alasan, pengenalan RGI', 'open' => false],
        ['id' => 'dokumen',   'icon' => 'paperclip',   'title' => 'Dokumen pendukung', 'sub' => '7 jenis dokumen', 'open' => false],
    ];
@endphp

<div x-data="{ openSection: 'kampus' }">
    @foreach($sections as $section)
        <div class="border border-gray-100 rounded-2xl overflow-hidden">
                <button type="button"
                    @click="openSection = (openSection === '{{ $section['id'] }}' ? null : '{{ $section['id'] }}')"
                    class="w-full flex items-center justify-between px-5 py-4 bg-blue-50 hover:bg-blue-100 transition text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $section['icon'] }}" class="w-4 h-4 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-gray-800">{{ $section['title'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $section['sub'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
                    :class="openSection === '{{ $section['id'] }}' ? 'rotate-180' : ''"></i>
                </button>

            <div x-show="openSection === '{{ $section['id'] }}'" x-transition x-cloak class="px-5 pb-5 pt-1 border-t border-gray-100 bg-white">

                @if($section['id'] === 'kampus')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-detail-item label="Kampus" :value="$pendaftaran->cabang?->nama_cabang ?? '-'" />
                        <x-detail-item label="Jurusan" :value="$pendaftaran->jurusan?->nama_jurusan ?? $pendaftaran->jurusan?->nama ?? '-'" />
                    </div>
                @endif

                @if($section['id'] === 'datadiri')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-detail-item label="NIK" :value="$pendaftaran->nik" />
                        <x-detail-item label="No KK" :value="$pendaftaran->nkk" />
                        <x-detail-item label="Nama Lengkap" :value="$pendaftaran->nama" />
                        <x-detail-item label="Tempat Lahir" :value="$pendaftaran->tempat_lahir" />
                        <x-detail-item label="Tanggal Lahir" :value="$pendaftaran->tgl_lahir" />
                        <x-detail-item label="Umur" :value="$pendaftaran->umur . ' tahun'" />
                        <x-detail-item label="Jenis Kelamin" :value="$pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'" />
                        <x-detail-item label="Anak Ke" :value="$pendaftaran->anak_ke" />
                        <x-detail-item label="No HP" :value="$pendaftaran->no_hp" />
                        <x-detail-item label="Pendidikan Terakhir" :value="$pendaftaran->pendidikan" />
                        <x-detail-item label="Nama Sekolah" :value="$pendaftaran->sekolah" />
                        <x-detail-item label="Cita-cita" :value="$pendaftaran->cita_cita" />
                        <x-detail-item label="Hobi" :value="is_array($pendaftaran->hobi) ? implode(', ', $pendaftaran->hobi) : $pendaftaran->hobi" />
                        <x-detail-item label="Penyakit" :value="$pendaftaran->penyakit ?? '-'" />
                    </div>
                    <div class="mt-4"><x-detail-item label="Alamat Lengkap" :value="$pendaftaran->alamat" /></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-detail-item label="Provinsi" :value="$pendaftaran->provinsi_nama" />
                        <x-detail-item label="Kabupaten/Kota" :value="$pendaftaran->kabupaten_nama" />
                        <x-detail-item label="Kecamatan" :value="$pendaftaran->kecamatan_nama" />
                        <x-detail-item label="Kelurahan" :value="$pendaftaran->kelurahan_nama" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                        <x-detail-item label="Facebook" :value="$pendaftaran->facebook" />
                        <x-detail-item label="Instagram" :value="$pendaftaran->instagram" />
                    </div>
                @endif

                @if($section['id'] === 'orangtua')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-detail-item label="Nama Ayah / Wali" :value="$pendaftaran->nama_wali" />
                        <x-detail-item label="Pendidikan Wali" :value="$pendaftaran->pendidikan_wali" />
                        <x-detail-item label="Pekerjaan Wali" :value="$pendaftaran->pekerjaan_wali" />
                        <x-detail-item label="No HP Wali" :value="$pendaftaran->nohp_wali" />
                        <x-detail-item label="Nama Ibu" :value="$pendaftaran->nama_ibu" />
                        <x-detail-item label="Pendidikan Ibu" :value="$pendaftaran->pendidikan_ibu" />
                        <x-detail-item label="Pekerjaan Ibu" :value="$pendaftaran->pekerjaan_ibu" />
                        <x-detail-item label="No HP Ibu" :value="$pendaftaran->nohp_ibu" />
                        <x-detail-item label="Jumlah Keluarga" :value="$pendaftaran->jml_keluarga" />
                        <x-detail-item label="Pendapatan Keluarga" :value="$pendaftaran->pendapatan_keluarga" />
                        <x-detail-item label="Status Rumah" :value="$pendaftaran->status_rumah" />
                    </div>
                    <div class="mt-4"><x-detail-item label="Alamat Orang Tua" :value="$pendaftaran->alamat_orangtua" /></div>
                @endif

                @if($section['id'] === 'lainlain')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-detail-item label="Alasan Memilih RGI" :value="$pendaftaran->alasan" />
                        <x-detail-item label="Mengetahui RGI Dari" :value="is_array($pendaftaran->pengenalan) ? implode(', ', $pendaftaran->pengenalan) : $pendaftaran->pengenalan" />
                        <x-detail-item label="Rekomendasi" :value="$pendaftaran->rekomendasi ?? '-'" />
                    </div>
                    <div class="mt-4"><x-detail-item label="Motivasi" :value="$pendaftaran->motivasi" /></div>
                @endif

                @if($section['id'] === 'dokumen')
                    @php
                        $files = [
                            'pas_foto'    => ['label' => 'Pas Foto', 'icon' => 'user'],
                            'foto_kk'     => ['label' => 'Kartu Keluarga', 'icon' => 'file-text'],
                            'foto_ktp'    => ['label' => 'KTP', 'icon' => 'credit-card'],
                            'foto_ijazah' => ['label' => 'Ijazah Terakhir', 'icon' => 'award'],
                            'sktm'        => ['label' => 'SKTM / Surat Rekomendasi DKM', 'icon' => 'file-check'],
                            'surat_sehat' => ['label' => 'Surat Keterangan Sehat', 'icon' => 'heart-pulse'],
                            'foto_rumah'  => ['label' => 'Foto Rumah', 'icon' => 'home'],
                        ];
                    @endphp
                    <div x-data="{ previewOpen: false, previewFile: '' }" class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach($files as $field => $meta)
                                <div class="border border-gray-100 rounded-xl p-4 flex items-center gap-3 hover:border-blue-200 hover:bg-blue-50/30 transition">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="{{ $meta['icon'] }}" class="w-5 h-5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700 leading-tight">{{ $meta['label'] }}</p>
                                        @if($pendaftaran->$field)
                                            <button type="button" @click="previewOpen = true; previewFile = '{{ asset('storage/' . $pendaftaran->$field) }}'"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-1">
                                                <i data-lucide="eye" class="w-3 h-3"></i> Lihat file
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400 mt-1 block">Tidak ada file</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <template x-teleport="body">
                            <div x-show="previewOpen" x-transition.opacity x-cloak
                                class="fixed inset-0 z-[99999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
                                @click.self="previewOpen = false">
                                <div x-show="previewOpen" class="relative bg-white w-full max-w-6xl h-[92vh] rounded-3xl shadow-2xl overflow-hidden">
                                    <div class="flex items-center justify-between px-6 py-4 border-b bg-white">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">Preview Dokumen</h3>
                                            <p class="text-sm text-gray-500">Klik area luar untuk menutup preview</p>
                                        </div>
                                        <button type="button" @click="previewOpen = false"
                                                class="w-10 h-10 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-md transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="w-full h-[calc(92vh-80px)] bg-gray-100 flex items-center justify-center overflow-auto">
                                        <template x-if="previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                            <div class="w-full h-full flex items-center justify-center p-4">
                                                <img :src="previewFile" class="max-w-full max-h-full object-contain rounded-xl shadow">
                                            </div>
                                        </template>
                                        <template x-if="!previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                            <iframe :src="previewFile" class="w-full h-full border-0 bg-white"></iframe>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                @endif

            </div>
        </div>
    @endforeach
</div>