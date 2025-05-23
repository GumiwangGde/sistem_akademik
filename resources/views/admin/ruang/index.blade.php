<x-app-layout>
    <x-slot name="header">
    <div class="bg-gradient-to-r from-blue-800 to-blue-900 text-white rounded-l-xl rounded-r-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">{{ __('Daftar Ruang') }}</h1>
                </div>
            </div>
        </div>
    </div>
    </x-slot>

    <div class="bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('admin.ruang.create') }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out mb-6">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Tambah Ruang
                        </a>
                    </div>
                    <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                        <thead class="">
                            <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Nama Ruang</th>
                                <th class="px-4 py-3 text-left">Kapasitas</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ruang as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_ruang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kapasitas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-4 items-center">
                                        <a href="{{ route('admin.ruang.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out hover:underline">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                        <form action="{{ route('admin.ruang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruang ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300 ease-in-out hover:underline">
                                            <i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
