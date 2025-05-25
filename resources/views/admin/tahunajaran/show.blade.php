<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Tahun Ajaran: ') }} <span class="text-blue-600">{{ $tahunAjaran->nama_tahun_ajaran }}</span>
            </h2>
            <a href="{{ route('admin.tahunajaran.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Tahun Ajaran') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Informasi Tahun Ajaran
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Detail lengkap mengenai periode akademik.
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Kode Tahun Ajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->kode_tahun_ajaran }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nama Tahun Ajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->nama_tahun_ajaran }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Periode</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->tahun_mulai }} / {{ $tahunAjaran->tahun_selesai }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Semester</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->semester }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($tahunAjaran->status == 'aktif')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @elseif ($tahunAjaran->status == 'direncanakan')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Direncanakan
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-2"></div> {{-- Spacer --}}

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Mulai Perkuliahan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->tanggal_mulai_perkuliahan ? \Carbon\Carbon::parse($tahunAjaran->tanggal_mulai_perkuliahan)->isoFormat('LL') : '-' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Selesai Perkuliahan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->tanggal_selesai_perkuliahan ? \Carbon\Carbon::parse($tahunAjaran->tanggal_selesai_perkuliahan)->isoFormat('LL') : '-' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Mulai FRS</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->tanggal_mulai_frs ? \Carbon\Carbon::parse($tahunAjaran->tanggal_mulai_frs)->isoFormat('LL') : '-' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Selesai FRS</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->tanggal_selesai_frs ? \Carbon\Carbon::parse($tahunAjaran->tanggal_selesai_frs)->isoFormat('LL') : '-' }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->created_at->format('d F Y, H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tahunAjaran->updated_at->format('d F Y, H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        @if ($tahunAjaran->status != 'aktif')
                        <form action="{{ route('admin.tahunajaran.setActive', $tahunAjaran->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengaktifkan Tahun Ajaran ini? Tahun Ajaran lain yang aktif akan dinonaktifkan.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('Aktifkan') }}
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.tahunajaran.edit', $tahunAjaran->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('Edit') }}
                        </a>
                         @if ($tahunAjaran->status != 'aktif')
                        <form action="{{ route('admin.tahunajaran.destroy', $tahunAjaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tahun Ajaran ini? Tindakan ini tidak dapat diurungkan dan dapat mempengaruhi data terkait.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Hapus') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
