<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sipensa RGI') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo-rgi.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        
    </head>

    <body class="font-sans antialiased bg-gray-50">
        {{-- <div x-data="{ sidebarOpen: true }" class="flex">

            @include('components.sidebar')

            <div id="mainContent" 
                :class="sidebarOpen ? 'ml-[336px]' : 'ml-[80px]'" 
                class="px-4 sm:px-6 lg:px-9 py-6 w-full min-h-screen transition-all duration-300 ease-in-out z-10 overflow-x-hidden">
                
                @include('layouts.navigation')

                <div class="mt-4">
                    {{ $slot }}
                </div>
            </div>

        </div> --}}
        <div x-data="{sidebarOpen: window.innerWidth >= 1024,
            isMobile: window.innerWidth < 1024
        }" 
        x-init="
            $watch('isMobile', val => { if (val) sidebarOpen = false })
        "
        @resize.window="
            isMobile = window.innerWidth < 1024;
            sidebarOpen = window.innerWidth >= 1024;
        "
        class="flex">
            @include('components.sidebar')

            <div id="mainContent" 
                :class="isMobile ? 'ml-[80px]' : (sidebarOpen ? 'ml-[336px]' : 'ml-[80px]')" 
                class="px-4 sm:px-6 lg:px-9 py-6 w-full min-h-screen transition-all duration-300 ease-in-out z-10 overflow-x-hidden">
                
                @include('layouts.navigation')

                <div class="mt-4">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Lucide Icon -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
        <script src="{{ asset('js/sidebar.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3B82F6',
                    timer: 2500,
                    showConfirmButton: false
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#EF4444',
                });
            </script>
        @endif

        <script>

        // DELETE
        function confirmDelete(formId) {

            Swal.fire({
                title: 'Yakin?',
                text: 'Data akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }

            });

        }

        // SUBMIT
        function confirmSubmit(formId, message = 'Yakin Ingin menyimpan data?') {

            Swal.fire({
                title: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }

            });

        }

        // CANCEL
        function confirmCancel(url = null) {

            Swal.fire({
                title: 'Batalkan perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {

                if (result.isConfirmed) {

                    if (url) {
                        window.location.href = url;
                    } else {
                        window.history.back();
                    }

                }

            });

        }

        function confirmTolak() {
            Swal.fire({
                title: 'Alasan Penolakan',
                input: 'textarea',
                inputLabel: 'Masukkan alasan penolakan',
                inputPlaceholder: 'Contoh: Berkas tidak lengkap...',
                inputAttributes: {
                    'aria-label': 'Masukkan alasan penolakan'
                },

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',

                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#9CA3AF',

                inputValidator: (value) => {

                    if (!value) {
                        return 'Alasan penolakan wajib diisi!';
                    }

                }

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('alasan_ditolak').value = result.value;

                    document.getElementById('tolakForm').submit();

                }

            });
        }

        function confirmSetujui() {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                text: 'Peserta akan dipindahkan ke tahap terverifikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#16a34a'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('setujuiForm').submit();
                }
            });
        }

        function confirmSubmitPretest() {
            Swal.fire({
                title: 'Kumpulkan Pretest?',
                text: 'Pretest hanya dapat dikerjakan satu kali dan jawaban tidak dapat diubah kembali.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kumpulkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Mengirim Jawaban...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById('pretestForm').submit();
                }
            });
        }

        function confirmLanjut(label, formId) {
        Swal.fire({
            title: label + '?',
            text: `Yakin ingin mengubah status menjadi "${label}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }


    </script>

    </body>
</html>
