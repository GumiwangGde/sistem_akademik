<head>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Detail Kelas: ') }} {{ $kelas->nama_kelas }}
            </h2>
        </div>
    </x-slot>

    <!-- Latar belakang keseluruhan halaman putih -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('kelas.index') }}" class="inline-block bg-gradient-to-r from-gray-500 to-gray-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-gray-400 hover:to-gray-500 transition-all duration-300 ease-in-out mb-6">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kelas
                    </a>

                    {{-- Informasi Umum Kelas --}}
                    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informasi Kelas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Nama Kelas</h4>
                                <p class="text-gray-700">{{ $kelas->nama_kelas }}</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Status</h4>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $kelas->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($kelas->status) }}
                                </span>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Dosen Wali</h4>
                                <p class="text-gray-700">
                                    {{ $kelas->dosenWali && $kelas->dosenWali->user ? $kelas->dosenWali->user->name : 'Belum ada dosen wali' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Card --}}
                    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>Statistik Kelas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-blue-800">Total Mahasiswa</h4>
                                        <p class="text-2xl font-bold text-blue-900">{{ $mahasiswaCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-green-100 to-green-200 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-tie text-green-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-green-800">Dosen Wali</h4>
                                        <p class="text-lg font-semibold text-green-900">
                                            {{ $kelas->dosenWali && $kelas->dosenWali->user ? 'Tersedia' : 'Belum Ada' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Mahasiswa --}}
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-list mr-2 text-blue-600"></i>Daftar Mahasiswa
                        </h3>

                        @if($mahasiswa->isNotEmpty())
                            <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                                <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                            <th class="px-6 py-4 text-left">NRP</th>
                                            <th class="px-6 py-4 text-left">Nama Mahasiswa</th>
                                            <th class="px-6 py-4 text-left">Email</th>
                                            <th class="px-6 py-4 text-left">Program Studi</th>
                                            <th class="px-6 py-4 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mahasiswa as $mhs)
                                            <tr class="border-b hover:bg-blue-100">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mhs->nrp }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $mhs->nama }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mhs->user->email ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mhs->prodi }}</td>
                                                <td class="px-6 py-4 text-sm">
                                                    <div class="flex gap-4 items-center">
                                                        <!-- Tombol Lihat Detail Mahasiswa -->
                                                        <a href="{{ route('mahasiswa.show', $mhs->id_mahasiswa) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                                            <i class="fas fa-eye mr-1"></i> Detail
                                                        </a>
                                                        
                                                        <!-- Tombol Edit Mahasiswa -->
                                                        <a href="{{ route('mahasiswa.edit', $mhs->id_mahasiswa) }}" class="text-green-600 hover:text-green-800 transition duration-300 ease-in-out hover:underline">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-6 p-6 bg-yellow-100 text-yellow-700 rounded-lg shadow-md">
                                <i class="fas fa-info-circle mr-2"></i>
                                Belum ada mahasiswa yang terdaftar di kelas ini.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>