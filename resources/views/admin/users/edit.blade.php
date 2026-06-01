<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            {{-- Header --}}
            <div class="flex items-center gap-6 pb-10 border-b">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="user-pen" class="w-9 h-9"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
                    <p class="text-gray-600 mt-1">Perbarui data pengguna sistem</p>
                </div>
            </div>

            <form id="editUserForm"
                  action="{{ route('admin.users.update', $user->id) }}"
                  method="POST"
                  x-data="{
                      cabangId: '{{ old('cabang_id', $user->cabang_id) }}',
                      roleName: '{{ old('role_id') ? optional($roles->firstWhere('id', old('role_id')))->nama : optional($user->role)->nama }}',
                      unsurWawancara: '{{ old('unsur_wawancara', $user->unsur_wawancara) }}',
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
                @method('PUT')

                <div class="grid grid-cols-2 gap-x-8 gap-y-5 mt-12">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm mb-2">Nama Pengguna</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm mb-2">Nomor Handphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" maxlength="14"
                               class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cabang — dipilih sebelum Role --}}
                    <div>
                        <label class="block text-sm mb-2">Cabang</label>
                        <select name="cabang_id"
                                x-model="cabangId"
                                x-on:change="fetchJurusan()"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                        {{ old('cabang_id', $user->cabang_id) == $cabang->id ? 'selected' : '' }}>
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
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unsur Wawancara --}}
                    <div x-show="roleName === 'Tim Wawancara'" x-transition x-cloak>
                        <label class="block text-sm mb-2">Unsur Wawancara</label>
                        <select name="unsur_wawancara"
                                x-model="unsurWawancara"
                                :disabled="roleName !== 'Tim Wawancara'"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500">
                            <option value="">Pilih Unsur Wawancara</option>
                            <option value="operator"   {{ old('unsur_wawancara', $user->unsur_wawancara) == 'operator'   ? 'selected' : '' }}>Operator</option>
                            <option value="manajemen"  {{ old('unsur_wawancara', $user->unsur_wawancara) == 'manajemen'  ? 'selected' : '' }}>Manajemen</option>
                            <option value="scc_asrama" {{ old('unsur_wawancara', $user->unsur_wawancara) == 'scc_asrama' ? 'selected' : '' }}>SCC / Asrama</option>
                            <option value="instruktur" {{ old('unsur_wawancara', $user->unsur_wawancara) == 'instruktur' ? 'selected' : '' }}>Instruktur</option>
                        </select>
                        @error('unsur_wawancara')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jurusan (by cabang, hanya instruktur) --}}
                    <div x-show="roleName === 'Tim Wawancara' && unsurWawancara === 'instruktur'" x-transition x-cloak>
                        <label class="block text-sm mb-2">Jurusan Instruktur</label>
                        <select name="jurusan_id"
                                :disabled="loadingJurusan"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:ring-blue-200 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400">

                            <option value="" x-text="loadingJurusan ? 'Memuat...' : (jurusans.length === 0 && cabangId ? 'Tidak ada jurusan' : 'Pilih Jurusan')"></option>

                            <template x-for="jurusan in jurusans" :key="jurusan.id">
                                <option :value="jurusan.id"
                                        :selected="jurusan.id == '{{ old('jurusan_id', $user->jurusan_id) }}'"
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

                    {{-- Is Active --}}
                    <div class="col-span-2">
                        <label class="block text-sm mb-3">Status Akun</label>
                        <label class="inline-flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                            <span>Aktif</span>
                        </label>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 mt-12">
                    <button type="button"
                            onclick="confirmCancel('{{ route('admin.users.index') }}')"
                            class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Batal
                    </button>

                    <button type="button"
                            onclick="confirmSubmit('editUserForm', 'Yakin ingin memperbarui data user?')"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</x-app-layout>