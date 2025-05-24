{{-- resources/views/layouts/navigation.blade.php --}}
<head>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<aside class="bg-white fixed inset-0 z-50 my-4 ml-4 h-[calc(100vh-32px)] w-72 rounded-xl transition-transform duration-300 xl:translate-x-0 -translate-x-80 shadow-md overflow-hidden border border-gray-200">
    <!-- Modern Logo Header -->
    <div class="px-6 py-6 border-b border-white">
        <a class="flex items-center" href="{{ route('admin.dashboard') }}">
            <div class="bg-blue-500 w-10 h-10 rounded-lg flex items-center justify-center">
                <i class="fas fa-university text-white text-lg"></i>
            </div>
            <div class="ml-3">
                <h6 class="font-sans text-base font-bold text-gray-800">Sistem Akademik</h6>
                <p class="text-xs text-gray-500">Manajemen Pendidikan</p>
            </div>
        </a>
    </div>
    
    <!-- Navigation Menu with visual interest -->
    <div class="px-4 py-5">
        <nav>
            <ul class="space-y-1.5">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 
                              {{ request()->routeIs('admin.dashboard') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Dashboard</span>
                        
                        @if(request()->routeIs('admin.dashboard'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('matakuliah.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('matakuliah.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-book {{ request()->routeIs('matakuliah.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Matakuliah</span>
                        
                        @if(request()->routeIs('matakuliah.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('kelas.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('kelas.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher {{ request()->routeIs('kelas.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Kelas</span>
                        
                        @if(request()->routeIs('kelas.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('dosen.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('dosen.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-users {{ request()->routeIs('dosen.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Dosen</span>
                        
                        @if(request()->routeIs('dosen.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.ruang.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.ruang.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-door-open {{ request()->routeIs('admin.ruang.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Ruang</span>
                        
                        @if(request()->routeIs('admin.ruang.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('mahasiswa.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('mahasiswa.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-graduation-cap {{ request()->routeIs('mahasiswa.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Mahasiswa</span>
                        
                        @if(request()->routeIs('mahasiswa.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.users.index') 
                                 ? 'bg-blue-500 text-white shadow-sm' 
                                 : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-users-cog {{ request()->routeIs('admin.users.index') ? 'text-white' : 'text-blue-500' }}"></i>
                        </div>
                        <span class="ml-3">Users</span>
                        
                        @if(request()->routeIs('admin.users.index'))
                        <div class="ml-auto bg-white opacity-70 h-2 w-2 rounded-full"></div>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modern User Profile Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4">
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
            <div x-data="{ open: false }" class="relative">
                <!-- Stylish user button -->
                <button @click="open = !open" class="flex w-full items-center gap-3 focus:outline-none">
                    <div class="relative">
                        <div class="h-9 w-9 rounded-lg bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-green-500 border-2 border-white"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Admin
                        </p>
                    </div>
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-gray-400 bg-white shadow-sm border border-gray-200">
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </button>
                
                <!-- Dropdown menu with animation -->
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    @click.away="open = false" 
                    class="absolute left-0 bottom-full mb-3 w-full bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 z-50">
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-user-circle mr-3 text-blue-500"></i> 
                            <span>Profile Settings</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-sign-out-alt mr-3 text-red-500"></i> 
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>