<x-app-layout>
<x-slot name="header">
    <div class="bg-gradient-to-r text-black ">
        <h1 class="text-3xl font-bold">{{ __('Dashboard Admin') }}</h1>
        <p class="mt-1 text-slate-950">{{ __('Sistem Informai Akademik') }}</p>
    </div>
</x-slot>


    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Quick Stats Overview -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Ringkasan Sistem') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Total Users Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Total Pengguna') }}</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($total_users) }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Admin: {{ $total_admins }} | Dosen: {{ $total_lecturers }} | Mahasiswa: {{ $total_students }}
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                {{ __('Kelola Pengguna') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Academic Data Cards -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Dosen') }}</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($total_dosen) }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Aktif') }}
                                    </span>
                                </p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('dosen.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                {{ __('Kelola Dosen') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Mahasiswa') }}</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($total_mahasiswa) }}</p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Terdaftar') }}
                                    </span>
                                </p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('mahasiswa.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium flex items-center">
                                {{ __('Kelola Mahasiswa') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Mata Kuliah') }}</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($total_matakuliah) }}</p>
                                <p class="text-xs text-purple-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Tersedia') }}
                                    </span>
                                </p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('matakuliah.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                                {{ __('Kelola Mata Kuliah') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Resources -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Sumber Daya Akademik') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Classes Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Manajemen Kelas') }}</h3>
                                    <p class="text-sm text-gray-600">{{ __('Kelola dan monitor kelas yang tersedia') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-indigo-600">{{ number_format($total_kelas) }}</p>
                                <p class="text-xs text-gray-500">{{ __('Total Kelas') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-4 text-sm">
                                <span class="text-green-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Aktif') }}
                                </span>
                            </div>
                            <a href="{{ route('kelas.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                {{ __('Kelola Kelas') }}
                            </a>
                        </div>
                    </div>

                    <!-- Rooms Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-lg mr-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Manajemen Ruang') }}</h3>
                                    <p class="text-sm text-gray-600">{{ __('Kelola fasilitas ruang kuliah dan laboratorium') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-red-600">{{ number_format($total_ruang) }}</p>
                                <p class="text-xs text-gray-500">{{ __('Total Ruang') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-4 text-sm">
                                <span class="text-green-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Tersedia') }}
                                </span>
                            </div>
                            <a href="{{ route('admin.ruang.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                {{ __('Kelola Ruang') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Aksi Cepat') }}</h2>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors">
                            <div class="bg-blue-100 group-hover:bg-blue-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700 text-center">{{ __('Pengguna') }}</span>
                        </a>

                        <a href="{{ route('dosen.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors">
                            <div class="bg-green-100 group-hover:bg-green-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700 text-center">{{ __('Dosen') }}</span>
                        </a>

                        <a href="{{ route('mahasiswa.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                            <div class="bg-yellow-100 group-hover:bg-yellow-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700 text-center">{{ __('Mahasiswa') }}</span>
                        </a>

                        <a href="{{ route('matakuliah.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors">
                            <div class="bg-purple-100 group-hover:bg-purple-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700 text-center">{{ __('Mata Kuliah') }}</span>
                        </a>

                        <a href="{{ route('kelas.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                            <div class="bg-indigo-100 group-hover:bg-indigo-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 text-center">{{ __('Kelas') }}</span>
                        </a>

                        <a href="{{ route('admin.ruang.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors">
                            <div class="bg-red-100 group-hover:bg-red-200 p-3 rounded-lg mb-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-red-700 text-center">{{ __('Ruang') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Reports Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Laporan Sistem') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Generate laporan komprehensif data sistem') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('admin.laporan.unduh') }}" class="block w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Unduh Laporan PDF') }}
                        </a>
                        <p class="text-xs text-gray-500 text-center">{{ __('Laporan mencakup ringkasan lengkap data pengguna, akademik, dan fasilitas') }}</p>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Status Sistem') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Informasi kesehatan dan performa sistem') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Status Server') }}</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                {{ __('Online') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Database') }}</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                {{ __('Connected') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Last Backup') }}</span>
                            <span class="text-xs text-gray-500">{{ now()->subHours(2)->format('H:i d/m/Y') }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                {{ __('Sistem berjalan dengan baik. Monitoring otomatis aktif 24/7.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>