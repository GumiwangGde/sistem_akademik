<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Berita') }}: <span class="italic">{{ Str::limit($berita->judul, 30) }}</span>
            </h2>
            <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Pastikan route 'admin.berita.update' sudah benar dengan parameter 'berita' --}}
                    <form method="POST" action="{{ route('admin.berita.update', ['berita' => $berita->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700">{{ __('Judul Berita') }} <span class="text-red-500">*</span></label>
                            <input id="judul" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="judul" value="{{ old('judul', $berita->judul) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="isi" class="block text-sm font-medium text-gray-700">{{ __('Isi Berita') }} <span class="text-red-500">*</span></label>
                            {{-- Pertimbangkan untuk menggunakan editor WYSIWYG di sini --}}
                            <textarea id="isi" name="isi" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('isi', $berita->isi) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="gambar_url" class="block text-sm font-medium text-gray-700">{{ __('URL Gambar (Opsional)') }}</label>
                            <input id="gambar_url" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="url" name="gambar_url" value="{{ old('gambar_url', $berita->gambar_url) }}" placeholder="https://example.com/image.jpg" />
                            @if($berita->gambar_url)
                                <p class="mt-2 text-xs text-gray-500">Gambar saat ini: <a href="{{ $berita->gambar_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Lihat Gambar</a></p>
                                <div class="mt-1">
                                    <label for="hapus_gambar_url" class="inline-flex items-center">
                                        <input id="hapus_gambar_url" type="checkbox" name="hapus_gambar_url" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Hapus URL Gambar saat ini') }}</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                        {{-- 
                        <div class="mt-4">
                            <label for="gambar_file" class="block text-sm font-medium text-gray-700">{{ __('Ganti File Gambar (Opsional)') }}</label>
                            <input id="gambar_file" class="block mt-1 w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" type="file" name="gambar_file" />
                            @if($berita->gambar_url && !filter_var($berita->gambar_url, FILTER_VALIDATE_URL))
                                <p class="mt-2 text-xs text-gray-500">File gambar saat ini: {{ basename($berita->gambar_url) }} </p>
                                <div class="mt-1">
                                    <label for="hapus_gambar_file" class="inline-flex items-center">
                                        <input id="hapus_gambar_file" type="checkbox" name="hapus_gambar_file" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Hapus File Gambar saat ini') }}</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                        --}}

                        <div class="mt-4">
                            <label for="target_role" class="block text-sm font-medium text-gray-700">{{ __('Target Role') }} <span class="text-red-500">*</span></label>
                            <select id="target_role" name="target_role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="semua" {{ old('target_role', $berita->target_role) == 'semua' ? 'selected' : '' }}>Semua</option>
                                <option value="dosen" {{ old('target_role', $berita->target_role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="mahasiswa" {{ old('target_role', $berita->target_role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status Publikasi') }} <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="terbit" {{ old('status', $berita->status) == 'terbit' ? 'selected' : '' }}>Terbit</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="published_at" class="block text-sm font-medium text-gray-700">{{ __('Tanggal Publikasi (Opsional)') }}</label>
                            <input id="published_at" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="datetime-local" name="published_at" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : '') }}" />
                             <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin terbit segera (jika status "Terbit") atau jika status "Draft".</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Update Berita') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
