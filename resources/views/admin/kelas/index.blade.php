{{-- resources/views/admin/kelas/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto"> {{-- Wrapper untuk alignment header card --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                <div class="px-4 py-4 sm:px-6 sm:py-5"> {{-- Menyesuaikan padding internal header card --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ __('Manajemen Kelas') }}</h1>
                                <p class="text-sm text-gray-500">Kelola data kelas dalam sistem akademik</p>
                            </div>
                        </div>
                        <a href="{{ route('kelas.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12"> {{-- Padding vertikal halaman --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6"> {{-- Memberi jarak antar elemen flash dan card utama --}}
                {{-- Flash Messages Success --}}
                @if (session('success'))
                    <div class="flex w-full overflow-hidden bg-green-50 rounded-lg shadow-sm border border-green-300">
                        <div class="flex items-center justify-center w-12 bg-green-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-green-600">Berhasil</span>
                            <p class="text-sm text-green-500">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Flash Messages Error (from session) --}}
                @if(session('error'))
                    <div class="flex w-full overflow-hidden bg-red-50 rounded-lg shadow-sm border border-red-300">
                        <div class="flex items-center justify-center w-12 bg-red-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM22 27.3333H18V23.3333H22V27.3333ZM22 19.9999H18V12.6666H22V19.9999Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-red-600">Error</span>
                            <p class="text-sm text-red-500">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                
                {{-- Flash Messages Error (from isset($error_message)) --}}
                @if(isset($error_message))
                     <div class="flex w-full overflow-hidden bg-red-50 rounded-lg shadow-sm border border-red-300">
                        <div class="flex items-center justify-center w-12 bg-red-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM22 27.3333H18V23.3333H22V27.3333ZM22 19.9999H18V12.6666H22V19.9999Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-red-600">Error</span>
                            <p class="text-sm text-red-500">{{ $error_message }}</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-6"> {{-- Padding internal card utama --}}
                        @if (isset($kelas) && $kelas->isNotEmpty())
                            <div class="flex flex-col">
                                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                        <div class="shadow-sm border border-gray-200 sm:rounded-lg overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Nama Kelas
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Status
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Dosen Wali
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Detail
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Aksi
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($kelas as $item)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                {{ $item->nama_kelas ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $item->dosenWali && $item->dosenWali->user ? $item->dosenWali->user->name : 'N/A' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                <a href="{{ route('kelas.detail', $item->id_kelas) }}" class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 p-1 rounded-md inline-flex items-center" title="Lihat Detail">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                    Detail
                                                                </a>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                                <div class="flex items-center justify-center space-x-2">
                                                                    <a href="{{ route('kelas.edit', $item->id_kelas) }}" class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 p-1 rounded-md" title="Edit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                    </a>
                                                                    
                                                                    <form action="{{ route('kelas.destroy', $item->id_kelas) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $item->nama_kelas }}? Data terkait kelas ini (seperti mahasiswa dan jadwal) mungkin juga akan terpengaruh.');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 p-1 rounded-md" title="Hapus">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                    
                                                                    @if($item->status == 'inactive')
                                                                        <form action="{{ route('kelas.activate', $item->id_kelas) }}" method="POST" class="inline" onsubmit="return confirm('Aktifkan kelas {{ $item->nama_kelas }}?');">
                                                                            @csrf
                                                                            @method('PATCH') {{-- Biasanya aktivasi/deaktivasi menggunakan PATCH atau PUT --}}
                                                                            <button type="submit" class="text-green-600 hover:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 p-1 rounded-md" title="Aktifkan">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
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
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4"> {{-- bg-blue-50 to bg-blue-100 --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Kelas</h2>
                                <p class="text-gray-600 mb-6">Silakan tambahkan kelas baru dengan mengklik tombol "Tambah Kelas"</p> {{-- text-gray-500 to text-gray-600 --}}
                                <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Kelas Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>