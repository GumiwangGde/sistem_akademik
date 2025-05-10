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

                    {{-- Pesan Error jika ada --}}
                    @if(isset($error_message))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Error!</strong> {{ $error_message }}
                        </div>
                    @endif

                    {{-- Tombol untuk tambah dosen --}}
                    <a href="{{ route('dosen.create') }}" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Tambah Dosen</a>
                    
                    {{-- Cek jika ada data dosen --}}
                    @if(isset($dosen) && $dosen->isNotEmpty())
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
                                        <td class="border px-4 py-2">{{ $item->user->name ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $item->user->email ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $item-> nidn ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="flex gap-2">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('dosen.edit', $item->id_dosen) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                                
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('dosen.destroy', $item->id_dosen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="mt-4 p-4 bg-yellow-100 text-yellow-700 rounded">
                            Tidak ada data dosen untuk ditampilkan.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
