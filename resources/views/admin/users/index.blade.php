<x-app-layout>
    <x-slot name="header">
        <div class="ml-48">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Daftar Users') }}
            </h2>
        </div>
    </x-slot>

    <!-- Latar belakang keseluruhan dengan gradasi biru -->
    <div class="pl-80 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Daftar Users</h1>

                    <!-- Tabel dengan latar belakang biru -->
                    <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                        <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                    <th class="px-6 py-4 text-left">ID</th>
                                    <th class="px-6 py-4 text-left">Nama</th>
                                    <th class="px-6 py-4 text-left">Email</th>
                                    <th class="px-6 py-4 text-left">Created At</th>
                                    <th class="px-6 py-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b hover:bg-blue-100">
                                        <td class="border px-4 py-2">{{ $user->id }}</td>
                                        <td class="border px-4 py-2">{{ $user->name }}</td>
                                        <td class="border px-4 py-2">{{ $user->email }}</td>
                                        <td class="border px-4 py-2">{{ $user->created_at }}</td>
                                        <td class="border px-4 py-2">
                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300 ease-in-out hover:underline">
                                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
