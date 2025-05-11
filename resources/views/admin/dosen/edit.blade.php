<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-900 leading-tight">
            {{ __('Edit Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-r from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tampilkan pesan error jika ada -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Error!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosen.update', $dosen->id_dosen) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Metode PUT untuk update -->

                        <!-- Form Grid -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Nama -->
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Nama</label>
                                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" value="{{ old('name', $dosen->user->name) }}" required>
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                <div class="flex">
                                    @php
                                        $emailParts = explode('@', $dosen->user->email);
                                        $username = $emailParts[0] ?? '';
                                    @endphp
                                    <input type="text" id="username" name="username" class="w-full p-3 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" value="{{ old('username', $username) }}" required oninput="updateEmail()">
                                    <span class="bg-gray-300 p-3 rounded-r-lg text-gray-700">@it.lecturer.pens.ac.id</span>
                                </div>
                                <input type="hidden" id="email" name="email" value="{{ old('email', $dosen->user->email) }}"> <!-- Hidden field untuk email lengkap -->
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                                <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200">
                                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password</small>
                            </div>

                            <!-- NIDN -->
                            <div class="mb-4">
                                <label for="nidn" class="block text-gray-700 font-medium mb-2">NIDN</label>
                                <input type="text" id="nidn" name="nidn" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200" value="{{ old('nidn', $dosen->nidn) }}" required>
                            </div>

                            <!-- Dosen Wali -->
                            <div class="mb-4 flex items-center">
                                <input type="checkbox" id="is_dosen_wali" name="is_dosen_wali" class="mr-2 text-indigo-600" {{ $dosen->is_dosen_wali ? 'checked' : '' }}>
                                <label for="is_dosen_wali" class="text-gray-700">Dosen Wali</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4">
                                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-300">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Jalankan fungsi updateEmail saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateEmail();
        });
        
        function updateEmail() {
            const username = document.getElementById('username').value;
            const domain = '@it.lecturer.pens.ac.id';
            document.getElementById('email').value = username + domain;  // Set email hidden field
            console.log("Email updated to: " + username + domain); // Debug
        }
    </script>
</x-app-layout>