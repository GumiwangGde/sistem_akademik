<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Dosen: ') }} <span class="text-blue-600">{{ $dosen->user->name ?? $dosen->nidn }}</span>
            </h2>
            <a href="{{ route('admin.dosen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Dosen') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Informasi Akun & Detail Dosen
                            </h3>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dosen->user->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Email Akun</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dosen->user->email ?? 'N/A' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">NIDN</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dosen->nidn }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Status Dosen Wali</dt>
                                    <dd class="mt-1 text-sm">
                                        @if($dosen->is_dosen_wali)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Akun Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ optional($dosen->user)->created_at ? $dosen->user->created_at->format('d F Y, H:i') : '-' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Data Dosen Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dosen->created_at->format('d F Y, H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($tahunAjaranAktif)
                            <div class="border-t border-gray-200 pt-6 mt-6">
                                <h3 class="text-md leading-6 font-medium text-gray-900 mb-3">
                                    Informasi Akademik (TA: {{ $tahunAjaranAktif->nama_tahun_ajaran }})
                                </h3>
                                @php
                                    // Filter kelasWali dan jadwalKuliah berdasarkan tahunAjaranAktif di sini
                                    $kelasPerwalianToShow = $dosen->kelasWali->filter(function ($kelas) use ($tahunAjaranAktif) {
                                        return $kelas->id_tahun_ajaran == $tahunAjaranAktif->id;
                                    });
                                    $jadwalMengajarToShow = $dosen->jadwalKuliah->filter(function ($jadwal) use ($tahunAjaranAktif) {
                                        return $jadwal->id_tahun_ajaran == $tahunAjaranAktif->id;
                                    });
                                @endphp

                                @if($kelasPerwalianToShow->isNotEmpty())
                                <div class="mt-4">
                                    <dt class="text-sm font-medium text-gray-500">Kelas Perwalian (TA Aktif)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <ul class="list-disc list-inside pl-5">
                                        @foreach($kelasPerwalianToShow as $kelas)
                                            <li>{{ $kelas->nama_kelas }} ({{ $kelas->prodi->nama_prodi ?? 'N/A' }})</li>
                                        @endforeach
                                        </ul>
                                    </dd>
                                </div>
                                @else
                                <p class="text-sm text-gray-500 mt-2">Tidak ada kelas perwalian pada tahun ajaran aktif ini.</p>
                                @endif

                                @if($jadwalMengajarToShow->isNotEmpty())
                                <div class="mt-4">
                                    <dt class="text-sm font-medium text-gray-500">Jadwal Mengajar (TA Aktif)</dt>
                                     <ul class="list-disc list-inside pl-5 text-sm text-gray-900">
                                        @foreach($jadwalMengajarToShow as $jadwal)
                                            <li>
                                                {{ $jadwal->masterMatakuliah->nama_mk ?? $jadwal->nama_mk }} 
                                                (Kelas: {{ $jadwal->kelas->nama_kelas ?? 'N/A' }} - {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                                </div>
                                @else
                                <p class="text-sm text-gray-500 mt-2">Tidak ada jadwal mengajar pada tahun ajaran aktif ini.</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 mt-6 border-t border-gray-200 pt-6">Tidak ada Tahun Ajaran yang aktif saat ini untuk menampilkan informasi akademik terkait.</p>
                        @endif

                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.dosen.edit', $dosen->id_dosen) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('Edit Dosen') }}
                        </a>
                        <form action="{{ route('admin.dosen.destroy', $dosen->id_dosen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini? Tindakan ini tidak dapat diurungkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Hapus Dosen') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
