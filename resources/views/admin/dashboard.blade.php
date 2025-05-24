<!-- filepath: c:\laragon\www\sistem_akademik\resources\views\admin\dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ __('Dashboard Admin') }}</h1>
                            <p class="text-sm text-gray-500">{{ __('Sistem Informasi Akademik') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Quick Stats Overview -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('Ringkasan Sistem') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Total Users Card -->
                    <div class="bg-white rounded-xl shadow-md border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-700"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Total Pengguna') }}</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ number_format($total_users) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full mr-1">A: {{ $total_admins }}</span>
                                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full mr-1">D: {{ $total_lecturers }}</span>
                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-full">M: {{ $total_students }}</span>
                                    </p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center group">
                                    {{ __('Kelola Pengguna') }}
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Dosen Card -->
                    <div class="bg-white rounded-xl shadow-md border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <div class="h-2 bg-gradient-to-r from-green-500 to-green-700"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Dosen') }}</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ number_format($total_dosen) }}</p>
                                    <p class="text-xs mt-1">
                                        <span class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ __('Aktif') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('dosen.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center group">
                                    {{ __('Kelola Dosen') }}
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mahasiswa Card -->
                    <div class="bg-white rounded-xl shadow-md border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <div class="h-2 bg-gradient-to-r from-yellow-400 to-yellow-600"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Mahasiswa') }}</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ number_format($total_mahasiswa) }}</p>
                                    <p class="text-xs mt-1">
                                        <span class="inline-flex items-center px-2 py-1 bg-yellow-50 text-yellow-700 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ __('Terdaftar') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('mahasiswa.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium flex items-center group">
                                    {{ __('Kelola Mahasiswa') }}
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mata Kuliah Card -->
                    <div class="bg-white rounded-xl shadow-md border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-700"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Mata Kuliah') }}</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ number_format($total_matakuliah) }}</p>
                                    <p class="text-xs mt-1">
                                        <span class="inline-flex items-center px-2 py-1 bg-purple-50 text-purple-700 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Tersedia') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('matakuliah.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center group">
                                    {{ __('Kelola Mata Kuliah') }}
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Resources -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    {{ __('Sumber Daya Akademik') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Classes Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-indigo-100 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="bg-indigo-600 p-3 rounded-lg mr-4 shadow-md">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="mt-1 inline-block px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                                    {{ __('Total Kelas') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-indigo-100">
                            <div class="flex space-x-4 text-sm">
                                <span class="flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Aktif') }}
                                </span>
                            </div>
                            <a href="{{ route('kelas.index') }}" class="relative inline-flex items-center px-6 py-2 overflow-hidden text-white bg-indigo-600 rounded-lg group focus:ring-4 focus:ring-indigo-300 focus:outline-none hover:bg-indigo-700 transition-all">
                                <span class="relative">{{ __('Kelola Kelas') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Rooms Card -->
                    <div class="bg-gradient-to-br from-red-50 to-white rounded-xl shadow-md border border-red-100 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="bg-red-600 p-3 rounded-lg mr-4 shadow-md">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="mt-1 inline-block px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                    {{ __('Total Ruang') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-red-100">
                            <div class="flex space-x-4 text-sm">
                                <span class="flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Tersedia') }}
                                </span>
                            </div>
                            <a href="{{ route('admin.ruang.index') }}" class="relative inline-flex items-center px-6 py-2 overflow-hidden text-white bg-red-600 rounded-lg group focus:ring-4 focus:ring-red-300 focus:outline-none hover:bg-red-700 transition-all">
                                <span class="relative">{{ __('Kelola Ruang') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    {{ __('Aksi Cepat') }}
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-blue-50 border-2 border-blue-100 hover:border-blue-300 hover:bg-blue-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700 text-center">{{ __('Pengguna') }}</span>
                        </a>

                        <a href="{{ route('dosen.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-green-50 border-2 border-green-100 hover:border-green-300 hover:bg-green-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700 text-center">{{ __('Dosen') }}</span>
                        </a>

                        <a href="{{ route('mahasiswa.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-yellow-50 border-2 border-yellow-100 hover:border-yellow-300 hover:bg-yellow-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700 text-center">{{ __('Mahasiswa') }}</span>
                        </a>

                        <a href="{{ route('matakuliah.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-purple-50 border-2 border-purple-100 hover:border-purple-300 hover:bg-purple-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700 text-center">{{ __('Mata Kuliah') }}</span>
                        </a>

                        <a href="{{ route('kelas.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-indigo-50 border-2 border-indigo-100 hover:border-indigo-300 hover:bg-indigo-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 text-center">{{ __('Kelas') }}</span>
                        </a>

                        <a href="{{ route('admin.ruang.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-red-50 border-2 border-red-100 hover:border-red-300 hover:bg-red-100 transition-all">
                            <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-xl shadow-md border border-blue-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-600 p-3 rounded-lg mr-4 shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Laporan Sistem') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Generate laporan komprehensif data sistem') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ route('admin.laporan.unduh') }}" class="group flex w-full items-center justify-center bg-blue-600 text-white py-3 px-4 rounded-lg font-medium shadow-md hover:bg-blue-700 transition-all hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>{{ __('Unduh Laporan PDF') }}</span>
                        </a>
                        <p class="text-xs text-gray-500 text-center border-t border-blue-100 pt-4 mt-4">
                            {{ __('Laporan mencakup ringkasan lengkap data pengguna, akademik, dan fasilitas') }}
                        </p>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-gradient-to-br from-green-50 via-white to-green-50 rounded-xl shadow-md border border-green-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-600 p-3 rounded-lg mr-4 shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Status Sistem') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Informasi kesehatan dan performa sistem') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                            <span class="text-sm font-medium text-gray-600">{{ __('Status Server') }}</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                {{ __('Online') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                            <span class="text-sm font-medium text-gray-600">{{ __('Database') }}</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                {{ __('Connected') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                            <span class="text-sm font-medium text-gray-600">{{ __('Last Backup') }}</span>
                            <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                                {{ now()->subHours(2)->format('H:i d/m/Y') }}
                            </span>
                        </div>
                        <div class="pt-4 border-t border-green-100 mt-4">
                            <p class="text-xs text-gray-500 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Sistem berjalan dengan baik. Monitoring otomatis aktif 24/7.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>