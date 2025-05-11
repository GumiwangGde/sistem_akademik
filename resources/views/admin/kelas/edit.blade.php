{{-- resources/views/admin/kelas/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-blue-900 leading-tight p-4 rounded-lg shadow-md bg-gradient-to-r from-blue-200 to-blue-400">
            {{ __('Edit Kelas') }}
        </h2>
    </x-slot>

    <!-- Latar belakang keseluruhan halaman putih -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Success jika ada --}}
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

                    {{-- Pesan Error jika ada --}}
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Error!</strong> 
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form edit kelas --}}
                    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Kelas -->
                        <div class="form-group mb-4">
                            <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" value="{{ $kelas->nama_kelas }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <!-- Status Kelas -->
                        <div class="form-group mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Kelas</label>
                            <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="inactive" {{ $kelas->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ $kelas->status == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>

                        <!-- Dosen Wali -->
                        <div class="form-group mb-4" id="dosen_wali_div" style="{{ $kelas->status == 'inactive' ? 'display: none;' : '' }}">
                            <label for="id_dosen_wali" class="block text-sm font-medium text-gray-700">Dosen Wali</label>
                            <select name="id_dosen_wali" id="id_dosen_wali" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Dosen Wali --</option>
                                @foreach($dosen as $dosenItem)
                                    <option value="{{ $dosenItem->id_dosen }}" {{ $kelas->id_dosen_wali == $dosenItem->id_dosen ? 'selected' : '' }}>{{ $dosenItem->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="mt-4 inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out">
                            Simpan Perubahan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Menyembunyikan atau menampilkan input dosen wali tergantung pada status kelas
        document.getElementById('status').addEventListener('change', function () {
            const dosenWaliDiv = document.getElementById('dosen_wali_div');
            if (this.value === 'active') {
                dosenWaliDiv.style.display = 'block'; // Menampilkan input dosen wali
            } else {
                dosenWaliDiv.style.display = 'none'; // Menyembunyikan input dosen wali
                document.getElementById('id_dosen_wali').value = ''; // Reset pilihan dosen wali
            }
        });
    </script>

</x-app-layout>