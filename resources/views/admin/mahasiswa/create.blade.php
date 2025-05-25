<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Jadwal Kuliah Baru') }}
            </h2>
            <a href="{{ route('admin.matakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Jadwal Kuliah') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
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

                    <form method="POST" action="{{ route('admin.matakuliah.store') }}" id="createJadwalForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Master Mata Kuliah --}}
                            <div>
                                <label for="id_master_mk" class="block text-sm font-medium text-gray-700">{{ __('Master Mata Kuliah') }} <span class="text-red-500">*</span></label>
                                <select name="id_master_mk" id="id_master_mk" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_master_mk') border-red-500 @enderror">
                                    <option value="">Pilih Master Mata Kuliah</option>
                                    @foreach ($masterMatakuliahList as $masterMk)
                                        <option value="{{ $masterMk->id_master_mk }}" 
                                                data-kode="{{ $masterMk->kode_mk }}" 
                                                data-nama="{{ $masterMk->nama_mk }}" 
                                                data-sks="{{ $masterMk->sks_total }}" 
                                                {{ old('id_master_mk') == $masterMk->id_master_mk ? 'selected' : '' }}>
                                            {{ $masterMk->nama_mk }} ({{ $masterMk->kode_mk }}) - {{ $masterMk->prodi->nama_prodi ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_master_mk')
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
                            
                            {{-- Kode MK (Jadwal) - Readonly Display, Hidden Input for submission --}}
                            <div>
                                <label for="kode_mk_display" class="block text-sm font-medium text-gray-700">{{ __('Kode MK (Jadwal)') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="kode_mk_display" value="{{ old('kode_mk') }}" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:ring-0 focus:border-gray-300 sm:text-sm @error('kode_mk') border-red-500 @enderror">
                                <input type="hidden" name="kode_mk" id="kode_mk_hidden" value="{{ old('kode_mk') }}">
                                @error('kode_mk')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nama MK (Jadwal) - Readonly Display, Hidden Input for submission --}}
                            <div>
                                <label for="nama_mk_display" class="block text-sm font-medium text-gray-700">{{ __('Nama MK (Jadwal)') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_mk_display" value="{{ old('nama_mk') }}" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:ring-0 focus:border-gray-300 sm:text-sm @error('nama_mk') border-red-500 @enderror">
                                <input type="hidden" name="nama_mk" id="nama_mk_hidden" value="{{ old('nama_mk') }}">
                                @error('nama_mk')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- SKS (Jadwal) - Readonly Display, Hidden Input for submission --}}
                            <div>
                                <label for="sks_display" class="block text-sm font-medium text-gray-700">{{ __('SKS (Jadwal)') }} <span class="text-red-500">*</span></label>
                                <input type="number" id="sks_display" value="{{ old('sks') }}" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:ring-0 focus:border-gray-300 sm:text-sm @error('sks') border-red-500 @enderror">
                                <input type="hidden" name="sks" id="sks_hidden" value="{{ old('sks') }}">
                                @error('sks')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Semester Pelaksanaan --}}
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700">{{ __('Semester Pelaksanaan') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="semester" id="semester" value="{{ old('semester') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('semester') border-red-500 @enderror"
                                       placeholder="Contoh: Ganjil, Genap, atau 1, 2, dll.">
                                @error('semester')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dosen Pengampu --}}
                            <div>
                                <label for="id_dosen" class="block text-sm font-medium text-gray-700">{{ __('Dosen Pengampu') }} <span class="text-red-500">*</span></label>
                                <select name="id_dosen" id="id_dosen" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_dosen') border-red-500 @enderror">
                                    <option value="">Pilih Dosen</option>
                                    @foreach ($dosenList as $id_dosen => $nama_dosen)
                                        <option value="{{ $id_dosen }}" {{ old('id_dosen') == $id_dosen ? 'selected' : '' }}>
                                            {{ $nama_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_dosen')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kelas --}}
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700">{{ __('Kelas') }} <span class="text-red-500">*</span></label>
                                <select name="kelas_id" id="kelas_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('kelas_id') border-red-500 @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas_item)
                                        <option value="{{ $kelas_item->id_kelas }}" {{ old('kelas_id') == $kelas_item->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas_item->nama_kelas }} 
                                            (@if($kelas_item->prodi){{ $kelas_item->prodi->nama_prodi }}@else N/A @endif 
                                            - TA: @if($kelas_item->tahunAjaran){{ $kelas_item->tahunAjaran->nama_tahun_ajaran }}@else N/A @endif)
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ruang --}}
                            <div>
                                <label for="ruang_id" class="block text-sm font-medium text-gray-700">{{ __('Ruang') }} <span class="text-red-500">*</span></label>
                                <select name="ruang_id" id="ruang_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('ruang_id') border-red-500 @enderror">
                                    <option value="">Pilih Ruang</option>
                                    @foreach ($ruangList as $ruang_item)
                                        <option value="{{ $ruang_item->id }}" {{ old('ruang_id') == $ruang_item->id ? 'selected' : '' }}>
                                            {{ $ruang_item->nama_ruang }} (Kapasitas: {{ $ruang_item->kapasitas }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruang_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Hari --}}
                            <div>
                                <label for="hari" class="block text-sm font-medium text-gray-700">{{ __('Hari') }} <span class="text-red-500">*</span></label>
                                <select name="hari" id="hari" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hari') border-red-500 @enderror">
                                    <option value="">Pilih Hari</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari_option)
                                        <option value="{{ $hari_option }}" {{ old('hari') == $hari_option ? 'selected' : '' }}>{{ $hari_option }}</option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jam Mulai --}}
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700">{{ __('Jam Mulai') }} <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_mulai') border-red-500 @enderror">
                                @error('jam_mulai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jam Selesai --}}
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700">{{ __('Jam Selesai') }} <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_selesai') border-red-500 @enderror">
                                @error('jam_selesai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.matakuliah.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Jadwal Kuliah') }}
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
            const masterMkSelect = document.getElementById('id_master_mk');
            
            const kodeMkDisplay = document.getElementById('kode_mk_display');
            const namaMkDisplay = document.getElementById('nama_mk_display');
            const sksDisplay = document.getElementById('sks_display');
            
            const kodeMkHidden = document.getElementById('kode_mk_hidden');
            const namaMkHidden = document.getElementById('nama_mk_hidden');
            const sksHidden = document.getElementById('sks_hidden');
            const form = document.getElementById('createJadwalForm'); // ID untuk form

            function populateJadwalFields() {
                const selectedOption = masterMkSelect.options[masterMkSelect.selectedIndex];
                let kode = '';
                let nama = '';
                let sks = '';

                if (selectedOption && selectedOption.value) {
                    kode = selectedOption.getAttribute('data-kode') || '';
                    nama = selectedOption.getAttribute('data-nama') || '';
                    sks = selectedOption.getAttribute('data-sks') || '';
                }

                if(kodeMkDisplay) kodeMkDisplay.value = kode;
                if(namaMkDisplay) namaMkDisplay.value = nama;
                if(sksDisplay) sksDisplay.value = sks;

                if(kodeMkHidden) kodeMkHidden.value = kode;
                if(namaMkHidden) namaMkHidden.value = nama;
                if(sksHidden) sksHidden.value = sks;
            }

            if (masterMkSelect) {
                masterMkSelect.addEventListener('change', populateJadwalFields);
                // Panggil saat load untuk mengisi jika ada old value atau untuk form edit
                populateJadwalFields(); 
            }

            // Pastikan nilai hidden field terisi sebelum submit
            if (form) {
                form.addEventListener('submit', function(event) {
                    populateJadwalFields(); // Panggil sekali lagi untuk memastikan nilai terbaru
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
