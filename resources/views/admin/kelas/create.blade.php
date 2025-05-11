<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-blue-900 leading-tight p-4 rounded-lg shadow-md bg-gradient-to-r from-blue-200 to-blue-400">
            {{ __('Tambah Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-md mb-6">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

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

                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf

                        <!-- Dropdown Class Year -->
                        <div class="form-group mb-4">
                            <label for="class_year" class="block text-sm font-medium text-gray-700">Class Year</label>
                            <select name="class_year" id="class_year" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <!-- Dropdown Program (D3/D4) -->
                        <div class="form-group mb-4">
                            <label for="program" class="block text-sm font-medium text-gray-700">Program</label>
                            <select name="program" id="program" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="D3" selected>D3</option>
                                <option value="D4">D4</option>
                                <option value="D3 PJJ">D3 PJJ</option>
                                <option value="D4 PJJ">D4 PJJ</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>

                        <!-- Dropdown Prodi (IT, ELIN, ELKA) -->
                        <div class="form-group mb-4">
                            <label for="prodi" class="block text-sm font-medium text-gray-700">Prodi</label>
                            <select name="prodi" id="prodi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="IT" selected>IT</option>
                                <option value="ELIN">ELIN</option>
                                <option value="ELKA">ELKA</option>
                                <!-- Add other prodi options here -->
                            </select>
                        </div>

                        <!-- Dropdown Class Type (A, B, C, etc.) -->
                        <div class="form-group mb-4">
                            <label for="class_type" class="block text-sm font-medium text-gray-700">Class Type</label>
                            <select name="class_type" id="class_type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="A" selected>A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                                <option value="I">I</option>
                                <option value="J">J</option>
                            </select>
                        </div>

                        <!-- Hidden Input for Nama Kelas -->
                        <input type="hidden" name="nama_kelas" id="nama_kelas">

                        <!-- Status Kelas -->
                        <div class="form-group mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Kelas</label>
                            <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="inactive" selected>Inactive</option>
                                <option value="active">Active</option>
                            </select>
                        </div>

                        <!-- Dosen Wali -->
                        <div class="form-group mb-4" id="dosen_wali_div">
                            <label for="id_dosen_wali" class="block text-sm font-medium text-gray-700">Dosen Wali</label>
                            <select name="id_dosen_wali" id="id_dosen_wali" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Dosen Wali --</option>
                                @foreach($dosen as $dosenItem)
                                    <option value="{{ $dosenItem->id_dosen }}">{{ $dosenItem->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="mt-4 inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-6 rounded-full shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-500 transition-all duration-300 ease-in-out">
                            Simpan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to update the class name based on dropdown selections
        function updateClassName() {
            var classYear = document.getElementById('class_year').value;
            var program = document.getElementById('program').value;
            var prodi = document.getElementById('prodi').value;
            var classType = document.getElementById('class_type').value;
            var className = classYear + ' ' + program + ' ' + prodi + ' ' + classType;
            document.getElementById('nama_kelas').value = className; // Update the hidden input field
        }

        // Attach event listeners to the dropdowns
        document.getElementById('class_year').addEventListener('change', updateClassName);
        document.getElementById('program').addEventListener('change', updateClassName);
        document.getElementById('prodi').addEventListener('change', updateClassName);
        document.getElementById('class_type').addEventListener('change', updateClassName);

        // Update class name on page load
        window.addEventListener('load', updateClassName);
    </script>

</x-app-layout>
