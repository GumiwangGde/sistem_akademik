<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Tahun Ajaran Baru') }}
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

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tahunajaran.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kode_tahun_ajaran" class="block text-sm font-medium text-gray-700">{{ __('Kode Tahun Ajaran') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_tahun_ajaran" id="kode_tahun_ajaran" value="{{ old('kode_tahun_ajaran') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('kode_tahun_ajaran') border-red-500 @enderror" 
                                       placeholder="Contoh: 20231, 20232">
                                @error('kode_tahun_ajaran')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_tahun_ajaran" class="block text-sm font-medium text-gray-700">{{ __('Nama Tahun Ajaran') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_tahun_ajaran" id="nama_tahun_ajaran" value="{{ old('nama_tahun_ajaran') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_tahun_ajaran') border-red-500 @enderror"
                                       placeholder="Contoh: 2023/2024 Ganjil">
                                @error('nama_tahun_ajaran')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_mulai" class="block text-sm font-medium text-gray-700">{{ __('Tahun Mulai') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="tahun_mulai" id="tahun_mulai" value="{{ old('tahun_mulai', date('Y')) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tahun_mulai') border-red-500 @enderror"
                                       placeholder="YYYY">
                                @error('tahun_mulai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_selesai" class="block text-sm font-medium text-gray-700">{{ __('Tahun Selesai') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="tahun_selesai" id="tahun_selesai" value="{{ old('tahun_selesai', date('Y') + 1) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tahun_selesai') border-red-500 @enderror"
                                       placeholder="YYYY">
                                @error('tahun_selesai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700">{{ __('Semester') }} <span class="text-red-500">*</span></label>
                                <select name="semester" id="semester" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('semester') border-red-500 @enderror">
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                    <option value="direncanakan" {{ old('status', 'direncanakan') == 'direncanakan' ? 'selected' : '' }}>Direncanakan</option>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Jika memilih 'Aktif', tahun ajaran lain yang aktif akan otomatis menjadi 'Tidak Aktif'.</p>
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-md font-medium text-gray-800 mb-2">Periode Penting (Opsional)</h4>
                            </div>

                            <div>
                                <label for="tanggal_mulai_perkuliahan" class="block text-sm font-medium text-gray-700">{{ __('Mulai Perkuliahan') }}</label>
                                <input type="date" name="tanggal_mulai_perkuliahan" id="tanggal_mulai_perkuliahan" value="{{ old('tanggal_mulai_perkuliahan') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal_mulai_perkuliahan') border-red-500 @enderror">
                                @error('tanggal_mulai_perkuliahan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_selesai_perkuliahan" class="block text-sm font-medium text-gray-700">{{ __('Selesai Perkuliahan') }}</label>
                                <input type="date" name="tanggal_selesai_perkuliahan" id="tanggal_selesai_perkuliahan" value="{{ old('tanggal_selesai_perkuliahan') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal_selesai_perkuliahan') border-red-500 @enderror">
                                @error('tanggal_selesai_perkuliahan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_mulai_frs" class="block text-sm font-medium text-gray-700">{{ __('Mulai FRS') }}</label>
                                <input type="date" name="tanggal_mulai_frs" id="tanggal_mulai_frs" value="{{ old('tanggal_mulai_frs') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal_mulai_frs') border-red-500 @enderror">
                                @error('tanggal_mulai_frs')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_selesai_frs" class="block text-sm font-medium text-gray-700">{{ __('Selesai FRS') }}</label>
                                <input type="date" name="tanggal_selesai_frs" id="tanggal_selesai_frs" value="{{ old('tanggal_selesai_frs') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal_selesai_frs') border-red-500 @enderror">
                                @error('tanggal_selesai_frs')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.tahunajaran.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Tahun Ajaran') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
