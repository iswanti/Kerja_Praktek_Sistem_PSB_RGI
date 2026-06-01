<div class="fixed top-0 left-0 h-screen flex z-50 transition-all duration-300">

    {{-- Sidebar Icon --}}
    <div id="sidebarIcons" class="w-20 bg-blue-500 h-screen flex flex-col items-center text-white z-[70] shrink-0 shadow-xl">
        <div class="h-16 mt-5 w-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition-colors" onclick="toggleSidebar()">
            <i data-lucide="menu"></i>
        </div>

        <div class="flex flex-col space-y-6 mt-10">

            {{-- Dashboard --}}
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
               class="h-12 flex items-center justify-center px-6 w-full transition relative
               {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                <i data-lucide="layout-dashboard" class="text-white"></i>
            </a>

            @if(Auth::user()->role === 'admin')
                {{-- Admin: Data Pendaftaran --}}
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('admin.pendaftaran.*') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="file-text" class="text-white"></i>
                </a>

                {{-- Admin: Wawancara --}}
                <a href="{{ route('admin.wawancara.index') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('admin.wawancara.*') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="messages-square" class="text-white"></i>
                </a>

                {{-- Admin: Kelola Soal --}}
                <a href="{{ route('admin.soal.index') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('admin.soal.*') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="clipboard-list" class="text-white"></i>
                </a>

                {{-- Admin: Pengumuman --}}
                <a href="{{ route('admin.pengumuman.index') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('admin.pengumuman.*') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="megaphone" class="text-white"></i>
                </a>
            @else
                {{-- Siswa: Form Daftar --}}
                <a href="{{ route('pendaftaran.create') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('pendaftaran.create') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="file-plus" class="text-white"></i>
                </a>

                {{-- Siswa: Seleksi --}}
                <a href="{{ route('seleksi.index') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('seleksi.*') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="check-circle" class="text-white"></i>
                </a>

                {{-- Siswa: Pengumuman --}}
                <a href="{{ route('pengumuman.publik') }}"
                   class="h-12 flex items-center justify-center px-6 w-full transition relative
                   {{ request()->routeIs('pengumuman.publik') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}">
                    <i data-lucide="megaphone" class="text-white"></i>
                </a>
            @endif

        </div>
    </div>

    {{-- Sidebar Text --}}
    <div id="sidebarText"
        :class="sidebarOpen ? 'w-64' : 'w-0'"
        class="bg-gradient-to-b from-blue-600 to-blue-800 text-white rounded-r-3xl h-screen transition-all duration-500 ease-in-out overflow-hidden z-[60]">

        <div class="h-16 mt-5 flex justify-center items-center">
            <img src="/images/sipensa.png" class="h-16 object-contain" alt="RGI">
        </div>

        <div class="pt-10 flex flex-col space-y-6">

            {{-- Dashboard --}}
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
               class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
               {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                Dashboard
            </a>
            

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Data Pendaftaran
                </a>

                <a href="{{ route('admin.wawancara.index') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('admin.wawancara.*') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Wawancara
                </a>

                <a href="{{ route('admin.soal.index') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('admin.soal.*') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Kelola Soal
                </a>

                <a href="{{ route('admin.pengumuman.index') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('admin.pengumuman.*') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Pengumuman
                </a>
            @else
                <a href="{{ route('pendaftaran.create') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('pendaftaran.create') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Form Pendaftaran
                </a>

                <a href="{{ route('seleksi.index') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('seleksi.*') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Seleksi
                </a>

                <a href="{{ route('pengumuman.publik') }}"
                   class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition
                   {{ request()->routeIs('pengumuman.publik') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}">
                    Pengumuman
                </a>
            @endif

        </div>
    </div>
</div>