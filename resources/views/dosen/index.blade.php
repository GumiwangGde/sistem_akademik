<x-app-layout>
    {{-- Navigation --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Dosen') }}
        </h2>
    </x-slot>

    {{-- Content --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('dosen.create') }}" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Tambah Dosen</a>
                    <table class="mt-4 w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">NIDN</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dosen as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $item->user->email }}</td>
                                    <td class="border px-4 py-2">{{ $item->nidn }}</td>
                                    <td class="border px-4 py-2">
                                        
                                        <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                        </form>
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
