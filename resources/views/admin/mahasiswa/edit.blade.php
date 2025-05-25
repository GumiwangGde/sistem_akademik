<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Mahasiswa: ') }} <span class="text-indigo-600">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</span>
            </h2>
            <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali ke Daftar Mahasiswa') }}
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
                    @if(session('error'))
                        <div class="mb-4 px-4 py-3 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                             <p class="font-semibold">Error!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa->id_mahasiswa) }}" id="editMahasiswaForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Informasi Akun Pengguna</h3>
                                <p class="text-sm text-gray-500 mb-4">Nama Akun juga akan digunakan sebagai Nama Profil Mahasiswa.</p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama Lengkap Mahasiswa') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $mahasiswa->user->name ?? $mahasiswa->nama) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">{{ __('Username Email') }} <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="username" id="username" 
                                           value="{{ old('username', $email_username_edit) }}" 
                                           required
                                           class="flex-1 block w-full min-w-0 rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('username') border-red-500 @enderror @error('email') border-red-500 @enderror"
                                           placeholder="username.unik">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        @it.student.pens.ac.id
                                    </span>
                                </div>
                                {{-- Input hidden 'email' tidak diperlukan jika controller membuat email dari 'username' --}}
                                {{-- Namun, jika controller Anda memvalidasi 'email' dari request, ini diperlukan --}}
                                {{-- <input type="hidden" id="email_hidden" name="email" value="{{ old('email', $mahasiswa->user->email ?? '') }}"> --}}
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Email lengkap: <code id="email_preview" class="font-semibold text-gray-700"></code></p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password Baru (Opsional)') }}</label>
                                <input type="password" name="password" id="password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                                       placeholder="Kosongkan jika tidak ingin mengubah">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Informasi Detail Akademik</h3>
                            </div>

                            <div>
                                <label for="nrp" class="block text-sm font-medium text-gray-700">{{ __('NRP') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nrp" id="nrp" value="{{ old('nrp', $mahasiswa->nrp) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nrp') border-red-500 @enderror">
                                @error('nrp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="prodi" class="block text-sm font-medium text-gray-700">{{ __('Program Studi') }} <span class="text-red-500">*</span></label>
                                <select name="prodi" id="prodi" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('prodi') border-red-500 @enderror">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($prodiModelList as $prodi_item)
                                        <option value="{{ $prodi_item->nama_prodi }}" {{ old('prodi', $selected_prodi_nama) == $prodi_item->nama_prodi ? 'selected' : '' }}>
                                            {{ $prodi_item->nama_prodi }} ({{ $prodi_item->jenjang }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('prodi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="id_kelas" class="block text-sm font-medium text-gray-700">{{ __('Kelas') }} <span class="text-red-500">*</span></label>
                                <select name="id_kelas" id="id_kelas" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('id_kelas') border-red-500 @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas_item)
                                        <option value="{{ $kelas_item->id_kelas }}" {{ old('id_kelas', $mahasiswa->id_kelas) == $kelas_item->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas_item->nama_kelas }} 
                                            (@if($kelas_item->prodi){{ $kelas_item->prodi->nama_prodi }}@endif 
                                            - TA: @if($kelas_item->tahunAjaran){{ $kelas_item->tahunAjaran->nama_tahun_ajaran }}@endif)
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.mahasiswa.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameEl = document.getElementById('username');
            // const emailHiddenEl = document.getElementById('email_hidden'); // Dihilangkan jika controller tidak menggunakannya
            const emailPreviewEl = document.getElementById('email_preview');
            const formEl = document.getElementById('editMahasiswaForm'); // ID form edit
            const domain = '@it.student.pens.ac.id';

            function updateEmailPreview() {
                if (!usernameEl) return;
                const usernameValue = usernameEl.value.trim();
                const fullEmail = usernameValue ? usernameValue + domain : '';
                
                // Jika controller Anda membuat email dari 'username', input hidden 'email' tidak perlu di-update oleh JS
                // if (emailHiddenEl) {
                //     emailHiddenEl.value = fullEmail;
                // }
                if (emailPreviewEl) {
                    emailPreviewEl.textContent = fullEmail || '(username akan ditambahkan domain otomatis)';
                }
            }

            if (usernameEl) {
                usernameEl.addEventListener('input', updateEmailPreview);
                updateEmailPreview(); // Panggil saat load
            }

            // Jika controller membuat email dari 'username', tidak perlu update hidden field saat submit
            // if (formEl) {
            //     formEl.addEventListener('submit', function() {
            //         updateEmailPreview(); // Cukup update preview, atau jika ada hidden field, update itu.
            //     });
            // }
        });
    </script>
    @endpush
</x-app-layout>
