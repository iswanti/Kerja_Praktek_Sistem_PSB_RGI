<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-5">
        <div class="bg-white p-8 rounded-lg shadow">
            <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 p-3 mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-2xl font-semibold text-gray-900">Pilih Kampus dan Jurusan</h2>
                        <p class="mt-1 text-sm text-gray-600">Pilih lokasi kampus tujuan dan program keahlian yang diminati.</p>

                        <!--Kampus-->
                        <div x-data="kampusJurusan()" x-init='initData(@json($cabangs))' class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-base font-medium text-gray-700">Kampus</label>
                                <select x-model="kampus" @change="updateJurusan()" name="cabang_id" class="w-full border-gray-300 rounded-md mt-1">
                                    <option value="">Pilih Kampus</option>
                                    <template x-for="c in cabangs" :key="c.id">
                                        <option :value="c.id" x-text="c.nama_cabang"></option>
                                    </template>
                                </select>
                            </div>

                            <!--Jurusan-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Jurusan</label>                  
                                <select name="jurusan_id" x-model="jurusan" class="w-full border-gray-300 rounded-md mt-1" :disabled="!kampus">
                                    <option value="">Pilih Jurusan</option>
                                    <template x-for="j in jurusanList" :key="j.id">
                                        <option :value="j.id" x-text="j.nama_jurusan"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <h2 class="text-2xl font-semibold text-gray-900 mt-10 border-t pt-10">Informasi Data Diri</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!--NIK-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Nomor Induk Kependudukan</label>
                                <input type="text" name="nik" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Nomor Kartu Keluarga-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Nomor Kartu Keluarga</label>
                                <input type="text" name="nkk" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Nama Lengkap-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="nama" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Tempat Lahir-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Tanggal Lahir-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Umur-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Umur</label>
                                <input type="number" name="umur" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!--Jenis Kelamin-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Jenis Kelamin</label>
                                <div class="mt-3 flex items-center gap-x-6">
                                    <div class="flex items-center gap-x-2">
                                        <input id="laki-laki" type="radio" name="jenis_kelamin" value="L" checked class="size-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="laki-laki" class="text-base font-medium text-gray-700">Laki-Laki</label>
                                    </div>
                                    <div class="flex items-center gap-x-2">
                                        <input id="perempuan" type="radio" name="jenis_kelamin" value="P" class="size-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="perempuan" class="text-base font-medium text-gray-700">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <!--Anak Ke-->
                            <div>
                                <label class="block text-base font-medium text-gray-700">Anak Ke</label>
                                <input type="number" name="anak_ke" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>
                        </div>


                        <!--Alamat-->
                        <div class="col-span-full mt-6">
                            <label class="block text-base font-medium text-gray-700">Alamat Lengkap</label>
                            <input type="text" name="alamat" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                        </div>


                        <div x-data="wilayah()" x-init="getProvinsi()" class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-base font-medium text-gray-700">Provinsi</label>
                                    <select x-model="provinsi" @change="getKabupaten()" name="provinsi_id" class="w-full border-gray-300 rounded-lg mt-1">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="p in provinsis" :key="p.id">
                                            <option :value="p.id" x-text="p.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-base font-medium text-gray-700">Kabupaten/Kota</label>                  
                                    <select x-model="kabupaten" @change="getKecamatan()" name="kabupaten_id" class="w-full border-gray-300 rounded-lg mt-1" :disabled="!provinsi">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <template x-for="k in kabupatens" :key="k.id">
                                            <option :value="k.id" x-text="k.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label class="block text-base font-medium text-gray-700">Kecamatan</label>                  
                                    <select x-model="kecamatan" @change="getKelurahan()" name="kecamatan_id" class="w-full border-gray-300 rounded-lg mt-1" :disabled="!kabupaten">
                                        <option value="">Pilih Kecamatan</option>
                                        <template x-for="kec in kecamatans" :key="kec.id">
                                            <option :value="kec.id" x-text="kec.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-base font-medium text-gray-700">Kelurahan</label>                  
                                    <select x-model="kelurahan" name="kelurahan_id" class="w-full border-gray-300 rounded-lg mt-1" :disabled="!kecamatan">
                                        <option value="">Pilih Kelurahan</option>
                                        <template x-for="kel in kelurahans" :key="kel.id">
                                            <option :value="kel.id" x-text="kel.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-base font-medium text-gray-700">Pendidikan Terakhir</label>
                                <select name="pendidikan" class="w-full border-gray-300 rounded-md mt-1">
                                    <option value="" >Pilih Pendidikan Terakhir</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-base font-medium text-gray-700">Nama Sekolah</label>
                                <input type="text" name="sekolah" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t">
                            <label class="block text-base font-medium text-gray-700">Upload Berkas (Kartu Keluarga/KTP)</label>
                            <input type="file" name="berkas" class="mt-2 w-full text-base text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="mt-10 flex justify-end border-t pt-6">
                            <button type="submit" class="px-10 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg transition-all transform hover:scale-105">
                                Kirim Pendaftaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>