{{-- resources/views/admin/matakuliah/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="ml-48">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Tambah Matakuliah') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 pl-80 bg-gradient-to-r from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-100 to-blue-300 overflow-hidden shadow-lg sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Terjadi kesalahan:</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('matakuliah.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select id="kelas_id" name="kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $kelas_item)
                                    <option value="{{ $kelas_item->id_kelas }}" {{ old('kelas_id') == $kelas_item->id_kelas ? 'selected' : '' }}>
                                        {{ $kelas_item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_dosen" class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengajar</label>
                            <select id="id_dosen" name="id_dosen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $dosen_item)
                                    <option value="{{ $dosen_item->id_dosen }}" {{ old('id_dosen') == $dosen_item->id_dosen ? 'selected' : '' }}>
                                        {{ $dosen_item->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="kode_mk" class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Kuliah</label>
                            <input type="text" id="kode_mk" name="kode_mk" value="{{ old('kode_mk') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Contoh: MK001" required />
                        </div>

                        <div>
                            <label for="nama_mk" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah</label>
                            <input type="text" id="nama_mk" name="nama_mk" value="{{ old('nama_mk') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Masukkan nama mata kuliah" required />
                        </div>

                        <div>
                            <label for="sks" class="block text-sm font-medium text-gray-700 mb-1">SKS</label>
                            <input type="number" id="sks" name="sks" value="{{ old('sks') }}" min="1" max="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                            <select id="semester" name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="Semester {{ $i }}" {{ old('semester') == "Semester {$i}" ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                            <select id="hari" name="hari" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                    <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="ruang_id" class="block text-sm font-medium text-gray-700 mb-1">Ruang</label>
                            <select id="ruang_id" name="ruang_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="">-- Pilih Ruang --</option>
                                @foreach($ruang as $ruang_item)
                                    <option value="{{ $ruang_item->id }}" {{ old('ruang_id') == $ruang_item->id ? 'selected' : '' }}>
                                        {{ $ruang_item->nama_ruang }} (Kapasitas: {{ $ruang_item->kapasitas }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('matakuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 active:from-blue-700 active:to-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-save mr-2"></i> Simpan Mata Kuliah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>