{{-- resources/views/admin/dosen/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto"> {{-- Wrapper untuk alignment header card --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                <div class="px-4 py-4 sm:px-6 sm:py-5"> {{-- Padding internal header card --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ __('Edit Dosen') }}</h1>
                                <p class="text-sm text-gray-500">Perbarui data dosen: {{ $dosen->user->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12"> {{-- Padding vertikal halaman --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6"> {{-- Jarak antar flash message dan card form --}}
                {{-- Flash Message Success --}}
                @if(session('success'))
                    <div class="flex w-full overflow-hidden bg-green-50 rounded-lg shadow-sm border border-green-300">
                        <div class="flex items-center justify-center w-12 bg-green-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-green-600">Sukses!</span>
                            <p class="text-sm text-green-500">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Flash Message Error ($errors->any()) --}}
                @if($errors->any())
                    <div class="flex w-full overflow-hidden bg-red-50 rounded-lg shadow-sm border border-red-300">
                        <div class="flex items-center justify-center w-12 bg-red-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM22 27.3333H18V23.3333H22V27.3333ZM22 19.9999H18V12.6666H22V19.9999Z"></path>
                            </svg>
                        </div>
                        <div class="px-4 py-3">
                            <span class="font-semibold text-red-600">Terjadi kesalahan:</span>
                            <ul class="text-sm text-red-500 mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 sm:p-8">
                        <form action="{{ route('dosen.update', $dosen->id_dosen) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $dosen->user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Masukkan nama lengkap" required>
                                </div>
                                
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Email PENS <span class="text-red-500">*</span></label>
                                    @php
                                        $emailParts = explode('@', $dosen->user->email);
                                        $currentUsername = $emailParts[0] ?? '';
                                    @endphp
                                    <div class="flex mt-1">
                                        <input type="text" id="username" name="username" value="{{ old('username', $currentUsername) }}" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required placeholder="Username" oninput="updateEmail()">
                                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            @it.lecturer.pens.ac.id
                                        </span>
                                    </div>
                                    <input type="hidden" id="email" name="email" value="{{ old('email', $dosen->user->email) }}">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Kosongkan jika tidak diubah">
                                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ulangi password baru">
                                </div>

                                <div>
                                    <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">NIDN <span class="text-red-500">*</span></label>
                                    <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Masukkan NIDN" required>
                                </div>

                                <div class="md:col-span-2 flex items-center pt-2">
                                    <input type="checkbox" id="is_dosen_wali" name="is_dosen_wali" value="1" {{ old('is_dosen_wali', $dosen->is_dosen_wali) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <label for="is_dosen_wali" class="ml-2 block text-sm text-gray-700">Jadikan Dosen Wali</label>
                                </div>
                            </div>

                            <div class="flex items-center justify-end pt-4 space-x-3">
                                <a href="{{ route('dosen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const domain = '@it.lecturer.pens.ac.id';

            function updateEmail() {
                const username = usernameInput.value;
                // Hanya update jika username diisi, untuk menghindari @domain.ac.id saat kosong
                emailInput.value = username ? username + domain : ''; 
            }

            if (usernameInput) {
                usernameInput.addEventListener('input', updateEmail);
                // Panggil saat load untuk memastikan email terisi jika username sudah ada (dari old() atau model)
                updateEmail(); 
            }
        });
    </script>
    @endpush
</x-app-layout>