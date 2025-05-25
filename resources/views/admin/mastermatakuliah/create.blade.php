<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Master Mata Kuliah Baru') }}
            </h2>
            <a href="{{ route('admin.mastermatakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Master MK') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- max-w-4xl untuk form yang lebih lebar --}}
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

                    <form method="POST" action="{{ route('admin.mastermatakuliah.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kode MK --}}
                            <div>
                                <label for="kode_mk" class="block text-sm font-medium text-gray-700">{{ __('Kode Mata Kuliah') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_mk" id="kode_mk" value="{{ old('kode_mk') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('kode_mk') border-red-500 @enderror" 
                                       placeholder="Contoh: IF184101">
                                @error('kode_mk')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nama Mata Kuliah --}}
                            <div>
                                <label for="nama_mk" class="block text-sm font-medium text-gray-700">{{ __('Nama Mata Kuliah') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_mk" id="nama_mk" value="{{ old('nama_mk') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_mk') border-red-500 @enderror"
                                       placeholder="Contoh: Pemrograman Web Lanjut">
                                @error('nama_mk')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- SKS Teori --}}
                            <div>
                                <label for="sks_teori" class="block text-sm font-medium text-gray-700">{{ __('SKS Teori') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="sks_teori" id="sks_teori" value="{{ old('sks_teori', 0) }}" required min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sks_teori') border-red-500 @enderror">
                                @error('sks_teori')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- SKS Praktek --}}
                            <div>
                                <label for="sks_praktek" class="block text-sm font-medium text-gray-700">{{ __('SKS Praktek') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="sks_praktek" id="sks_praktek" value="{{ old('sks_praktek', 0) }}" required min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sks_praktek') border-red-500 @enderror">
                                @error('sks_praktek')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- SKS Lapangan --}}
                            <div>
                                <label for="sks_lapangan" class="block text-sm font-medium text-gray-700">{{ __('SKS Lapangan') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="sks_lapangan" id="sks_lapangan" value="{{ old('sks_lapangan', 0) }}" required min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sks_lapangan') border-red-500 @enderror">
                                @error('sks_lapangan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Semester Default --}}
                            <div>
                                <label for="semester_default" class="block text-sm font-medium text-gray-700">{{ __('Semester Default Penawaran') }}</label>
                                <input type="number" name="semester_default" id="semester_default" value="{{ old('semester_default') }}" min="1" max="14"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('semester_default') border-red-500 @enderror"
                                       placeholder="Contoh: 1, 2, ... 8">
                                @error('semester_default')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Program Studi --}}
                            <div class="md:col-span-2">
                                <label for="id_prodi" class="block text-sm font-medium text-gray-700">{{ __('Program Studi') }} <span class="text-red-500">*</span></label>
                                <select name="id_prodi" id="id_prodi" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_prodi') border-red-500 @enderror">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($prodiList as $prodi)
                                        <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi') == $prodi->id_prodi ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_prodi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">{{ __('Deskripsi Mata Kuliah') }}</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('deskripsi') border-red-500 @enderror"
                                          placeholder="Deskripsi singkat mengenai mata kuliah...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.mastermatakuliah.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Master MK') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
