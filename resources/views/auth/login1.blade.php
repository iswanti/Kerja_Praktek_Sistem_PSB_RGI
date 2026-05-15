<x-guest-layout>
        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">
            
            <!-- Logo -->
            <div class="flex justify-center items-center gap-4 mb-4">
                <img src="/images/logo.jpeg" class="h-10" alt="RGI">
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-blue-600">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-6">Masuk kedalam Akun PSB RGI Anda</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="text-left">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500"/>
                </div>

                <!-- Password -->
                {{-- <div class="mb-2">
                    <label class="text-sm font-semibold text-gray-700">Password</label>
                    <input type="password" name="password" required autocomplete="current-password" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500"/>
                </div> --}}
                <!-- Password -->
                <div class="mb-2">
                    <label class="text-sm font-semibold text-gray-700">Password</label>

                    <div x-data="{ show: false }" class="relative mt-1">
                        <!-- Input Password -->
                        <input :type="show ? 'text' : 'password'" name="password" require autocomplete="current-password" class="w-full px-4 py-3 pr-12 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">

                        <!-- Toggle Show/Hide Password -->
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-blue-600" >
                            <!-- Icon Eye -->
                            <i data-lucide="eye" x-show="!show" class="w-5 h-5"> </i>

                            <!-- Icon Eye Off -->
                            <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"> </i>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500"/>
                </div>

                <!-- Forgot -->
                <div class="text-right mb-4">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-blue-600">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                    Masuk
                </button>

                <!-- Register -->
                <p class="text-center text-sm text-gray-500 mt-4">
                    Belum Memiliki Akun ?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                        Registrasi disini
                    </a>
                </p>

                <!-- Footer -->
                <p class="text-center text-xs text-gray-400 mt-4">
                    © 2026 Rumah Gemilang Indonesia
                </p>
            </form>
        </div>
    </div>

    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            // Re-render icon setiap Alpine mengubah x-show
            document.addEventListener('click', function () {
                lucide.createIcons();
            });
        });
    </script>
    
</x-guest-layout>