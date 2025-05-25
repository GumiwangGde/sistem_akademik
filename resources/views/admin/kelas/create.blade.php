<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kelas Baru') }}
            </h2>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Kelas') }}
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
                    @if (session('error'))
                        <div class="mb-4 px-4 py-3 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                             <p class="font-semibold">Error!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif


                    <form method="POST" action="{{ route('admin.kelas.store') }}">
                        @csrf

                        <div class="space-y-6">
                            {{-- Nama Kelas --}}
                            <div>
                                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">{{ __('Nama Kelas') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_kelas') border-red-500 @enderror" 
                                       placeholder="Contoh: TI-2A, SI-3B">
                                @error('nama_kelas')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Program Studi --}}
                            <div>
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

                            {{-- Tahun Ajaran --}}
                            <div>
                                <label for="id_tahun_ajaran" class="block text-sm font-medium text-gray-700">{{ __('Tahun Ajaran') }} <span class="text-red-500">*</span></label>
                                <select name="id_tahun_ajaran" id="id_tahun_ajaran" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_tahun_ajaran') border-red-500 @enderror">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($tahunAjaranList as $ta)
                                        <option value="{{ $ta->id }}" {{ old('id_tahun_ajaran') == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->nama_tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tahun_ajaran')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dosen Wali --}}
                            <div>
                                <label for="id_dosen_wali" class="block text-sm font-medium text-gray-700">{{ __('Dosen Wali') }} <span id="dosen_wali_required_indicator" class="text-red-500 hidden">*</span></label>
                                <select name="id_dosen_wali" id="id_dosen_wali"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_dosen_wali') border-red-500 @enderror">
                                    <option value="">Pilih Dosen Wali (Opsional jika status tidak aktif)</option>
                                    @foreach ($dosenWaliList as $dosen)
                                        <option value="{{ $dosen->id_dosen }}" {{ old('id_dosen_wali') == $dosen->id_dosen ? 'selected' : '' }}>
                                            {{ $dosen->user->name ?? $dosen->nidn }} ({{ $dosen->nidn }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_dosen_wali')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status Kelas') }} <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                    <option value="inactive" {{ old('status', 'inactive') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Dosen wali wajib diisi jika status kelas 'Aktif'.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.kelas.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Kelas') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('status');
            const dosenWaliRequiredIndicator = document.getElementById('dosen_wali_required_indicator');

            function toggleDosenWaliRequired() {
                if (statusSelect.value === 'active') {
                    dosenWaliRequiredIndicator.classList.remove('hidden');
                } else {
                    dosenWaliRequiredIndicator.classList.add('hidden');
                }
            }
            if(statusSelect) {
                toggleDosenWaliRequired(); // Initial check
                statusSelect.addEventListener('change', toggleDosenWaliRequired);
            }
        });
    </script>
    @endpush
</x-app-layout>
