{{-- resources/views/admin/mahasiswa/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Daftar Mahasiswa') }}
            </h2>
        </div>
    </x-slot>

    <!-- Latar belakang keseluruhan halaman putih -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Error jika ada --}}
                    @if(isset($error_message))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Error!</strong> {{ $error_message }}
                        </div>
                    @endif

                    {{-- Pesan Sukses jika ada --}}
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tombol untuk tambah mahasiswa --}}
                    <a href="{{ route('mahasiswa.create') }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out mb-6">
                        <i class="fas fa-user-plus mr-2"></i> Tambah Mahasiswa
                    </a>

                    {{-- Cek jika ada data mahasiswa --}}
                    @if(isset($mahasiswa) && $mahasiswa->isNotEmpty())
                        <!-- Tabel dengan latar belakang biru -->
                        <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                            <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                        <!-- Urutan kolom: NRP, Nama, Kelas, Prodi, Email, Aksi -->
                                        <th class="px-6 py-4 text-left">NRP</th>
                                        <th class="px-6 py-4 text-left">Nama</th>
                                        <th class="px-6 py-4 text-left">Kelas</th>
                                        <th class="px-6 py-4 text-left">Prodi</th>
                                        <th class="px-6 py-4 text-left">Email</th>
                                        <th class="px-6 py-4 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswa as $item)
                                        <tr class="border-b hover:bg-blue-100">
                                            <!-- NRP -->
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nrp ?? 'N/A' }}</td>

                                            <!-- Nama -->
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama ?? 'N/A' }}</td>

                                            <!-- Kelas -->
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->kelas->nama_kelas ?? 'N/A' }}</td>

                                            <!-- Prodi -->
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->prodi ?? 'N/A' }}</td>

                                            <!-- Email -->
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->user->email ?? 'N/A' }}</td>

                                            <!-- Aksi -->
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex gap-4 items-center">
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('mahasiswa.edit', $item->id_mahasiswa) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('mahasiswa.destroy', $item->id_mahasiswa) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">
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
                            Tidak ada data mahasiswa untuk ditampilkan.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>