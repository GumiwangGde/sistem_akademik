<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Berita') }}: {{ Str::limit($berita->judul, 30) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

                    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Berita <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul', $berita->judul) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>

                        <div class="mt-4">
                            <label for="isi" class="block text-sm font-medium text-gray-700">Isi Berita <span class="text-red-500">*</span></label>
                            <textarea name="isi" id="isi" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">{{ old('isi', $berita->isi) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="gambar_url" class="block text-sm font-medium text-gray-700">URL Gambar (Opsional)</label>
                            <input type="url" name="gambar_url" id="gambar_url" value="{{ old('gambar_url', $berita->gambar_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="https://example.com/image.jpg">
                            @if($berita->gambar_url)
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Gambar saat ini:</p>
                                <img src="{{ $berita->gambar_url }}" alt="Gambar Berita" class="mt-1 rounded max-h-40">
                                <div class="mt-1">
                                    <input type="checkbox" name="hapus_gambar_url" id="hapus_gambar_url" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="hapus_gambar_url" class="ml-2 text-sm text-gray-600">Hapus URL gambar saat ini</label>
                                </div>
                            </div>
                            @endif
                        </div>
                        {{-- 
                        <div class="mt-4">
                            <label for="gambar_file" class="block text-sm font-medium text-gray-700">Atau Upload File Gambar Baru (Opsional)</label>
                            <input type="file" name="gambar_file" id="gambar_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                             @if($berita->gambar_url && !filter_var($berita->gambar_url, FILTER_VALIDATE_URL)) 
                                <p class="text-xs text-gray-500 mt-1">File gambar saat ini: {{ basename($berita->gambar_url) }}</p>
                                <div class="mt-1">
                                    <input type="checkbox" name="hapus_gambar_file" id="hapus_gambar_file" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="hapus_gambar_file" class="ml-2 text-sm text-gray-600">Hapus file gambar saat ini</label>
                                </div>
                            @endif
                        </div>
                        --}}

                        <div class="mt-4">
                            <label for="target_role" class="block text-sm font-medium text-gray-700">Target Role <span class="text-red-500">*</span></label>
                            <select name="target_role" id="target_role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="semua" {{ old('target_role', $berita->target_role) == 'semua' ? 'selected' : '' }}>Semua</option>
                                <option value="dosen" {{ old('target_role', $berita->target_role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="mahasiswa" {{ old('target_role', $berita->target_role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Publikasi <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="terbit" {{ old('status', $berita->status) == 'terbit' ? 'selected' : '' }}>Terbit</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="published_at" class="block text-sm font-medium text-gray-700">Tanggal Publikasi (Opsional)</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin terbit segera (jika status "Terbit") atau jika status "Draft".</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
