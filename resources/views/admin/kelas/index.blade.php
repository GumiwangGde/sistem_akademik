<head>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<x-app-layout>
    <x-slot name="header">
        <div class="ml-48">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Daftar Kelas') }}
            </h2>
        </div>
    </x-slot>

    <!-- Latar belakang keseluruhan halaman putih -->
    <div class="pl-80 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Success jika ada --}}
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

                    {{-- Pesan Error jika ada (perbaikan) --}}
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    @endif

                    {{-- Tambahkan handling untuk error lama --}}
                    @if(isset($error_message))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Error!</strong> {{ $error_message }}
                        </div>
                    @endif

                    {{-- Tombol untuk tambah kelas --}}
                    <a href="{{ route('kelas.create') }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out mb-6">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Kelas
                    </a>

                    {{-- Cek jika ada data kelas --}}
                    @if(isset($kelas) && $kelas->isNotEmpty())
                        <!-- Tabel dengan latar belakang biru -->
                        <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50"> <!-- Latar belakang biru untuk tabel -->
                            <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                        <th class="px-6 py-4 text-left">Nama Kelas</th>
                                        <th class="px-6 py-4 text-left">Status</th>
                                        <th class="px-6 py-4 text-left">Dosen Wali</th>
                                        <th class="px-6 py-4 text-left">Lihat Kelas</th>
                                        <th class="px-6 py-4 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kelas as $item)
                                        <tr class="border-b hover:bg-blue-100">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama_kelas ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $item->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $item->dosenWali && $item->dosenWali->user ? $item->dosenWali->user->name : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('kelas.detail', $item->id_kelas) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex gap-4 items-center">
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('kelas.edit', $item->id_kelas) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>

                                                    <!-- Tombol Hapus (Perbaikan) -->
                                                    <form action="{{ route('kelas.destroy', $item->id_kelas) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300 ease-in-out hover:underline" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $item->nama_kelas }}?');">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Aktifkan Kelas -->
                                                    @if($item->status == 'inactive')
                                                        <form action="{{ route('kelas.activate', $item->id_kelas) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-800 transition duration-300 ease-in-out hover:underline">
                                                                <i class="fas fa-check-circle mr-1"></i> Aktifkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="mt-6 p-6 bg-yellow-100 text-yellow-700 rounded-lg shadow-md">
                            Tidak ada data kelas untuk ditampilkan.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>