<nav x-data="{ open: false }"
    class="w-full bg-gradient-to-r bg-blue-500 shadow-md">

    

    <!-- Primary Navigation Menu -->
    <div class="w-full px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between items-center h-20">
           @php
                use App\Models\Menu;
                $routeName = request()->route()?->getName();
                $currentMenu = Menu::where('route', $routeName)->first();
                if (!$currentMenu && $routeName) {
                    $routeMap = [
                        'admin.pendaftaran.create' => 'admin.pendaftaran.index',
                        'admin.pendaftaran.edit'   => 'admin.pendaftaran.index',
                        'admin.pendaftaran.show'   => 'admin.pendaftaran.index',

                        'admin.alumni.create'      => 'admin.alumni.index',
                        'admin.alumni.edit'        => 'admin.alumni.index',

                        'admin.gelombang.create'   => 'admin.gelombang.index',
                        'admin.gelombang.edit'     => 'admin.gelombang.index',

                        'admin.users.create'       => 'admin.users.index',
                        'admin.users.edit'         => 'admin.users.index',

                        'admin.roles.create'       => 'admin.roles.index',
                        'admin.roles.edit'         => 'admin.roles.index',
                        'admin.roles.show'         => 'admin.roles.index',

                        'admin.soal.create'        => 'admin.soal.index',
                        'admin.soal.edit'          => 'admin.soal.index',

                        'admin.wawancara.show'     => 'admin.wawancara.index',
                        'admin.wawancara.edit'     => 'admin.wawancara.index',
                    ];

                    $parentRoute = $routeMap[$routeName] ?? null;

                    if ($parentRoute) {
                        $currentMenu = Menu::where('route', $parentRoute)->first();
                    }
                }

                $menuName = $currentMenu?->nama ?? 'Dashboard';
                $actionName = null;
                if (str_ends_with($routeName, '.create')) {
                    $actionName = 'Tambah Data';
                } elseif (str_ends_with($routeName, '.edit')) {
                    $actionName = 'Edit Data';
                } elseif (str_ends_with($routeName, '.show')) {
                    $actionName = 'Detail Data';
                }

                $breadcrumb = $actionName
                    ? $menuName . ' / ' . $actionName
                    : $menuName;

                if (request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')) {
                    $menuName = 'Dashboard';
                    $breadcrumb = 'Ringkasan data pendaftaran calon siswa berdasarkan cabang dan jurusan';
                }
            @endphp
            <!-- Judul Halaman -->
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-white">
                    {{ $menuName }}
                </h1>

                <p class="text-sm text-blue-100 mt-1">
                    {{ $breadcrumb }}
                </p>
            </div>

            <!-- Right Menu -->
            <div class="hidden sm:flex sm:items-center space-x-4">
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();

                    $notifications = auth()->user()
                        ->notifications()
                        ->latest()
                        ->take(10)
                        ->get();
                @endphp

                <x-dropdown align="right" width="80">

                    <x-slot name="trigger">
                        <button
                            class="relative p-2 bg-white/20 hover:bg-white/30 rounded-full text-white transition">
                            <i data-lucide="bell" class="w-5 h-5"></i>

                            @if($unreadCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <div class="w-80 max-h-96 overflow-y-auto bg-white">

                            <div class="px-4 py-3 border-b">
                                <h3 class="font-bold text-sm text-gray-800">
                                    Notifikasi
                                </h3>
                            </div>

                            @forelse($notifications as $notification)

                                <form method="POST"
                                    action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf

                                    <button type="submit"
                                            class="w-full text-left">

                                        <div class="px-4 py-3 border-b hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">

                                            <div class="flex gap-3">

                                                <div class="mt-1">
                                                    <i data-lucide="{{ $notification->data['icon'] ?? 'bell' }}"
                                                    class="w-5 h-5"></i>
                                                </div>

                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $notification->data['body'] ?? '-' }}
                                                    </p>

                                                    <p class="text-[11px] text-gray-400 mt-2">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </form>

                            @empty

                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    Belum ada notifikasi
                                </div>

                            @endforelse

                        </div>

                    </x-slot>

                </x-dropdown>

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