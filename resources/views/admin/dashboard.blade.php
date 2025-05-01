
@vite('resources/css/app.css')
@extends('admin.layout')


@section('header')
    {{ __('Dashboard Admin') }}
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-6 rounded-lg shadow">
            <div class="text-blue-600 text-xl font-semibold mb-2">Total Mahasiswa</div>
            <div class="text-3xl font-bold">{{ $mahasiswaCount }}</div>
            <div class="mt-4">
                <a href="{{ route('admin.mahasiswa.index') }}" class="text-blue-600 hover:text-blue-800">
                    Lihat Semua &rarr;
                </a>
            </div>
        </div>

        <div class="bg-green-50 p-6 rounded-lg shadow">
            <div class="text-green-600 text-xl font-semibold mb-2">Total Dosen</div>
            <div class="text-3xl font-bold">{{ $dosenCount }}</div>
            <div class="mt-4">
                <a href="{{ route('admin.dosen.index') }}" class="text-green-600 hover:text-green-800">
                    Lihat Semua &rarr;
                </a>
            </div>
        </div>

        <div class="bg-yellow-50 p-6 rounded-lg shadow">
            <div class="text-yellow-600 text-xl font-semibold mb-2">Total Mata Kuliah</div>
            <div class="text-3xl font-bold">{{ $mataKuliahCount }}</div>
            <div class="mt-4">
                <a href="#" class="text-yellow-600 hover:text-yellow-800">
                    Lihat Semua &rarr;
                </a>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.mahasiswa.index') }}" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold mb-2">Kelola Mahasiswa</h4>
                <p class="text-gray-600">Tambah, edit, dan hapus data mahasiswa</p>
            </a>
            
            <a href="{{ route('admin.dosen.index') }}" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold mb-2">Kelola Dosen</h4>
                <p class="text-gray-600">Tambah, edit, dan hapus data dosen</p>
            </a>
            
            <a href="#" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold mb-2">Kelola Mata Kuliah</h4>
                <p class="text-gray-600">Tambah, edit, dan hapus data mata kuliah</p>
            </a>
            
            <a href="#" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold mb-2">Kelola Jadwal Kuliah</h4>
                <p class="text-gray-600">Atur jadwal mata kuliah</p>
            </a>
            
            <a href="#" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold mb-2">Kelola Kelas</h4>
                <p class="text-gray-600">Atur kelas dan dosen wali</p>
            </a>
        </div>
    </div>
@endsection