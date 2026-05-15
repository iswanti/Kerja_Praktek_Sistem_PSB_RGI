<div class="fixed top-0 left-0 h-screen flex z-50 transition-all duration-300">
    
    <div id="sidebarIcons" class="w-20 bg-blue-500 h-screen flex flex-col items-center text-white z-[70] shrink-0 shadow-xl">
        <div class="h-16 mt-5 w-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition-colors" onclick="toggleSidebar()">
            <i data-lucide="menu"></i>
        </div>
        
        <div class="flex flex-col space-y-6 mt-10">
            <a href="{{ route('dashboard') }}" class="h-12 flex items-center justify-center px-6 w-full transition relative {{ request()->routeIs('dashboard') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}"> <i data-lucide="layout-dashboard" class="text-white"></i> </a>
            <a href="{{ route('pendaftaran.index') }}" class="h-12 flex items-center justify-center px-6 w-full transition relative {{ request()->routeIs('pendaftaran') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}"> <i data-lucide="file-text" class="text-white"></i> </a>
            <a href="#" class="h-12 flex items-center justify-center px-6 w-full transition relative {{ request()->routeIs('seleksi') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}"> <i data-lucide="check-circle" class="text-white"></i> </a>
            <a href="#" class="h-12 flex items-center justify-center px-6 w-full transition relative {{ request()->routeIs('pengumuman') ? 'border-l-4 border-white' : 'border-l-4 border-transparent hover:border-white' }}"> <i data-lucide="megaphone" class="text-white"></i> </a>
        </div>
    </div>

    {{-- <div id="sidebarText" class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white rounded-r-3xl h-screen transition-all duration-500 ease-in-out transform z-[60] overflow-hidden"> --}}
    <div id="sidebarText" 
    :class="sidebarOpen ? 'w-64' : 'w-0'"
    class="bg-gradient-to-b from-blue-600 to-blue-800 text-white rounded-r-3xl h-screen transition-all duration-500 ease-in-out overflow-hidden z-[60]">
        
        <div class="h-16 mt-5 flex justify-center items-center">
            <img src="/images/sipensa.png" class="h-16 object-contain" alt="RGI">
        </div>

        <div class="pt-10 flex flex-col space-y-6">

            <a href="{{ route('dashboard') }}" class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}"> Dashboard </a>
            
            <a href="{{route('pendaftaran.index')}}" class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition {{ request()->routeIs('pendaftaran') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}"> Pendaftaran </a>
            
            <a href="#" class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition {{ request()->routeIs('seleksi') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}"> Seleksi </a>
            
            <a href="#" class="h-12 flex items-center ml-4 px-4 rounded-l-3xl transition {{ request()->routeIs('pengumuman') ? 'bg-gray-100 text-blue-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-blue-600' }}"> Pengumuman 
            </a>

        </div>
    </div>
</div>