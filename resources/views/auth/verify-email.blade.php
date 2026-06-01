<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center">

        <!-- Logo -->
        <div class="flex justify-center items-center gap-4 mb-4">
            <img src="/images/logo.jpeg" class="h-10" alt="RGI">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-blue-600">Verifikasi Email</h2>
        <p class="text-gray-500 text-sm mb-6">
            Terima kasih telah mendaftar! Silakan verifikasi email Anda dengan mengklik tautan yang telah kami kirimkan.
            Jika tidak menerima email, kami akan mengirimkan yang baru.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 rounded-xl px-4 py-3">
                Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="flex flex-col gap-3 mt-4">
            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-sm text-gray-500 hover:text-blue-600 py-2 transition">
                    Keluar
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6">
            © 2026 Rumah Gemilang Indonesia
        </p>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</x-guest-layout>