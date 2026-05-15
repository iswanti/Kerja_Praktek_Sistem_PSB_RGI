<nav x-data="{ open: false }"
    class="w-full bg-gradient-to-r bg-blue-500 shadow-md">

    

    <!-- Primary Navigation Menu -->
    <div class="w-full px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between items-center h-20">

           @php
                if (request()->routeIs('dashboard')) {
                    $menuName = 'Dashboard';
                } elseif (request()->routeIs('pendaftaran.*')) {
                    $menuName = 'Data Pendaftaran';
                } elseif (request()->routeIs('wawancara.*')) {
                    $menuName = 'Data Wawancara';
                } elseif (request()->routeIs('seleksi.*')) {
                    $menuName = 'Data Seleksi';
                } else {
                    $menuName = 'Pengumuman';
                }
            @endphp

            <!-- Judul Halaman -->
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-white">
                    {{ $menuName }}
                </h1>

                <!-- Breadcrumb -->
                <p class="text-sm text-blue-100 mt-1">
                    @if (request()->routeIs('pendaftaran.create'))
                        <a href="{{ route('pendaftaran.index') }}"
                        class="hover:underline">
                            Data Pendaftaran
                        </a>
                        <span class="mx-1">/</span>
                        <span>Tambah Data</span>

                    @elseif (request()->routeIs('pendaftaran.edit'))
                        <a href="{{ route('pendaftaran.index') }}"
                        class="hover:underline">
                            Data Pendaftaran
                        </a>
                        <span class="mx-1">/</span>
                        <span>Edit Data</span>

                    @elseif (request()->routeIs('pendaftaran.show'))
                        <a href="{{ route('pendaftaran.index') }}"
                        class="hover:underline">
                            Data Pendaftaran
                        </a>
                        <span class="mx-1">/</span>
                        <span>Detail Data</span>

                    @elseif (request()->routeIs('pendaftaran.index'))
                        <span>Data Pendaftaran</span>

                    @elseif (request()->routeIs('wawancara.*'))
                        <span>Data Wawancara</span>

                    @elseif (request()->routeIs('seleksi.*'))
                        <span>Data Seleksi</span>

                    @elseif (request()->routeIs('dashboard'))
                        <span>Dashboard</span>

                    @else
                        <span>Pengumuman</span>
                    @endif
                </p>
            </div>

            <!-- Right Menu -->
            <div class="hidden sm:flex sm:items-center space-x-4">

                <!-- Notifikasi -->
                <button
                    class="relative p-2 bg-white/20 hover:bg-white/30 rounded-full text-white transition">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full">
                        3
                    </span>
                </button>

                <!-- Dropdown Profile -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition text-white">

                            @if(Auth::user()->profile_photo_url)
                                <img class="h-9 w-9 rounded-full object-cover border-2 border-white"
                                    src="{{ Auth::user()->profile_photo_url }}"
                                    alt="Foto Profil">
                            @else
                                <div
                                    class="h-9 w-9 rounded-full bg-white flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                                </div>
                            @endif

                            <span class="font-medium text-sm">
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="fill-current h-4 w-4 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open"
                    class="text-white hover:text-blue-200 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none"
                        viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>