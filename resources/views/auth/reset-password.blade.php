<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">

        <!-- Logo -->
        <div class="flex justify-center items-center gap-4 mb-4">
            <img src="/images/logo.jpeg" class="h-10" alt="RGI">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-blue-600">Reset Password</h2>
        <p class="text-gray-500 text-sm mb-6">Masukkan password baru Anda di bawah ini</p>

        <form method="POST" action="{{ route('password.store') }}" class="text-left">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}"
                    required autofocus autocomplete="username"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500"/>
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-700">Password Baru</label>
                <div x-data="{ show: false }" class="relative mt-1">
                    <input :type="show ? 'text' : 'password'" name="password"
                        required autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                        <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                        <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500"/>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                <div x-data="{ show: false }" class="relative mt-1">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation"
                        required autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                        <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                        <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500"/>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                Reset Password
            </button>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-400 mt-6">
                © 2026 Rumah Gemilang Indonesia
            </p>
        </form>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
            document.addEventListener('click', function () {
                lucide.createIcons();
            });
        });
    </script>
</x-guest-layout>