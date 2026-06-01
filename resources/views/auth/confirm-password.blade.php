<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">

        {{-- Logo --}}
        <div class="flex justify-center items-center gap-4 mb-4">
            <img src="/images/logo.jpeg" class="h-10" alt="RGI">
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-blue-600 mb-2">
            Konfirmasi Password
        </h2>

        {{-- Subtitle --}}
        <p class="text-gray-500 text-sm mb-6">
            This is a secure area of the application. Please confirm your password before continuing.
        </p>

        {{-- Session Status --}}
        <x-auth-session-status
            class="mb-4 text-sm text-green-600"
            :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm') }}" class="text-left space-y-5">
            @csrf

            {{-- Password --}}
            <div>
                <label for="password" class="text-sm font-semibold text-gray-700 mb-2 block">
                    Password
                </label>

                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan password"
                           class="w-full px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">

                    {{-- Toggle Show/Hide --}}
                    <button type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600">
                        <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                        <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500"/>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                Confirm
            </button>

            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 mt-4">
                © 2026 Rumah Gemilang Indonesia
            </p>
        </form>

    </div>

    {{-- Lucide icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
            document.addEventListener('click', function () { lucide.createIcons(); });
        });
    </script>
</x-guest-layout>