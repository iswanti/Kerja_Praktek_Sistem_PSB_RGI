<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">

        {{-- Logo --}}
        <div class="flex justify-center items-center gap-4 mb-4">
            <img src="/images/logo.jpeg" class="h-10" alt="RGI">
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-blue-600">
            Lupa Password
        </h2>

        <p class="text-gray-500 text-sm mb-6">
            Masukkan email akun Anda. Kami akan mengirimkan link reset password.
        </p>

        {{-- Session Status --}}
        <x-auth-session-status
            class="mb-4 text-sm text-green-600"
            :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="text-left">
            @csrf

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="text-sm font-semibold text-gray-700">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan email"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 border-0 focus:ring-2 focus:ring-blue-400">

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2 text-xs text-red-500" />
            </div>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                Kirim Link Reset Password
            </button>

            {{-- Back Login --}}
            <p class="text-center text-sm text-gray-500 mt-4">
                Ingat password?
                <a href="{{ route('login') }}"
                   class="text-blue-600 font-semibold hover:underline">
                    Kembali Login
                </a>
            </p>

            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 mt-4">
                © 2026 Rumah Gemilang Indonesia
            </p>
        </form>
    </div>
</x-guest-layout>