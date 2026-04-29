<x-guest-layout>
    <!-- Card -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">
            
        <!-- Logo -->
        <div class="flex justify-center items-center gap-4 mb-4">
            <img src="/images/logo.jpeg" class="h-10" alt="RGI">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-blue-600">Selamat Datang</h2>
        <p class="text-gray-500 text-sm mb-6">Buat Akun Sipensa RGI Mu Terlebih dahulu yu</p>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="text-left">
            @csrf

            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    {{-- <x-input-label for="name" :value="__('Name')" /> --}}
                    <label class="text-sm font-semibold text-gray-700" for ="name">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Nomor hp -->
                <div>
                    {{-- <x-input-label for="email" :value="__('Email')" /> --}}
                    <label class="text-sm font-semibold text-gray-700" for="phone">No Handphone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required autofocus autocomplete="phone" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>



            <!-- Email Address -->
            <div class="mt-4">
                {{-- <x-input-label for="email" :value="__('Email')" /> --}}
                <label class="text-sm font-semibold text-gray-700" for="email">Email</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Password -->
                <div class="mt-4">
                    {{-- <x-input-label for="password" :value="__('Password')" /> --}}
                    <label class="text-sm font-semibold text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required autofocus autocomplete="new-password" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    {{-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> --}}
                    <label class="text-sm font-semibold text-gray-700" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autofocus autocomplete="new-password" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <!-- Button -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
    
                <a href="{{ route('login') }}"
                    class="w-full sm:w-1/2 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                    Kembali
                </a>

                <button type="submit"
                    class="w-full sm:w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                    Daftar
                </button>

            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-400 mt-4">
                © 2026 Rumah Gemilang Indonesia
            </p>
        </form>
    </div>
</x-guest-layout>