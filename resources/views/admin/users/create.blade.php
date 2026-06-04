<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            {{-- Header --}}
            <div class="flex items-center gap-6 pb-10 border-b">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="users" class="w-9 h-9"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah User</h1>
                    <p class="text-gray-600 mt-1">Tambahkan pengguna baru ke sistem</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.users.store') }}" method="POST"
                x-data="{
                    roleName: '{{ old('role_id') ? optional($roles->firstWhere('id', old('role_id')))->nama : '' }}',
                    unsurWawancara: '{{ old('unsur_wawancara') }}',
                    cabangId: '{{ old('cabang_id') }}',
                    jurusans: [],
                    loadingJurusan: false,

                    async fetchJurusan() {
                        if (!this.cabangId) {
                            this.jurusans = [];
                            return;
                        }
                        this.loadingJurusan = true;
                        try {
                            const res = await fetch(`/admin/jurusans-by-cabang/${this.cabangId}`);
                            this.jurusans = await res.json();
                        } catch (e) {
                            this.jurusans = [];
                        } finally {
                            this.loadingJurusan = false;
                        }
                    },

                    init() {
                        if (this.cabangId) this.fetchJurusan();
                    }
                }">
                @csrf

                <div class="grid grid-cols-2 gap-x-8 gap-y-5 mt-12">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm mb-2">Nama Pengguna</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm mb-2">Nomor Handphone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="14"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cabang --}}
                    <div>
                        <label class="block text-sm mb-2">Cabang</label>
                        <select name="cabang_id"
                                x-model="cabangId"
                                x-on:change="fetchJurusan()"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                        {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm mb-2">Kewenangan</label>
                        <select name="role_id"
                                x-on:change="
                                    roleName = $event.target.options[$event.target.selectedIndex].dataset.role;
                                    unsurWawancara = '';
                                "
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="" data-role="">Pilih Kewenangan</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                        data-role="{{ $role->nama }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unsur Wawancara (hanya Tim Wawancara) --}}
                    <div x-show="roleName === 'Tim Wawancara'" x-transition x-cloak>
                        <label class="block text-sm mb-2">Unsur Wawancara</label>
                        <select name="unsur_wawancara"
                                x-model="unsurWawancara"
                                :disabled="roleName !== 'Tim Wawancara'"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Unsur Wawancara</option>
                            <option value="operator"    {{ old('unsur_wawancara') == 'operator'    ? 'selected' : '' }}>Operator</option>
                            <option value="manajemen"   {{ old('unsur_wawancara') == 'manajemen'   ? 'selected' : '' }}>Manajemen</option>
                            <option value="scc_asrama"  {{ old('unsur_wawancara') == 'scc_asrama'  ? 'selected' : '' }}>SCC / Asrama</option>
                            <option value="instruktur"  {{ old('unsur_wawancara') == 'instruktur'  ? 'selected' : '' }}>Instruktur</option>
                        </select>
                        @error('unsur_wawancara')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jurusan (hanya instruktur) --}}
                    <div x-show="roleName === 'Tim Wawancara' && unsurWawancara === 'instruktur'" x-transition x-cloak>
                        <label class="block text-sm mb-2">Jurusan Instruktur</label>
                        <select name="jurusan_id"
                                :disabled="loadingJurusan"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                            <option value="" x-text="loadingJurusan 
                                ? 'Memuat...' 
                                : (!cabangId 
                                    ? 'Pilih cabang dulu' 
                                    : (jurusans.length === 0 ? 'Tidak ada jurusan' : 'Pilih Jurusan'))">
                            </option>

                            <template x-for="jurusan in jurusans" :key="jurusan.id">
                                <option :value="jurusan.id"
                                        :selected="String(jurusan.id) === '{{ old('jurusan_id') }}'"
                                        x-text="jurusan.nama_jurusan">
                                </option>
                            </template>

                        </select>

                        <p x-show="!cabangId" class="text-sm text-amber-500 mt-1">
                            Pilih cabang terlebih dahulu untuk melihat jurusan.
                        </p>

                        @error('jurusan_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm mb-2">Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password"
                                   autocomplete="new-password"
                                   class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                                <i data-lucide="eye"     x-show="!show"  class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="show"   x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500"/>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm mb-2">Konfirmasi Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                   autocomplete="new-password"
                                   class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                                <i data-lucide="eye"     x-show="!show"  class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="show"   x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500"/>
                    </div>

                    {{-- Is Active --}}
                    <div class="col-span-2">
                        <label class="block text-sm mb-3">Status Akun</label>
                        <label class="inline-flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', 1) == 1 ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                            <span>Aktif</span>
                        </label>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 mt-12">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Kembali
                    </a>
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</x-app-layout>