<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    {{-- <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body> --}}

    <body class="font-sans antialiased bg-gray-100">
        <div x-data="{ sidebarOpen: true }" class="flex">

            @include('components.sidebar')

            <div id="mainContent" 
                :class="sidebarOpen ? 'ml-[336px]' : 'ml-[80px]'" 
                class="p-9 w-full min-h-screen transition-all duration-300 ease-in-out z-10">
                
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

    </body>
</html>
