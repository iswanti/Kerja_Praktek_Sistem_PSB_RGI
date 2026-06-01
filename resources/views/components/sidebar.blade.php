<div x-data="{ openMenus: {} }"
     x-init="
        @foreach ($sidebarMenus as $menu)
            @php
                $visibleChildren = $menu->children->filter(function ($child) {
                    return $child->permissions
                        ->where('role_id', auth()->user()->role_id)
                        ->where('can_read', true)
                        ->count() > 0;
                });

                $isActiveParent = $visibleChildren->contains(function ($child) {
                    return request()->routeIs($child->route);
                });
            @endphp

            @if ($visibleChildren->count() > 0 && $isActiveParent)
                openMenus['{{ $menu->id }}'] = true;
            @endif
        @endforeach
     "
     class="fixed top-0 left-0 h-screen flex z-50 transition-all duration-300">

    {{-- Sidebar Icon --}}
    <div id="sidebarIcons"
         class="w-20 bg-blue-500 h-screen flex flex-col items-center text-white z-[70] shrink-0 shadow-xl">

        <div class="h-16 mt-5 w-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition-colors"
             onclick="toggleSidebar()">
            <i data-lucide="menu"></i>
        </div>

        <div class="flex flex-col mt-10 w-full">

            @foreach ($sidebarMenus as $menu)

                @php
                    $visibleChildren = $menu->children->filter(function ($child) {
                        return $child->permissions
                            ->where('role_id', auth()->user()->role_id)
                            ->where('can_read', true)
                            ->count() > 0;
                    });

                    $isActiveParent = $visibleChildren->contains(function ($child) {
                        return request()->routeIs($child->route);
                    });
                @endphp

                @if ($visibleChildren->count() > 0)

                    {{-- Parent Icon --}}
                    <button type="button"
                            @click="openMenus['{{ $menu->id }}'] = !openMenus['{{ $menu->id }}']"
                            class="h-14 flex items-center justify-center w-full relative transition
                            {{ $isActiveParent ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10 border-l-4 border-transparent hover:border-white' }}">

                        <i data-lucide="{{ $menu->icon ?? 'circle' }}"
                           class="w-5 h-5 text-white"></i>
                    </button>

                    {{-- Child Icons --}}
                    @foreach ($visibleChildren as $child)
                        <a x-show="openMenus['{{ $menu->id }}']"
                           x-transition
                           href="{{ route($child->route) }}"
                           class="h-12 flex items-center justify-center w-full relative transition
                           {{ request()->routeIs($child->route)
                                ? 'border-l-4 border-white bg-white/10'
                                : 'border-l-4 border-transparent hover:border-white hover:bg-white/10' }}">

                            <i data-lucide="{{ $child->icon ?? 'circle' }}"
                               class="w-5 h-5 text-white"></i>
                        </a>
                    @endforeach

                @else

                    @if ($menu->route)
                        @php
                            $routeName = $menu->route;

                            if (
                                $menu->nama === 'Pendaftaran' &&
                                auth()->user()->canCreateMenu('Pendaftaran') &&
                                !auth()->user()->canReadMenu('Pendaftaran')
                            ) {
                                $routeName = 'pendaftaran.create';
                            }
                        @endphp

                        <a href="{{ route($routeName) }}"
                        class="h-14 flex items-center justify-center w-full relative transition
                        {{ request()->routeIs($routeName)
                                ? 'bg-white/20 border-l-4 border-white'
                                : 'hover:bg-white/10 border-l-4 border-transparent hover:border-white' }}">

                            <i data-lucide="{{ $menu->icon ?? 'circle' }}"
                            class="w-5 h-5 text-white"></i>
                        </a>
                    @endif

                @endif

            @endforeach

        </div>
    </div>

    {{-- Sidebar Text --}}
    <div id="sidebarText"
         :class="sidebarOpen ? 'w-64' : 'w-0'"
         class="bg-gradient-to-b from-blue-600 to-blue-800 text-white rounded-r-3xl h-screen transition-all duration-500 ease-in-out overflow-hidden z-[60]">

        {{-- Logo --}}
        <div class="h-16 mt-5 flex justify-center items-center">
            <img src="/images/sipensa.png"
                 class="h-16 object-contain"
                 alt="RGI">
        </div>

        {{-- Menu Text --}}
        <div class="pt-10 flex flex-col">

            @foreach ($sidebarMenus as $menu)

                @php
                    $visibleChildren = $menu->children->filter(function ($child) {
                        return $child->permissions
                            ->where('role_id', auth()->user()->role_id)
                            ->where('can_read', true)
                            ->count() > 0;
                    });

                    $isActiveParent = $visibleChildren->contains(function ($child) {
                        return request()->routeIs($child->route);
                    });
                @endphp

                @if ($visibleChildren->count() > 0)

                    {{-- Parent Text --}}
                    <button type="button"
                            @click="openMenus['{{ $menu->id }}'] = !openMenus['{{ $menu->id }}']"
                            class="w-full h-14 flex items-center justify-between ml-4 px-5 rounded-l-3xl transition font-semibold
                            {{ $isActiveParent ? 'bg-white/20 text-white' : 'text-white hover:bg-white/10' }}">

                        <span>{{ $menu->nama }}</span>

                        <i data-lucide="chevron-down"
                           class="w-4 h-4 mr-5 transition-transform"
                           :class="openMenus['{{ $menu->id }}'] ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Children Text --}}
                    <div x-show="openMenus['{{ $menu->id }}']"
                         x-transition
                         class="flex flex-col">

                        @foreach ($visibleChildren as $child)
                            <a href="{{ route($child->route) }}"
                               class="h-11 flex items-center ml-8 px-5 rounded-l-3xl transition text-sm
                               {{ request()->routeIs($child->route)
                                    ? 'bg-white text-blue-600 font-semibold'
                                    : 'text-white hover:bg-white/10' }}">

                                {{ $child->nama }}
                            </a>
                        @endforeach

                    </div>

                @else

                    @if ($menu->route)
                        @php
                            $routeName = $menu->route;

                            if (
                                $menu->nama === 'Pendaftaran' &&
                                auth()->user()->canCreateMenu('Pendaftaran') &&
                                !auth()->user()->canReadMenu('Pendaftaran')
                            ) {
                                $routeName = 'pendaftaran.create';
                            }
                        @endphp

                        <a href="{{ route($routeName) }}"
                        class="h-14 flex items-center ml-4 px-5 rounded-l-3xl transition
                        {{ request()->routeIs($routeName)
                                ? 'bg-white text-blue-600 font-semibold'
                                : 'text-white hover:bg-white/10' }}">

                            {{ $menu->nama }}
                        </a>
                    @endif

                @endif

            @endforeach

        </div>
    </div>
</div>