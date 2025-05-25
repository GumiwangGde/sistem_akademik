<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                <div class="px-4 py-4 sm:px-6 sm:py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h1>
                                <div class="flex items-center mt-1 flex-wrap gap-x-2 gap-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kelas->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($kelas->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500 hidden sm:inline">•</span>
                                    <span class="text-xs text-gray-500">{{ $kelas->prodi->nama_prodi ?? 'Prodi N/A' }}</span>
                                    <span class="text-xs text-gray-500 hidden sm:inline">•</span>
                                    <span class="text-xs text-gray-500">{{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'TA N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg shadow-sm border border-green-300" role="alert">
                    <p class="font-semibold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 px-4 py-3 leading-normal text-red-700 bg-red-100 rounded-lg shadow-sm border border-red-300" role="alert">
                    <p class="font-semibold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Informasi Umum</h3>
                            <span class="p-1.5 bg-blue-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</p>
                            <p class="mt-0.5 text-gray-900 font-medium">{{ $kelas->nama_kelas }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</p>
                            <p class="mt-0.5 text-gray-900">{{ $kelas->prodi->nama_prodi ?? 'N/A' }} ({{ $kelas->prodi->jenjang ?? 'N/A'}})</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</p>
                            <p class="mt-0.5 text-gray-900">{{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'N/A' }}</p>
                        </div>
                         <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                            <div class="mt-0.5">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kelas->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($kelas->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Dosen Wali</h3>
                            <span class="p-1.5 bg-green-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        @if($kelas->dosenWali && $kelas->dosenWali->user)
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        {{ substr($kelas->dosenWali->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-900 font-medium">{{ $kelas->dosenWali->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $kelas->dosenWali->nidn ?? 'NIDN tidak tersedia' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-4">
                                <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada dosen wali</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Statistik</h3>
                            <span class="p-1.5 bg-purple-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $mahasiswaCount ?? $kelas->mahasiswa()->count() }}</p>
                                <p class="text-xs font-medium text-blue-700 uppercase mt-1">Mahasiswa Terdaftar</p>
                            </div>
                            {{-- Anda bisa menambahkan statistik lain di sini jika relevan --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa</h3>
                    {{-- Tombol aksi terkait daftar mahasiswa bisa ditambahkan di sini jika perlu --}}
                </div>
                
                @if($kelas->mahasiswa && $kelas->mahasiswa->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Prodi Mahasiswa</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($kelas->mahasiswa as $mhs)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $mhs->nrp }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm hidden sm:flex flex-shrink-0">
                                                    {{ substr($mhs->user->name ?? $mhs->nama, 0, 1) }}
                                                </div>
                                                <div class="ml-0 sm:ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $mhs->user->name ?? $mhs->nama }}</div>
                                                    <div class="text-xs text-gray-500 md:hidden">{{ $mhs->user->email ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">{{ $mhs->user->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">{{ $mhs->prodi->nama_prodi ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            {{-- Tombol aksi untuk mahasiswa jika ada, misal lihat detail mahasiswa --}}
                                            @if(Route::has('admin.mahasiswa.show'))
                                            <a href="{{ route('admin.mahasiswa.show', $mhs->id_mahasiswa) }}" class="text-blue-600 hover:text-blue-900 inline-flex items-center justify-center border border-blue-600 rounded-md h-7 w-7 sm:h-8 sm:w-8" title="Lihat Detail Mahasiswa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 flex flex-col items-center justify-center text-center">
                        <div class="p-6 bg-blue-50 rounded-full mb-4 inline-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada mahasiswa</h3>
                        <p class="text-gray-500 max-w-sm">Belum ada mahasiswa yang terdaftar di kelas ini. Mahasiswa dapat ditambahkan melalui halaman manajemen mahasiswa.</p>
                    </div>
                @endif
            </div>

            <div class="mt-8 flex flex-wrap justify-end gap-3">
                @if($kelas->status != 'active')
                    <form action="{{ route('admin.kelas.toggleStatus', $kelas->id_kelas) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengaktifkan kelas {{ $kelas->nama_kelas }}?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('Aktifkan') }}
                        </button>
                    </form>
                @else
                     <form action="{{ route('admin.kelas.toggleStatus', $kelas->id_kelas) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menonaktifkan kelas {{ $kelas->nama_kelas }}?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                             <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            {{ __('Nonaktifkan') }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.kelas.edit', $kelas->id_kelas) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    {{ __('Edit Kelas') }}
                </a>
                <form action="{{ route('admin.kelas.destroy', $kelas->id_kelas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $kelas->nama_kelas }}? Tindakan ini tidak dapat diurungkan dan dapat mempengaruhi data terkait.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('Hapus Kelas') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
