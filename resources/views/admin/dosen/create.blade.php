<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('dosen.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Nama</label>
                            <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700">Email</label>
                            <div class="flex">
                                <input type="text" id="username" name="username" class="w-full p-2 border border-gray-300 rounded-l" required placeholder="Masukkan username" oninput="updateEmail()">
                                <span class="bg-gray-300 p-2 rounded-r">@it.lecturer.pens.ac.id</span>
                            </div>
                            <input type="hidden" id="email" name="email"> <!-- Hidden field untuk email lengkap -->
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700">Password</label>
                            <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="nidn" class="block text-gray-700">NIDN</label>
                            <input type="text" id="nidn" name="nidn" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="is_dosen_wali" class="inline-flex items-center">
                                <input type="checkbox" id="is_dosen_wali" name="is_dosen_wali" class="mr-2">
                                <span class="text-gray-700">Dosen Wali</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateEmail() {
            const username = document.getElementById('username').value;
            const domain = '@it.lecturer.pens.ac.id';
            document.getElementById('email').value = username + domain;  // Set email hidden field
        }
    </script>
</x-app-layout>
