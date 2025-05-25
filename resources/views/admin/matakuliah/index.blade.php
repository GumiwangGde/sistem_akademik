{{-- resources/views/admin/matakuliah/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                <div class="px-4 py-4 sm:px-6 sm:py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ __('Manajemen Jadwal Kuliah') }}</h1>
                                <p class="text-sm text-gray-500">Kelola data jadwal pelaksanaan mata kuliah</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.matakuliah.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
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
                @if (session('error'))
                    <div class="flex w-full overflow-hidden bg-white shadow-md rounded-lg border border-gray-200">
                        <div class="flex items-center justify-center w-12 bg-red-500">
                           <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-red-500">Gagal</span>
                            <p class="text-sm text-gray-600">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Filter Section --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg shadow">
                    <form method="GET" action="{{ route('admin.matakuliah.index') }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari (Kode/Nama MK, Dosen, Kelas)</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Masukkan kata kunci">
                            </div>
                            <div>
                                <label for="id_tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                <select name="id_tahun_ajaran" id="id_tahun_ajaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua TA</option>
                                    @foreach ($tahunAjaranList as $ta)
                                        <option value="{{ $ta->id }}" {{ request('id_tahun_ajaran') == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->nama_tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="id_prodi" class="block text-sm font-medium text-gray-700">Program Studi</label>
                                <select name="id_prodi" id="id_prodi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Prodi</option>
                                    @foreach ($prodiList as $prodi)
                                        <option value="{{ $prodi->id_prodi }}" {{ request('id_prodi') == $prodi->id_prodi ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                                <select name="id_kelas" id="id_kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filter
                                </button>
                                <a href="{{ route('admin.matakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 sm:p-6">
                        @if (isset($jadwalKuliah) && $jadwalKuliah->isNotEmpty())
                            <div class="flex flex-col">
                                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                        <div class="shadow-sm border border-gray-200 sm:rounded-lg overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah (Jadwal)</th>
                                                        <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Master MK</th>
                                                        <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                                        <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                                        <th scope="col" class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                                        <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                                                        <th scope="col" class="hidden xl:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TA</th>
                                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($jadwalKuliah as $item)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->kode_mk }}</td>
                                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                                <div class="max-w-xs truncate" title="{{ $item->nama_mk }}">{{ $item->nama_mk }}</div>
                                                            </td>
                                                            <td class="hidden md:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <div class="max-w-xs truncate" title="{{ $item->masterMatakuliah->nama_mk ?? 'N/A' }} ({{ $item->masterMatakuliah->kode_mk ?? 'N/A' }})">
                                                                    {{ $item->masterMatakuliah->nama_mk ?? 'N/A' }}
                                                                </div>
                                                            </td>
                                                            <td class="hidden md:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    {{ $item->kelas->nama_kelas ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-500">
                                                                <div class="max-w-xs truncate" title="{{ $item->dosen->user->name ?? ($item->dosen->nidn ?? 'N/A') }}">{{ $item->dosen->user->name ?? ($item->dosen->nidn ?? 'N/A') }}</div>
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
                                                            <td class="hidden xl:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $item->tahunAjaran->nama_tahun_ajaran ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                                                <div class="flex items-center justify-center space-x-2">
                                                                    <a href="{{ route('admin.matakuliah.show', $item->id_mk) }}" class="text-gray-500 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 p-1 rounded-md" title="Detail">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                    </a>
                                                                    <a href="{{ route('admin.matakuliah.edit', $item->id_mk) }}" class="text-gray-500 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 p-1 rounded-md" title="Edit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                    </a>
                                                                    <form action="{{ route('admin.matakuliah.destroy', $item->id_mk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal kuliah ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-gray-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 p-1 rounded-md" title="Hapus">
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
                            <div class="mt-6">
                                {{ $jadwalKuliah->links() }}
                            </div>
                        @else
                            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Jadwal Kuliah</h2>
                                <p class="text-gray-600 mb-6">Silakan tambahkan jadwal kuliah baru dengan mengklik tombol "Tambah Jadwal"</p>
                                <a href="{{ route('admin.matakuliah.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Jadwal Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
