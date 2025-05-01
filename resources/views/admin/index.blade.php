@extends('admin.layout')

@section('header')
    {{ __('Data Mahasiswa') }}
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Mahasiswa</h2>
        <a href="{{ route('admin.mahasiswa.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Tambah Mahasiswa
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswa as $mhs)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $mhs->nim }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $mhs->nama }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $mhs->user->email ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $mhs->kelas->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($mhs->user && $mhs->user->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Belum Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.mahasiswa.show', $mhs) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                            <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                        Tidak ada data mahasiswa
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $mahasiswa->links() }}
    </div>
@endsection