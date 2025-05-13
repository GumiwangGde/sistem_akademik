{{-- resources/views/admin/matakuliah/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-blue-900 leading-tight p-4 rounded-lg shadow-md bg-gradient-to-r from-blue-200 to-blue-400">
            {{ __('Daftar Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    
                    {{-- Tombol untuk tambah mata kuliah --}}
                    <a href="{{ route('matakuliah.create') }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out mb-6">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Mata Kuliah
                    </a>

                    @if (isset($matakuliah) && $matakuliah->isNotEmpty())
                        <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                            <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                        <th class="px-4 py-3 text-left">Kode</th>
                                        <th class="px-4 py-3 text-left">Nama Mata Kuliah</th>
                                        <th class="px-4 py-3 text-left">Kelas</th>
                                        <th class="px-4 py-3 text-left">Dosen</th>
                                        <th class="px-4 py-3 text-left">SKS</th>
                                        <th class="px-4 py-3 text-left">Jadwal</th>
                                        <th class="px-4 py-3 text-left">Ruang</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($matakuliah as $item)
                                        <tr class="border-b hover:bg-blue-100">
                                            <td class="px-4 py-3 text-sm text-gray-600 font-medium">{{ $item->kode_mk }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->nama_mk }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->kelas->nama_kelas }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->dosen->user->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->sks }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->hari }}, {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->ruang }}</td>
                                            <td class="px-4 py-3 text-sm flex justify-center space-x-2">
                                                <a href="{{ route('matakuliah.edit', $item->id_mk) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('matakuliah.destroy', $item->id_mk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="mt-6 p-6 bg-yellow-100 text-yellow-700 rounded-lg shadow-md flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-lg font-medium">Tidak ada data mata kuliah</p>
                            <p class="text-sm mt-1">Silakan tambahkan mata kuliah baru dengan mengklik tombol di atas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>