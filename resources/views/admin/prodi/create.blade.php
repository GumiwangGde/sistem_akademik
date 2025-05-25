<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Program Studi Baru') }}
            </h2>
            <a href="{{ route('admin.prodi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Prodi') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"> {{-- max-w-3xl untuk form yang lebih fokus --}}
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

                    <form method="POST" action="{{ route('admin.prodi.store') }}">
                        @csrf

                        <div class="space-y-6">
                            {{-- Kode Prodi --}}
                            <div>
                                <label for="kode_prodi" class="block text-sm font-medium text-gray-700">{{ __('Kode Prodi') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_prodi" id="kode_prodi" value="{{ old('kode_prodi') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('kode_prodi') border-red-500 @enderror" 
                                       placeholder="Contoh: TI, SI, DKV">
                                @error('kode_prodi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nama Program Studi --}}
                            <div>
                                <label for="nama_prodi" class="block text-sm font-medium text-gray-700">{{ __('Nama Program Studi') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_prodi') border-red-500 @enderror"
                                       placeholder="Contoh: Teknik Informatika, Sistem Informasi">
                                @error('nama_prodi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jenjang --}}
                            <div>
                                <label for="jenjang" class="block text-sm font-medium text-gray-700">{{ __('Jenjang') }}</label>
                                <select name="jenjang" id="jenjang" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jenjang') border-red-500 @enderror">
                                    <option value="">Pilih Jenjang (Opsional)</option>
                                    @php
                                        // $jenjangList dikirim dari ProdiController@create
                                        // Jika tidak ada, bisa di-hardcode seperti di index.blade.php
                                        $jenjangOptions = $jenjangList ?? ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Spesialis'];
                                    @endphp
                                    @foreach ($jenjangOptions as $jenjang)
                                        <option value="{{ $jenjang }}" {{ old('jenjang') == $jenjang ? 'selected' : '' }}>
                                            {{ $jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenjang')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.prodi.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Prodi') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
