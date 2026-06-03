<x-app-layout>
    <div class="w-full mx-auto py-5 ">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

            <!-- Page Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-6">
                <h1 class="text-2xl font-bold text-white tracking-tight">Edit Pendaftaran</h1>
                <p class="text-blue-100 text-sm mt-1">Perbarui data pendaftaran Anda dengan benar dan lengkap</p>
            </div>

            <div class="p-8">
                {{-- <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data"> --}}
                <form action="{{ route('admin.pendaftaran.update', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 flex gap-3">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <ul class="list-disc pl-4 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    @if(session('kode'))
                    <script>
                        Swal.fire({
                            title: 'Pendaftaran Berhasil!',
                            html: 'Kode Pendaftaran Anda:<br><b>{{ session("kode") }}</b>',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>
                    @endif

                    <div class="space-y-10">

                        <!-- Section: Kampus dan Jurusan -->
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Pilih Kampus dan Jurusan</h2>
                                    <p class="text-sm text-gray-500">Pilih lokasi kampus tujuan dan program keahlian yang diminati.</p>
                                </div>
                            </div>

                            <div x-data="kampusJurusan()" x-init='initData(@json($cabangs),@js(old("cabang_id", $pendaftaran->cabang_id)),@js(old("jurusan_id", $pendaftaran->jurusan_id)) )' class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kampus <span class="text-red-500">*</span></label>
                                    <select x-model="kampus" @change="updateJurusan()" name="cabang_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 text-sm py-2.5">
                                        <option value="">Pilih Kampus</option>
                                        <template x-for="c in cabangs" :key="c.id">
                                            <option :value="c.id" :selected="c.id == '{{ old('cabang_id', $pendaftaran->cabang_id) }}'"  x-text="c.nama_cabang"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                                    <select name="jurusan_id" x-model="jurusan" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 text-sm py-2.5 disabled:bg-gray-50 disabled:text-gray-400" :disabled="!kampus">
                                        <option value="">Pilih Jurusan</option>
                                        <template x-for="j in jurusanList" :key="j.id">
                                            <option :value="j.id" :selected="j.id == '{{ old('jurusan_id', $pendaftaran->jurusan_id) }}'" x-text="j.nama_jurusan"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Section: Informasi Data Diri -->
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Informasi Data Diri</h2>
                                    <p class="text-sm text-gray-500">Isi data diri sesuai dengan dokumen identitas resmi Anda.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Induk Kependudukan <span class="text-red-500">*</span></label>
                                    <input type="text" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nik', $pendaftaran->nik) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Kartu Keluarga <span class="text-red-500">*</span></label>
                                    <input type="text" name="nkk" maxlength="16" placeholder="Masukkan 16 digit NKK" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nkk', $pendaftaran->nkk) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama" placeholder="Nama sesuai KTP" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nama', $pendaftaran->nama) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                                    <input type="text" name="tempat_lahir" placeholder="Kota tempat lahir" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <input type="date" name="tgl_lahir" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('tgl_lahir', $pendaftaran->tgl_lahir) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Umur <span class="text-red-500">*</span></label>
                                    <input type="number" name="umur" placeholder="Usia saat ini" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('umur', $pendaftaran->umur) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <div class="mt-2 flex items-center gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input id="laki-laki" type="radio" name="jenis_kelamin" value="L" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">Laki-Laki</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input id="perempuan" type="radio" name="jenis_kelamin" value="P" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">Perempuan</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Anak Ke <span class="text-red-500">*</span></label>
                                    <input type="number" name="anak_ke" placeholder="Contoh: 1, 2, 3..." class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('anak_ke', $pendaftaran->anak_ke) }}">
                                </div>
                            </div>

                            <div class="mt-5">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="alamat" placeholder="Jalan, RT/RW, Nomor rumah..." class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('alamat', $pendaftaran->alamat) }}">
                            </div>

                            <!-- Wilayah -->
                            <div x-data="wilayah()" x-init="initWilayah('{{ old('id_alamat', $pendaftaran->id_alamat) }}')" class="mt-5 space-y-5">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi <span class="text-red-500">*</span></label>
                                        <select x-model="provinsi" @change="pilihProvinsi()" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                            <option value="">Pilih Provinsi</option>
                                            <template x-for="p in provinsis" :key="p.code">
                                                <option :value="String(p.code)" x-text="p.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kabupaten/Kota <span class="text-red-500">*</span></label>
                                        <select x-model="kabupaten" @change="pilihKabupaten()" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400" :disabled="!provinsi">
                                            <option value="">Pilih Kabupaten/Kota</option>
                                            <template x-for="k in kabupatens" :key="k.code">
                                                <option :value="String(k.code)" x-text="k.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                                        <select x-model="kecamatan" @change="pilihKecamatan()" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400" :disabled="!kabupaten">
                                            <option value="">Pilih Kecamatan</option>
                                            <template x-for="kec in kecamatans" :key="kec.code">
                                                 <option :value="String(kec.code).trim()" x-text="kec.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelurahan <span class="text-red-500">*</span></label>
                                        <select x-model="kelurahan" @change="pilihKelurahan()" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400" :disabled="!kecamatan">
                                            <option value="">Pilih Kelurahan</option>
                                            <template x-for="kel in kelurahans" :key="kel.code">
                                                <option :value="String(kel.code)" x-text="kel.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="provinsi_nama" :value="provinsi_nama">
                                    <input type="hidden" name="kabupaten_nama" :value="kabupaten_nama">
                                    <input type="hidden" name="kecamatan_nama" :value="kecamatan_nama">
                                    <input type="hidden" name="kelurahan_nama" :value="kelurahan_nama">
                                    <input type="hidden" name="id_alamat" :value="kelurahan">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                                    <select name="pendidikan" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Pendidikan Terakhir</option>
                                        <option value="Tidak Sekolah" {{ old('pendidikan', $pendaftaran->pendidikan) == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                        <option value="SD/MI" {{ old('pendidikan', $pendaftaran->pendidikan) == 'SD/MI' ? 'selected' : '' }}>SD / MI</option>
                                        <option value="SMP/MTs" {{ old('pendidikan', $pendaftaran->pendidikan) == 'SMP/MTs' ? 'selected' : '' }}>SMP / MTs</option>
                                        <option value="SMA/SMK/MA" {{ old('pendidikan', $pendaftaran->pendidikan) == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                                        <option value="D1" {{ old('pendidikan', $pendaftaran->pendidikan) == 'D1' ? 'selected' : '' }}>Diploma 1 (D1)</option>
                                        <option value="D2" {{ old('pendidikan', $pendaftaran->pendidikan) == 'D2' ? 'selected' : '' }}>Diploma 2 (D2)</option>
                                        <option value="D3" {{ old('pendidikan', $pendaftaran->pendidikan) == 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                        <option value="D4/S1" {{ old('pendidikan', $pendaftaran->pendidikan) == 'D4/S1' ? 'selected' : '' }}>Diploma 4 / S1</option>
                                        <option value="S2" {{ old('pendidikan', $pendaftaran->pendidikan) == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                        <option value="S3" {{ old('pendidikan', $pendaftaran->pendidikan) == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                                    <input type="text" name="sekolah" placeholder="Nama sekolah terakhir" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('sekolah', $pendaftaran->sekolah) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cita-Cita <span class="text-red-500">*</span></label>
                                    <input type="text" name="cita_cita" placeholder="Apa cita-cita Anda?" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('cita_cita', $pendaftaran->cita_cita) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Handphone / WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="number" name="no_hp" maxlength="13" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('no_hp', $pendaftaran->no_hp) }}">
                                </div>

                                @php
                                    $opsiHobi = ['Membaca', 'Olahraga', 'Musik', 'Menulis', 'Traveling'];
                                    $hobiDb = old('hobi', $pendaftaran->hobi ?? []);
                                    if (!is_array($hobiDb)) {
                                        $hobiDb = explode(',', $hobiDb);
                                    }
                                    $hobiDb = array_map('trim', $hobiDb);
                                    $hobiUtama = array_values(array_intersect($hobiDb, $opsiHobi));
                                    $hobiLainnya = collect($hobiDb)
                                        ->filter(fn($item) => !in_array($item, $opsiHobi) && $item !== '')
                                        ->first();
                                    if ($hobiLainnya) {
                                        $hobiUtama[] = 'Lainnya';
                                    }
                                @endphp

                                <div x-data="{ hobi: @js($hobiUtama), lainnya: @js(old('hobi_lainnya', $hobiLainnya))}">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hobi <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['Membaca', 'Olahraga', 'Musik', 'Menulis', 'Traveling', 'Lainnya'] as $h)
                                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                            <input type="checkbox" value="{{ $h }}" x-model="hobi" name="hobi[]" class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                                            {{ $h }}
                                        </label>
                                        @endforeach
                                    </div>
                                    <div x-show="hobi.includes('Lainnya')" class="mt-3" x-transition>
                                        <input type="text" name="hobi_lainnya" placeholder="Sebutkan hobi lainnya" class="w-full border-gray-300 rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" x-model="lainnya">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Penyakit Yang Pernah Diderita</label>
                                    <input type="text" name="penyakit" placeholder="Jika ada, sebutkan penyakitnya" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('penyakit', $pendaftaran->penyakit) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Media Sosial -->
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Akun Media Sosial</h2>
                                    <p class="text-sm text-gray-500">ID Sosial media, contoh: www.facebook.com/rumahgemilang, @rumahgemilang</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Facebook</label>
                                    <input type="text" name="facebook" placeholder="URL atau nama akun Facebook" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('facebook', $pendaftaran->facebook) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Instagram</label>
                                    <input type="text" name="instagram" placeholder="@username Instagram" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('instagram', $pendaftaran->instagram) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Orang Tua/Wali -->
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Data Orang Tua atau Wali</h2>
                                    <p class="text-sm text-gray-500">Informasi data orang tua atau wali yang dapat dihubungi.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ayah / Wali</label>
                                    <input type="text" name="nama_wali" placeholder="Nama lengkap ayah/wali" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nama_wali', $pendaftaran->nama_wali) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                                    <select name="pendidikan_wali" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Pendidikan Terakhir</option>
                                        <option value="Tidak Sekolah" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                        <option value="SD/MI" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'SD/MI' ? 'selected' : '' }}>SD / MI</option>
                                        <option value="SMP/MTs" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'SMP/MTs' ? 'selected' : '' }}>SMP / MTs</option>
                                        <option value="SMA/SMK/MA" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                                        <option value="D1" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'D1' ? 'selected' : '' }}>Diploma 1 (D1)</option>
                                        <option value="D2" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'D2' ? 'selected' : '' }}>Diploma 2 (D2)</option>
                                        <option value="D3" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                        <option value="D4/S1" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'D4/S1' ? 'selected' : '' }}>Diploma 4 / S1</option>
                                        <option value="S2" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                        <option value="S3" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                    </select>
                                </div>

                                @php
                                    $opsiPekerjaan = ['PNS', 'Pegawai Swasta', 'Wiraswasta', 'Petani', 'Buruh', 'Tidak Bekerja'];

                                    $pekerjaanWaliDb = old('pekerjaan_wali', $pendaftaran->pekerjaan_wali ?? '');

                                    if (in_array($pekerjaanWaliDb, $opsiPekerjaan)) {
                                        $pekerjaanWali = $pekerjaanWaliDb;
                                        $pekerjaanWaliLainnya = old('pekerjaan_wali_lainnya', '');
                                    } else {
                                        $pekerjaanWali = 'Lainnya';
                                        $pekerjaanWaliLainnya = old('pekerjaan_wali_lainnya', $pekerjaanWaliDb);
                                    }
                                @endphp
                                <div x-data="{ pekerjaan_wali: @js($pekerjaanWali) }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                                    <select name="pekerjaan_wali" x-model="pekerjaan_wali" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Pekerjaan</option>
                                        <option value="PNS">PNS</option>
                                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Buruh">Buruh</option>
                                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div x-show="pekerjaan_wali === 'Lainnya'" x-transition>
                                        <input type="text" name="pekerjaan_wali_lainnya" placeholder="Sebutkan pekerjaan lainnya..." class="mt-3 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ $pekerjaanWaliLainnya }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon/HP Ayah/Wali</label>
                                    <input type="number" name="nohp_wali" maxlength="13" placeholder="Nomor HP ayah/wali" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nohp_wali', $pendaftaran->nohp_wali) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" placeholder="Nama lengkap ibu" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                                    <select name="pendidikan_ibu" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Pendidikan Terakhir</option>
                                        <option value="Tidak Sekolah" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                        <option value="SD/MI" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'SD/MI' ? 'selected' : '' }}>SD / MI</option>
                                        <option value="SMP/MTs" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'SMP/MTs' ? 'selected' : '' }}>SMP / MTs</option>
                                        <option value="SMA/SMK/MA" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                                        <option value="D1" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'D1' ? 'selected' : '' }}>Diploma 1 (D1)</option>
                                        <option value="D2" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'D2' ? 'selected' : '' }}>Diploma 2 (D2)</option>
                                        <option value="D3" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                        <option value="D4/S1" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'D4/S1' ? 'selected' : '' }}>Diploma 4 / S1</option>
                                        <option value="S2" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                        <option value="S3" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                    </select>
                                </div>
                                @php
                                    $opsiPekerjaan = ['PNS', 'Pegawai Swasta', 'Wiraswasta', 'Petani', 'Buruh', 'Tidak Bekerja'];

                                    $pekerjaanIbuDb = old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu ?? '');

                                    if (in_array($pekerjaanIbuDb, $opsiPekerjaan)) {
                                        $pekerjaanIbu = $pekerjaanIbuDb;
                                        $pekerjaanIbuLainnya = old('pekerjaan_ibu_lainnya', '');
                                    } else {
                                        $pekerjaanIbu = 'Lainnya';
                                        $pekerjaanIbuLainnya = old('pekerjaan_ibu_lainnya', $pekerjaanIbuDb);
                                    }
                                @endphp
                                <div x-data="{ pekerjaan_ibu: @js($pekerjaanIbu) }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan Ibu</label>
                                    <select name="pekerjaan_ibu" x-model="pekerjaan_ibu" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Pekerjaan</option>
                                        <option value="PNS">PNS</option>
                                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Buruh">Buruh</option>
                                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div x-show="pekerjaan_ibu === 'Lainnya'" x-transition>
                                        <input type="text" name="pekerjaan_ibu_lainnya" placeholder="Sebutkan pekerjaan lainnya..." class="mt-3 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ $pekerjaanIbuLainnya }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon/HP Ibu</label>
                                    <input type="number" name="nohp_ibu" maxlength="13" placeholder="Nomor HP ibu" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('nohp_ibu', $pendaftaran->nohp_ibu) }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap Orang Tua</label>
                                    <textarea name="alamat_orangtua" rows="2" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" placeholder="Masukkan alamat lengkap orang tua di sini...">{{ old('alamat_orangtua',$pendaftaran->alamat_orangtua) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Anggota Keluarga</label>
                                    <input type="number" name="jml_keluarga" placeholder="Jumlah anggota keluarga" class="mt-0 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" value="{{ old('jml_keluarga', $pendaftaran->jml_keluarga) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Rata-rata Pendapatan Keluarga Setiap Bulan</label>
                                    <select name="pendapatan_keluarga" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                        <option value="">Pilih Rata-rata Pendapatan</option>
                                        <option value="Rp 500.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 500.000' ? 'selected' : '' }}>< Rp 500.000</option>
                                        <option value="Rp 501.000 - Rp 1.000.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 501.000 - Rp 1.000.000' ? 'selected' : '' }}>Rp 501.000 - Rp 1.000.000</option>
                                        <option value="Rp 1.000.001 - Rp 1.500.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 1.000.001 - Rp 1.500.000' ? 'selected' : '' }}>Rp 1.000.001 - Rp 1.500.000</option>
                                        <option value="Rp 1.500.001 - Rp 2.000.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 1.500.001 - Rp 2.000.000' ? 'selected' : '' }}>Rp 1.500.001 - Rp 2.000.000</option>
                                        <option value="Rp 2.000.001 - Rp 2.500.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 2.000.001 - Rp 2.500.000' ? 'selected' : '' }}>Rp 2.000.001 - Rp 2.500.000</option>
                                        <option value="Rp 2.500.001 - Rp 3.000.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 2.500.001 - Rp 3.000.000' ? 'selected' : '' }}>Rp 2.500.001 - Rp 3.000.000</option>
                                        <option value="Rp 3.000.000" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'Rp 3.000.000' ? 'selected' : '' }}>> Rp 3.000.000</option>
                                        <option value="UMR/UMP" {{ old('pendapatan_keluarga', $pendaftaran->pendapatan_keluarga) == 'UMR/UMP' ? 'selected' : '' }}>>= UMR/UMP</option>
                                    </select>
                                </div>

                                @php
                                    $opsiStatusRumah = ['milik_sendiri', 'sewa', 'milik_kerabat'];
                                    $statusRumahDb = old('status_rumah', $pendaftaran->status_rumah ?? '');
                                    if (in_array($statusRumahDb, $opsiStatusRumah)) {
                                        $statusRumah = $statusRumahDb;
                                        $statusRumahLainnya = old('status_lainnya', '');
                                    } else {
                                        $statusRumah = 'lainnya';
                                        $statusRumahLainnya = old('status_lainnya', $statusRumahDb);
                                    }
                                @endphp

                                <div x-data="{ status: @js($statusRumah), other: @js($statusRumahLainnya) }" class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Status Rumah <span class="text-red-500">*</span></label>
                                    @foreach(['milik_sendiri' => 'Milik Sendiri', 'sewa' => 'Sewa', 'milik_kerabat' => 'Milik Kerabat', 'lainnya' => 'Lainnya'] as $val => $label)
                                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                        <input type="radio" name="status_rumah" value="{{ $val }}" x-model="status" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        {{ $label }}
                                    </label>
                                    @endforeach
                                    <div x-show="status === 'lainnya'" x-transition>
                                        <input type="text" name="status_lainnya" x-model="other" placeholder="Masukkan status rumah..." class="mt-1 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Lain-lain -->
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Lain-lain</h2>
                                    <p class="text-sm text-gray-500">Masukkan motivasi dan rekomendasi pengenalan Rumah Gemilang Indonesia.</p>
                                </div>
                            </div>

                            <div class="space-y-5 mt-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Motivasi yang mendorong Anda mengikuti pelatihan keterampilan di RGI adalah:</label>
                                    <textarea name="motivasi" rows="3" placeholder="Tuliskan motivasi Anda di sini..." class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">{{ old('motivasi',$pendaftaran->motivasi) }}</textarea>
                                </div>
                                @php
                                    $opsiPengenalan = ['Social Media', 'Brosur', 'Spanduk', 'Pengurus DKM Masjid', 'Kerabat/Saudara'];
                                    $pengenalanDb = old('pengenalan', $pendaftaran->pengenalan ?? []);
                                    if (!is_array($pengenalanDb)) {
                                        $pengenalanDb = explode(',', $pengenalanDb);
                                    }
                                    $pengenalanDb = array_map('trim', $pengenalanDb);
                                    $pengenalanUtama = array_values(array_intersect($pengenalanDb, $opsiPengenalan));
                                    $pengenalanLainnya = collect($pengenalanDb)
                                        ->filter(fn($item) => !in_array($item, $opsiPengenalan) && $item !== '')
                                        ->first();

                                    if ($pengenalanLainnya) {
                                        $pengenalanUtama[] = 'Lainnya';
                                    }
                                @endphp

                                <div x-data="{pengenalan: @js($pengenalanUtama), lainnya: @js(old('pengenalan_lainnya', $pengenalanLainnya)) }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pertama kali mengetahui Rumah Gemilang Indonesia (RGI) dari:</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach(['Social Media', 'Brosur', 'Spanduk', 'Pengurus DKM Masjid', 'Kerabat/Saudara', 'Lainnya'] as $p)
                                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                            <input type="checkbox" value="{{ $p }}" x-model="pengenalan" name="pengenalan[]" class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                                            {{ $p }}
                                        </label>
                                        @endforeach
                                    </div>
                                    <div x-show="pengenalan.includes('Lainnya')" class="mt-3" x-transition>
                                        <input type="text" name="pengenalan_lainnya" placeholder="Sebutkan pengenalan lainnya" class="w-full border-gray-300 rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" x-model="lainnya">
                                    </div>
                                </div>

                                @php
                                    $opsiAlasan = [
                                        'Kemauan Diri Sendiri', 'Dorongan Orang Tua/Keluarga', 'Dorongan Orang Lain'
                                    ];
                                    $alasanDb = old('alasan', $pendaftaran->alasan ?? '');
                                    if (in_array($alasanDb, $opsiAlasan)) {
                                        $alasanValue = $alasanDb;
                                        $alasanLainnya = old('alasan_lainnya', '');
                                    } else {
                                        $alasanValue = 'lainnya';
                                        $alasanLainnya = old('alasan_lainnya', $alasanDb);
                                    }
                                @endphp

                                <div x-data="{ status: @js($alasanValue), other: @js($alasanLainnya) }" class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Alasan memilih pendidikan keterampilan di RGI karena:</label>
                                    @foreach(['Kemauan Diri Sendiri', 'Dorongan Orang Tua/Keluarga', 'Dorongan Orang Lain'] as $al)
                                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                        <input type="radio" name="alasan" value="{{ $al }}" x-model="status" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        {{ $al }}
                                    </label>
                                    @endforeach
                                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                        <input type="radio" name="alasan" value="lainnya" x-model="status" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        Lainnya
                                    </label>
                                    <div x-show="status === 'lainnya'" x-transition>
                                        <input type="text" name="alasan_lainnya" x-model="other" placeholder="Masukkan alasan lainnya..." class="mt-1 w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pemberi Rekomendasi</label>
                                    <input type="text" name="rekomendasi" placeholder="Yang merekomendasikan Anda mendaftar ke Rumah Gemilang Indonesia?" class="w-full border-gray-300 rounded-xl shadow-sm text-sm py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-500" {{ old('rekomendasi',$pendaftaran->rekomendasi) }}>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Upload Dokumen -->
                        <div class="border-t border-gray-100 pt-8">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Upload Dokumen</h2>
                                    <p class="text-sm text-gray-500">Masukkan dokumen pendukung Anda di sini.</p>
                                </div>
                            </div>

                            <div x-data="{ previewOpen: false, previewFile: '' }">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                    @php
                                        $documents = [
                                            ['name' => 'pas_foto', 'label' => 'Pas Foto', 'required' => true],
                                            ['name' => 'foto_kk', 'label' => 'Kartu Keluarga', 'required' => true],
                                            ['name' => 'foto_ktp', 'label' => 'Kartu Tanda Penduduk (KTP)', 'required' => true],
                                            ['name' => 'foto_ijazah', 'label' => 'Ijazah Terakhir', 'required' => false],
                                            ['name' => 'sktm', 'label' => 'SKTM (Surat Keterangan Tidak Mampu) / Surat Rekomendasi DKM Masjid/Ponpes', 'required' => true],
                                            ['name' => 'surat_sehat', 'label' => 'Surat Keterangan Sehat', 'required' => true],
                                            ['name' => 'foto_rumah', 'label' => 'Foto Rumah ( Fotokan seluruh ruangan rumah dalam bentuk grid)', 'required' => true],
                                        ];
                                    @endphp

                                    @foreach($documents as $doc)
                                        @php
                                            $field = $doc['name'];
                                            $filePath = $pendaftaran->$field ?? null;
                                        @endphp

                                        <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:bg-blue-50/30 transition-colors">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $doc['label'] }}
                                            </label>

                                            <input 
                                                type="file" 
                                                name="{{ $doc['name'] }}"
                                                class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                                            >

                                            @if($filePath)
                                                <span class="text-gray-500">File saat ini:</span>
                                                <button type="button" @click=" previewOpen = true; previewFile = '{{ asset('storage/' . $filePath) }}'" class="mt-3 text-sm text-blue-600 hover:underline">
                                                    Lihat Dokumen
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Modal Preview -->
                                <template x-teleport="body">
                                    <div x-show="previewOpen" x-transition.opacity x-cloak class="fixed inset-0 z-[99999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" @click.self="previewOpen = false">
                                        <!-- Modal -->
                                        <div x-show="previewOpen" class="relative bg-white w-full max-w-6xl h-[92vh] rounded-3xl shadow-2xl overflow-hidden" >

                                            <!-- Header -->
                                            <div class="flex items-center justify-between px-6 py-4 border-b bg-white">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-800">
                                                        Preview Dokumen
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        Klik area luar untuk menutup preview
                                                    </p>
                                                </div>

                                                <button type="button" @click="previewOpen = false" class="w-10 h-10 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-md transition" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/> 
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Content -->
                                            <div class="w-full h-[calc(92vh-80px)] bg-gray-100 flex items-center justify-center overflow-auto">

                                                <!-- Preview Image -->
                                                <template x-if="previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                                    <div class="w-full h-full flex items-center justify-center p-4">
                                                        <img 
                                                            :src="previewFile"
                                                            class="max-w-full max-h-full object-contain rounded-xl shadow"
                                                        >
                                                    </div>
                                                </template>

                                                <!-- Preview PDF/File -->
                                                <template x-if="!previewFile.match(/\.(jpg|jpeg|png|webp)$/i)">
                                                    <iframe 
                                                        :src="previewFile"
                                                        class="w-full h-full border-0 bg-white"
                                                    ></iframe>
                                                </template>

                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
                            <p class="text-sm text-gray-400">Pastikan semua data sudah benar sebelum mengirim</p>
                            <button type="submit" class="px-10 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02] active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>