<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-blue-900 leading-tight p-4 rounded-lg shadow-md bg-gradient-to-r from-blue-200 to-blue-400">
            {{ __('Edit Kelas') }}
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

                    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Dropdown Class Year -->
                        <div class="form-group mb-4">
                            <label for="class_year" class="block text-sm font-medium text-gray-700">Class Year</label>
                            <select name="class_year" id="class_year" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ $kelas->class_year == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $kelas->class_year == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $kelas->class_year == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $kelas->class_year == '4' ? 'selected' : '' }}>4</option>
                            </select>
                        </div>

                        <!-- Dropdown Program (D3/D4) -->
                        <div class="form-group mb-4">
                            <label for="program" class="block text-sm font-medium text-gray-700">Program</label>
                            <select name="program" id="program" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="D3" {{ $kelas->program == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ $kelas->program == 'D4' ? 'selected' : '' }}>D4</option>
                            </select>
                        </div>

                        <!-- Dropdown Prodi (IT, ELIN, ELKA) -->
                        <div class="form-group mb-4">
                            <label for="prodi" class="block text-sm font-medium text-gray-700">Prodi</label>
                            <select name="prodi" id="prodi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="IT" {{ $kelas->prodi == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="ELIN" {{ $kelas->prodi == 'ELIN' ? 'selected' : '' }}>ELIN</option>
                                <option value="ELKA" {{ $kelas->prodi == 'ELKA' ? 'selected' : '' }}>ELKA</option>
                                <!-- Add other prodi options here -->
                            </select>
                        </div>

                        <!-- Dropdown Class Type (A, B, C, etc.) -->
                        <div class="form-group mb-4">
                            <label for="class_type" class="block text-sm font-medium text-gray-700">Class Type</label>
                            <select name="class_type" id="class_type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="A" {{ $kelas->class_type == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $kelas->class_type == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ $kelas->class_type == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ $kelas->class_type == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ $kelas->class_type == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ $kelas->class_type == 'F' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ $kelas->class_type == 'G' ? 'selected' : '' }}>G</option>
                                <option value="H" {{ $kelas->class_type == 'H' ? 'selected' : '' }}>H</option>
                                <option value="I" {{ $kelas->class_type == 'I' ? 'selected' : '' }}>I</option>
                                <option value="J" {{ $kelas->class_type == 'J' ? 'selected' : '' }}>J</option>
                            </select>
                        </div>

                        <!-- Hidden Input for Nama Kelas -->
                        <input type="hidden" name="nama_kelas" id="nama_kelas" value="{{ $kelas->nama_kelas }}">

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

        // Update the class name dynamically
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
