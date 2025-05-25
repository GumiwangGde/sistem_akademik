{{-- resources/views/admin/matakuliah/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- Wrapper ini memastikan card header di bawahnya terpusat dan memiliki lebar maksimal --}}
        {{-- sm:px-6 lg:px-8 DIHILANGKAN dari sini, karena mungkin sudah ada di layout utama --}}
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                {{-- Padding internal card header --}}
                <div class="px-4 py-4 sm:px-6 sm:py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ __('Manajemen Mata Kuliah') }}</h1>
                                <p class="text-sm text-gray-500">Kelola data mata kuliah dalam sistem akademik</p>
                            </div>
                        </div>
                        <a href="{{ route('matakuliah.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Mata Kuliah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        {{-- Wrapper untuk body content TETAP menggunakan sm:px-6 lg:px-8 karena ini adalah blok konten utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="flex w-full overflow-hidden bg-white shadow-md rounded-lg border border-gray-200">
                        <div class="flex items-center justify-center w-12 bg-green-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-green-500">Berhasil</span>
                            <p class="text-sm text-gray-600">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 sm:p-6">
                        @if (isset($matakuliah) && $matakuliah->isNotEmpty())
                            <div class="flex flex-col">
                                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                        <div class="shadow-sm border border-gray-200 sm:rounded-lg overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Kode
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Nama Mata Kuliah
                                                        </th>
                                                        <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Kelas
                                                        </th>
                                                        <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Dosen
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            SKS
                                                        </th>
                                                        <th scope="col" class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Jadwal
                                                        </th>
                                                        <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Ruang
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Aksi
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($matakuliah as $item)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                {{ $item->kode_mk }}
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                                <div class="max-w-xs truncate" title="{{ $item->nama_mk }}">{{ $item->nama_mk }}</div>
                                                            </td>
                                                            <td class="hidden md:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    {{ $item->kelas->nama_kelas ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">
                                                                <div class="max-w-xs truncate" title="{{ $item->dosen->user->name ?? 'N/A' }}">{{ $item->dosen->user->name ?? 'N/A' }}</div>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                    {{ $item->sks }}
                                                                </span>
                                                            </td>
                                                            <td class="hidden lg:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <div class="flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ $item->hari }}, {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                                                                </div>
                                                            </td>
                                                            <td class="hidden md:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <div class="flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                                    </svg>
                                                                    {{ $item->ruang?->nama_ruang ?? 'N/A' }}
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                                                <div class="flex items-center justify-center space-x-2">
                                                                    <a href="{{ route('matakuliah.edit', $item->id_mk) }}" class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 p-1 rounded-md" title="Edit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                    </a>
                                                                    <form action="{{ route('matakuliah.destroy', $item->id_mk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 p-1 rounded-md" title="Hapus">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
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
                        @else
                            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Mata Kuliah</h2>
                                <p class="text-gray-600 mb-6">Silakan tambahkan mata kuliah baru dengan mengklik tombol "Tambah Mata Kuliah"</p>
                                <a href="{{ route('matakuliah.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Mata Kuliah Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>