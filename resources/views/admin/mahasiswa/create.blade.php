{{-- resources/views/admin/mahasiswa/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-900 leading-tight">
            {{ __('Tambah Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-r from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('mahasiswa.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Nama -->
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Nama</label>
                                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                <div class="flex">
                                    <input type="text" id="username" name="username" class="w-full p-3 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required placeholder="Masukkan username" oninput="updateEmail()">
                                    <span class="bg-gray-300 p-3 rounded-r-lg text-gray-700">@it.student.pens.ac.id</span>
                                </div>
                                <input type="hidden" id="email" name="email"> <!-- Hidden field untuk email lengkap -->
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                                <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                                @error('password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- NRP -->
                            <div class="mb-4">
                                <label for="nrp" class="block text-gray-700 font-medium mb-2">NRP</label>
                                <input type="text" id="nrp" name="nrp" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                                @error('nrp')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Prodi -->
                            <div class="mb-4">
                                <label for="prodi" class="block text-gray-700 font-medium mb-2">Program Studi</label>
                                <select id="prodi" name="prodi" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                                    <option value="">Pilih Program Studi</option>
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Teknik Komputer">Teknik Komputer</option>
                                    <option value="Multimedia Broadcasting">Multimedia Broadcasting</option>
                                </select>
                                @error('prodi')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kelas (Dropdown) -->
                            <div class="mb-4">
                                <label for="id_kelas" class="block text-gray-700 font-medium mb-2">Kelas</label>
                                <select id="id_kelas" name="id_kelas" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $kelasItem)
                                        <option value="{{ $kelasItem->id_kelas }}">{{ $kelasItem->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4">
                                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-300">
                                    Simpan
                                </button>
                            </div>
                            
                            <!-- Cancel Button -->
                            <div class="mb-4">
                                <a href="{{ route('mahasiswa.index') }}" class="block w-full text-center bg-gray-400 text-white py-3 rounded-lg shadow-md hover:bg-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-300">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateEmail() {
            const username = document.getElementById('username').value;
            const domain = '@it.student.pens.ac.id';
            document.getElementById('email').value = username + domain;  // Set email hidden field
        }
    </script>
</x-app-layout>