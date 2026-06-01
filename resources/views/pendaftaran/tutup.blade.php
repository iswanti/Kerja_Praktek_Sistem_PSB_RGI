<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

            <div class="w-20 h-20 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-5">
                <i data-lucide="lock" class="w-10 h-10 text-red-600"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                Pendaftaran Ditutup
            </h2>

            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Mohon maaf, saat ini periode pendaftaran belum dibuka atau sudah berakhir.
                Silakan menunggu informasi gelombang pendaftaran berikutnya.
            </p>

            <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-700">
                    Saat ini tidak ada gelombang pendaftaran yang aktif.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gray-700 hover:bg-gray-800 text-white font-semibold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</x-app-layout>