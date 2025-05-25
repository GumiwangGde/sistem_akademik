<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Master Mata Kuliah: ') }} <span class="text-blue-600">{{ $masterMatakuliah->nama_mk }}</span>
            </h2>
            <a href="{{ route('admin.mastermatakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Master MK') }}
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
                                Informasi Master Mata Kuliah
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Detail lengkap mengenai master mata kuliah.
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Kode Mata Kuliah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->kode_mk }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nama Mata Kuliah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->nama_mk }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->prodi->nama_prodi ?? 'N/A' }} ({{ $masterMatakuliah->prodi->jenjang ?? '' }})</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Semester Default Penawaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->semester_default ?? '-' }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">SKS Teori</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->sks_teori }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">SKS Praktek</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->sks_praktek }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">SKS Lapangan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->sks_lapangan }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Total SKS</dt>
                                    <dd class="mt-1 text-sm font-bold text-gray-900">{{ $masterMatakuliah->sks_total }}</dd>
                                </div>

                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 prose max-w-none">
                                        {!! nl2br(e($masterMatakuliah->deskripsi)) ?: '-' !!}
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->created_at->format('d F Y, H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $masterMatakuliah->updated_at->format('d F Y, H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Opsional: Tampilkan daftar jadwal kuliah yang menggunakan master MK ini --}}
                        @if($masterMatakuliah->jadwalKuliah && $masterMatakuliah->jadwalKuliah->count() > 0)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                             <h3 class="text-md leading-6 font-medium text-gray-900 mb-3">
                                Dijadwalkan Pada:
                            </h3>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach($masterMatakuliah->jadwalKuliah as $jadwal)
                                    <li>
                                        Tahun Ajaran: {{ $jadwal->tahunAjaran->nama_tahun_ajaran ?? 'N/A' }}
                                        (Kelas: {{ $jadwal->kelas->nama_kelas ?? 'N/A' }}, Dosen: {{ $jadwal->dosen->user->name ?? $jadwal->dosen->nidn ?? 'N/A' }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.mastermatakuliah.edit', $masterMatakuliah->id_master_mk) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('Edit Master MK') }}
                        </a>
                        <form action="{{ route('admin.mastermatakuliah.destroy', $masterMatakuliah->id_master_mk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus master mata kuliah ini? Tindakan ini tidak dapat diurungkan dan dapat mempengaruhi data terkait (jadwal kuliah, FRS, dll).');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Hapus Master MK') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
