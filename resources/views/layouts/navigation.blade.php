<head>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<aside class="bg-gradient-to-br bg-blue-900 -translate-x-80 fixed inset-0 z-50 my-4 ml-4 h-[calc(100vh-32px)] w-72 rounded-xl transition-transform duration-300 xl:translate-x-0">
    <div class="relative border-b border-white/20">
        <a class="flex items-center gap-4 py-6 px-8" href="#/">
            <h6 class="block antialiased tracking-normal font-sans text-base font-semibold leading-relaxed text-white">Akademik</h6>
        </a>
    </div>
    
    <div class="m-4">
        <ul class="mb-4 flex flex-col gap-1">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <button class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-tachometer-alt w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Dashboard</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('matakuliah.index') }}">
                    <button class="{{ request()->routeIs('matakuliah.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-book w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Matakuliah</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('kelas.index') }}">
                    <button class="{{ request()->routeIs('kelas.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Kelas</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('dosen.index') }}">
                    <button class="{{ request()->routeIs('dosen.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-users w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Dosen</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.ruang.index') }}">
                    <button class="{{ request()->routeIs('admin.ruang.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-door-open w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Ruang</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.index') }}">
                    <button class="{{ request()->routeIs('mahasiswa.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-graduation-cap w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Mahasiswa</p>
                    </button>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}">
                    <button class="{{ request()->routeIs('admin.users.index') ? 'bg-blue-700 text-white shadow-lg' : 'text-white hover:bg-white/10 active:bg-white/30' }} middle none font-sans font-bold center transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 rounded-lg w-full flex items-center gap-4 px-4 capitalize">
                        <i class="fas fa-users-cog w-5 h-5"></i>
                        <p class="block antialiased font-sans text-base leading-relaxed text-inherit font-medium capitalize">Users</p>
                    </button>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile -->
    <div class="px-6 py-4">
        <div class="flex items-center space-x-3">
            <div class=" rounded-full p-2">
                <i class="fas fa-user text-white"></i>
            </div>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center text-white space-x-1 focus:outline-none">
                    <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                </button>
                        
                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-circle mr-2"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                        @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </form>
                    </div>
                </div>
            </div>
        <div class="text-blue-300 text-sm mt-1">{{ Auth::user()->email }}</div>
    </div>
</aside>