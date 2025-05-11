<head>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-blue-900 leading-tight p-4 rounded-lg shadow-md bg-gradient-to-r from-blue-200 to-blue-400">
            {{ __('Daftar Dosen') }}
        </h2>
    </x-slot>

    <!-- Latar belakang keseluruhan halaman putih -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Error jika ada --}}
                    @if(isset($error_message))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Error!</strong> {{ $error_message }}
                        </div>
                    @endif

                    {{-- Tombol untuk tambah dosen --}}
                    <a href="{{ route('dosen.create') }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out mb-6">
                        <i class="fas fa-user-plus mr-2"></i> Tambah Dosen
                    </a>

                    {{-- Cek jika ada data dosen --}}
                    @if(isset($dosen) && $dosen->isNotEmpty())
                        <!-- Tabel dengan latar belakang biru -->
                        <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50"> <!-- Latar belakang biru untuk tabel -->
                            <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                        <th class="px-6 py-4 text-left">Nama</th>
                                        <th class="px-6 py-4 text-left">Email</th>
                                        <th class="px-6 py-4 text-left">NIDN</th>
                                        <th class="px-6 py-4 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dosen as $item)
                                        <tr class="border-b hover:bg-blue-100">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->user->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->user->email ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nidn ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex gap-4 items-center">
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('dosen.edit', $item->id_dosen) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('dosen.destroy', $item->id_dosen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300 ease-in-out hover:underline">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="mt-6 p-6 bg-yellow-100 text-yellow-700 rounded-lg shadow-md">
                            Tidak ada data dosen untuk ditampilkan.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
