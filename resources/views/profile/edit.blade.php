<x-app-layout>
    <div class="py-8">
        <div class="w-full mx-auto">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Cover --}}
                <div class="h-56 relative bg-cover bg-center" style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
                    <div class="absolute -bottom-24 left-10 flex items-end gap-8">
                        <div class="relative group w-40 h-40">
                            <div class="w-40 h-40 bg-gray-300 border-4 border-white shadow rounded-sm overflow-hidden">

                                @if($pendaftaran && $pendaftaran->pas_foto)
                                    <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}"
                                        alt="Pas Foto"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-white">
                                        <i data-lucide="user" class="w-16 h-16"></i>
                                    </div>
                                @endif

                            </div>

                            {{-- Overlay --}}
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <form id="photoForm"
                                    action="{{ route('profile.photo.update') }}"
                                    method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type="file"
                                        id="photoInput"
                                        name="pas_foto"
                                        accept="image/*"
                                        class="hidden"
                                        onchange="document.getElementById('photoForm').submit()">
                                </form>
                                <button type="button"
                                        onclick="document.getElementById('photoInput').click()"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-800 rounded-lg text-sm font-medium shadow">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                    Edit Foto
                                </button>

                            </div>
                        </div>

                        <div class="pb-10">
                            <h2 class="text-2xl font-extrabold text-gray-900">
                                {{ $pendaftaran->nama_lengkap ?? $user->name }}
                            </h2>
                            <p class="text-sm text-gray-700">
                                {{ $pendaftaran->nomor_pendaftaran ?? $user->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-32 px-10 pb-10">

                    <div class="border border-gray-100 rounded-xl p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 text-sm">

                            <div class="text-gray-600">Jurusan</div>
                            <div class="font-semibold text-gray-900">
                                {{ $pendaftaran?->jurusan?->nama_jurusan ?? '-' }}
                            </div>

                            <div class="text-gray-600">Cabang</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran?->cabang?->nama_cabang ?? '-' }}
                            </div>

                            <div class="text-gray-600">NIK</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran->nik ?? '-' }}
                            </div>

                            <div class="text-gray-600">NKK</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran->nkk ?? '-' }}
                            </div>

                            <div class="text-gray-600">No Telephone</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran->no_hp ?? $user->phone?? '-' }}
                            </div>

                            <div class="text-gray-600">Tempat Tanggal Lahir</div>
                            <div class="text-gray-900">
                                @if($pendaftaran)
                                    {{ $pendaftaran->tempat_lahir ?? '-' }},
                                    {{ $pendaftaran->tgl_lahir ?? '-' }}
                                @else
                                    -
                                @endif
                            </div>

                            <div class="text-gray-600">Usia</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran->umur ?? '-' }}
                            </div>

                            <div class="text-gray-600">Alamat</div>
                            <div class="text-gray-900">
                                {{ $pendaftaran->alamat ?? '-' }}
                            </div>

                        </div>
                    </div>

                    @if(!$pendaftaran)
                        <div class="mt-6 p-4 rounded-xl bg-blue-50 text-blue-700 text-sm">
                            Anda belum melakukan pendaftaran. Data profil sementara diambil dari akun pengguna.
                        </div>
                    @endif

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}"
                           class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold">
                            Kembali
                        </a>

                        @if(!$pendaftaran)
                            <a href="{{ route('pendaftaran.create') }}"
                               class="px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold">
                                Daftar Sekarang
                            </a>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>