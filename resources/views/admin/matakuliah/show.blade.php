<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Jadwal Kuliah: ') }} <span class="text-blue-600">{{ $jadwalKuliah->nama_mk }} ({{ $jadwalKuliah->kode_mk }})</span>
            </h2>
            <a href="{{ route('admin.matakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali') }}
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
                                Informasi Detail Jadwal Kuliah
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Detail lengkap mengenai jadwal pelaksanaan mata kuliah.
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Master Mata Kuliah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $jadwalKuliah->masterMatakuliah->nama_mk ?? 'N/A' }}
                                        <span class="text-xs text-gray-500">({{ $jadwalKuliah->masterMatakuliah->kode_mk ?? 'N/A' }})</span>
                                        <p class="text-xs text-gray-500">Prodi: {{ $jadwalKuliah->masterMatakuliah->prodi->nama_prodi ?? 'N/A' }}</p>
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tahun Ajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->tahunAjaran->nama_tahun_ajaran ?? 'N/A' }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Kode MK (Jadwal)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->kode_mk }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nama MK (Jadwal)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->nama_mk }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">SKS (Jadwal)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->sks }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Semester Pelaksanaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->semester }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Dosen Pengampu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->dosen->user->name ?? ($jadwalKuliah->dosen->nidn ?? 'N/A') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Kelas</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $jadwalKuliah->kelas->nama_kelas ?? 'N/A' }}
                                        @if($jadwalKuliah->kelas && $jadwalKuliah->kelas->prodi)
                                        <span class="text-xs text-gray-500">({{ $jadwalKuliah->kelas->prodi->nama_prodi }})</span>
                                        @endif
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Ruang</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->ruang->nama_ruang ?? 'N/A' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Hari</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->hari }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Jam Mulai</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwalKuliah->jam_mulai)->format('H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Jam Selesai</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwalKuliah->jam_selesai)->format('H:i') }}</dd>
                                </div>
                                
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->created_at->format('d F Y, H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $jadwalKuliah->updated_at->format('d F Y, H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.matakuliah.edit', $jadwalKuliah->id_mk) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('Edit Jadwal') }}
                        </a>
                        <form action="{{ route('admin.matakuliah.destroy', $jadwalKuliah->id_mk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kuliah ini? Tindakan ini tidak dapat diurungkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Hapus Jadwal') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
