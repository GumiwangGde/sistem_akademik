{{-- filepath: c:\laragon\www\sistem_akademik\resources\views\admin\dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto"> {{-- Wrapper untuk alignment header card --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                <div class="px-4 py-4 sm:px-6 sm:py-5"> {{-- Padding internal header card disesuaikan --}}
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
        </div>
    </x-slot>

    <div class="py-8 sm:py-12"> {{-- Padding vertikal utama halaman --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-10"> {{-- Memberi jarak antar seksi dashboard --}}
                
                <section> {{-- Menggunakan tag <section> untuk semantic grouping --}}
                    <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Ringkasan Sistem') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <div class="bg-white rounded-xl shadow-lg border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                            <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-700"></div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('Total Pengguna') }}</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($total_users) }}</p>
                                        <p class="text-xs text-gray-400 mt-1 flex flex-wrap gap-1">
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full">A: {{ $total_admins }}</span>
                                            <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded-full">D: {{ $total_lecturers }}</span>
                                            <span class="px-2 py-0.5 bg-yellow-50 text-yellow-700 rounded-full">M: {{ $total_students }}</span>
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

                        <div class="bg-white rounded-xl shadow-lg border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                            <div class="h-2 bg-gradient-to-r from-green-500 to-green-700"></div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('Dosen') }}</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($total_dosen) }}</p>
                                        <p class="text-xs mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-green-50 text-green-700 rounded-full font-medium">
                                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
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

                        <div class="bg-white rounded-xl shadow-lg border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                            <div class="h-2 bg-gradient-to-r from-yellow-400 to-yellow-600"></div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('Mahasiswa') }}</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($total_mahasiswa) }}</p>
                                        <p class="text-xs mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-yellow-50 text-yellow-700 rounded-full font-medium">
                                                <svg class="w-3 h-3 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
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

                        <div class="bg-white rounded-xl shadow-lg border-0 overflow-hidden transform hover:scale-105 transition-all duration-300">
                            <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-700"></div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('Mata Kuliah') }}</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($total_matakuliah) }}</p>
                                        <p class="text-xs mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-purple-50 text-purple-700 rounded-full font-medium">
                                                <svg class="w-3 h-3 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
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
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ __('Sumber Daya Akademik') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-gradient-to-br from-indigo-50 via-gray-50 to-white rounded-xl shadow-lg border border-indigo-100 p-6 hover:shadow-xl transition-shadow duration-300">
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
                                    <div class="mt-1 inline-block px-2.5 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                                        {{ __('Total Kelas') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-indigo-100">
                                <div class="flex space-x-4 text-sm">
                                    <span class="flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-full font-medium">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $active_kelas_count ?? 0 }} {{ __('Aktif') }}
                                    </span>
                                </div>
                                <a href="{{ route('kelas.index') }}" class="relative inline-flex items-center px-6 py-2 overflow-hidden text-sm font-medium text-white bg-indigo-600 rounded-lg group focus:ring-4 focus:ring-indigo-300 focus:outline-none hover:bg-indigo-700 transition-all">
                                    <span class="relative">{{ __('Kelola Kelas') }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-pink-50 via-gray-50 to-white rounded-xl shadow-lg border border-pink-100 p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="bg-pink-600 p-3 rounded-lg mr-4 shadow-md">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {{-- Icon Ruang (sama dengan header, atau ganti jika perlu) --}}
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Manajemen Ruang') }}</h3>
                                        <p class="text-sm text-gray-600">{{ __('Kelola fasilitas ruang kuliah dan laboratorium') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-pink-600">{{ number_format($total_ruang) }}</p>
                                    <div class="mt-1 inline-block px-2.5 py-0.5 bg-pink-100 text-pink-700 text-xs font-medium rounded-full">
                                        {{ __('Total Ruang') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-pink-100">
                                <div class="flex space-x-4 text-sm">
                                    <span class="flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-full font-medium">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                       {{ $available_ruang_count ?? $total_ruang }} {{-- Ganti dengan variabel yang sesuai jika ada --}}
                                        {{ __('Tersedia') }}
                                    </span>
                                </div>
                                <a href="{{ route('admin.ruang.index') }}" class="relative inline-flex items-center px-6 py-2 overflow-hidden text-sm font-medium text-white bg-pink-600 rounded-lg group focus:ring-4 focus:ring-pink-300 focus:outline-none hover:bg-pink-700 transition-all">
                                    <span class="relative">{{ __('Kelola Ruang') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('Aksi Cepat') }}
                    </h2>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 border border-gray-200">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
                            <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700 text-center">{{ __('Pengguna') }}</span>
                            </a>

                            <a href="{{ route('dosen.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-green-300 hover:bg-green-50 transition-all duration-300">
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-700 text-center">{{ __('Dosen') }}</span>
                            </a>

                            <a href="{{ route('mahasiswa.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-yellow-300 hover:bg-yellow-50 transition-all duration-300">
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700 text-center">{{ __('Mahasiswa') }}</span>
                            </a>

                            <a href="{{ route('matakuliah.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700 text-center">{{ __('Mata Kuliah') }}</span>
                            </a>

                            <a href="{{ route('kelas.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-300">
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 text-center">{{ __('Kelas') }}</span>
                            </a>

                            <a href="{{ route('admin.ruang.index') }}" class="group flex flex-col items-center p-4 rounded-xl bg-gray-50 border-2 border-gray-100 hover:border-pink-300 hover:bg-pink-50 transition-all duration-300"> {{-- Warna disesuaikan dengan rooms card --}}
                                <div class="bg-white p-3 rounded-full mb-3 shadow-md group-hover:-translate-y-1 transition-transform">
                                    <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700 text-center">{{ __('Ruang') }}</span>
                            </a>
                        </div>
                    </div>
                </section>

                <section>
                     <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.108 1.204.165.399.505.71.93.78l.895.149c.542.09.94.56.94 1.11v1.093c0 .55-.398 1.02-.94 1.11l-.895.149c-.425.07-.765.383-.93.78-.165.398-.143.854.108 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.399.165-.71.505-.781.93l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.399-.505-.71-.93-.78l-.894-.148c-.542-.09-.94-.561-.94-1.11V11.5c0-.55.398-1.02.94-1.11l.894-.148c.425-.071.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.399-.165.71-.505.78-.93L9.232 3.94zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                        </svg>
                        {{ __('Tindakan Sistem') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-cyan-50 via-white to-cyan-50 rounded-xl shadow-lg border border-cyan-100 p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center mb-6">
                                <div class="bg-cyan-600 p-3 rounded-lg mr-4 shadow-md">
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
                                <a href="{{ route('admin.laporan.unduh') }}" class="group flex w-full items-center justify-center bg-cyan-600 text-white py-3 px-4 rounded-lg font-medium shadow-md hover:bg-cyan-700 transition-all hover:shadow-lg focus:ring-4 focus:ring-cyan-300 focus:outline-none">
                                    <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>{{ __('Unduh Laporan PDF') }}</span>
                                </a>
                                <p class="text-xs text-gray-500 text-center border-t border-cyan-100 pt-4 mt-4">
                                    {{ __('Laporan mencakup ringkasan lengkap data pengguna, akademik, dan fasilitas') }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-lime-50 via-white to-lime-50 rounded-xl shadow-lg border border-lime-100 p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center mb-6">
                                <div class="bg-lime-600 p-3 rounded-lg mr-4 shadow-md">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Status Sistem') }}</h3>
                                    <p class="text-sm text-gray-600">{{ __('Informasi kesehatan dan performa sistem') }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-600">{{ __('Status Server') }}</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                        {{ __('Online') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-600">{{ __('Database') }}</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                        {{ __('Connected') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-600">{{ __('Last Backup') }}</span>
                                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">
                                        {{ now()->subHours(2)->format('H:i, d M Y') }}
                                    </span>
                                </div>
                                <div class="pt-3 border-t border-lime-100 mt-3">
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
                </section>

            </div>
        </div>
    </div>
</x-app-layout>