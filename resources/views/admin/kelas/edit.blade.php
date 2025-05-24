{{-- resources/views/admin/kelas/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ __('Edit Kelas') }}</h1>
                            <p class="text-sm text-gray-500">{{ $kelas->nama_kelas }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-5 flex w-full overflow-hidden bg-white rounded-lg shadow-md">
                            <div class="flex items-center justify-center w-12 bg-green-500">
                                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"></path>
                                </svg>
                            </div>
                            <div class="px-4 py-3 -mx-3">
                                <div class="mx-3">
                                    <span class="font-semibold text-green-500">Sukses!</span>
                                    <p class="text-sm text-gray-600">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 flex w-full overflow-hidden bg-white rounded-lg shadow-md">
                            <div class="flex items-center justify-center w-12 bg-red-500">
                                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM22 27.3333H18V23.3333H22V27.3333ZM22 19.9999H18V12.6666H22V19.9999Z"></path>
                                </svg>
                            </div>
                            <div class="px-4 py-3 -mx-3">
                                <div class="mx-3">
                                    <span class="font-semibold text-red-500">Terjadi kesalahan:</span>
                                    <ul class="text-sm text-gray-600 mt-1 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('kelas.update', $kelas) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dropdown Class Year -->
                            <div>
                                <label for="class_year" class="block text-sm font-medium text-gray-700 mb-1">Tahun Angkatan</label>
                                <select name="class_year" id="class_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="1" {{ $kelas->class_year == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $kelas->class_year == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ $kelas->class_year == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ $kelas->class_year == '4' ? 'selected' : '' }}>4</option>
                                </select>
                            </div>

                            <!-- Dropdown Program (D3/D4) -->
                            <div>
                                <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                                <select name="program" id="program" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="D3" {{ $kelas->program == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="D4" {{ $kelas->program == 'D4' ? 'selected' : '' }}>D4</option>
                                    <option value="D3 PJJ" {{ $kelas->program == 'D3 PJJ' ? 'selected' : '' }}>D3 PJJ</option>
                                    <option value="D4 PJJ" {{ $kelas->program == 'D4 PJJ' ? 'selected' : '' }}>D4 PJJ</option>
                                    <option value="S2" {{ $kelas->program == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ $kelas->program == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>

                            <!-- Dropdown Prodi (IT, ELIN, ELKA) -->
                            <div>
                                <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                <select name="prodi" id="prodi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="IT" {{ $kelas->prodi == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="ELIN" {{ $kelas->prodi == 'ELIN' ? 'selected' : '' }}>ELIN</option>
                                    <option value="ELKA" {{ $kelas->prodi == 'ELKA' ? 'selected' : '' }}>ELKA</option>
                                    <!-- Add other prodi options here -->
                                </select>
                            </div>

                            <!-- Dropdown Class Type (A, B, C, etc.) -->
                            <div>
                                <label for="class_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kelas</label>
                                <select name="class_type" id="class_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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

                            <!-- Status Kelas -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Kelas</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="inactive" {{ $kelas->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="active" {{ $kelas->status == 'active' ? 'selected' : '' }}>Active</option>
                                </select>
                            </div>

                            <!-- Dosen Wali -->
                            <div id="dosen_wali_div" style="{{ $kelas->status == 'inactive' ? 'display: none;' : '' }}">
                                <label for="id_dosen_wali" class="block text-sm font-medium text-gray-700 mb-1">Dosen Wali</label>
                                <select name="id_dosen_wali" id="id_dosen_wali" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Dosen Wali --</option>
                                    @foreach($dosen as $dosenItem)
                                        <option value="{{ $dosenItem->id_dosen }}" {{ $kelas->id_dosen_wali == $dosenItem->id_dosen ? 'selected' : '' }}>{{ $dosenItem->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Hidden Input for Nama Kelas -->
                        <input type="hidden" name="nama_kelas" id="nama_kelas" value="{{ $kelas->nama_kelas }}">

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('kelas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:shadow-outline-gray transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:shadow-outline-blue transition ease-in-out duration-150">
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