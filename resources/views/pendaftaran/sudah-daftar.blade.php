<x-app-layout>
    <div class="w-full mx-auto py-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-5">
                <i data-lucide="info" class="w-10 h-10 text-yellow-600"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                Pendaftaran Sudah Dilakukan
            </h2>

            <p class="text-gray-600 mt-3">
                Anda sudah pernah mengisi formulir pendaftaran.
                Saat ini pendaftaran Anda sedang berada pada tahap
                <span class="font-semibold text-blue-600">
                    {{ ucwords(str_replace('_', ' ', $sudahDaftar->status)) }}
                </span>.
            </p>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 ">
                <p class="text-sm text-blue-700">
                    Kode Pendaftaran:
                    <span class="font-bold">{{ $sudahDaftar->kode_pendaftaran }}</span>
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>