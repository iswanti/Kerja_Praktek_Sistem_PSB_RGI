<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white p-8 rounded-lg shadow">

            {{-- Header --}}
            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="key-round" class="w-7 h-7"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ubah Password</h1>
                    <p class="text-gray-600 mt-1">
                        Perbarui password untuk user
                        <span class="font-semibold text-blue-600">{{ $user->name }}</span>
                    </p>
                </div>
            </div>

            <form id="passwordUserForm" action="{{ route('admin.users.password.update', $user->id) }}" method="POST" class="border border-gray-200 rounded-2xl p-8">
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    <div>
                        <label class="block text-sm mb-2"> Password Baru</label>
                        <div x-data="{ show: false }" class="relative">
                            {{-- Input --}}
                            <input :type="show ? 'text' : 'password'" name="password" class="w-full rounded-xl border-gray-300 px-4 py-3 pr-12 focus:ring-blue-200 focus:border-blue-500" placeholder="Masukkan password baru">

                            {{-- Toggle --}}
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                                {{-- Eye --}}
                                <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                                {{-- Eye Off --}}
                                <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-2">Konfirmasi Password Baru</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full rounded-xl border-gray-300 px-4 py-3 pr-12 focus:ring-blue-200 focus:border-blue-500" placeholder="Ulangi password baru">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                                <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                                <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <button type="button"
                            onclick="confirmCancel('{{ route('admin.users.index') }}')"
                            class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Batal
                    </button>

                    <button type="button"
                            onclick="confirmSubmit('passwordUserForm', 'Yakin ingin mengubah password user ini?')"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold">
                        Update Password
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>