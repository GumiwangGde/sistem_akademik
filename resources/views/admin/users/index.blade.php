<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Manajemen Users') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-200 to-blue-400 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Tab Navigation -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button onclick="showTab('admin')" id="admin-tab" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    <i class="fas fa-user-shield mr-2"></i>
                                    Admin ({{ count($admins) }})
                                </button>
                                <button onclick="showTab('dosen')" id="dosen-tab" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                                    Dosen ({{ count($lecturers) }})
                                </button>
                                <button onclick="showTab('mahasiswa')" id="mahasiswa-tab" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    <i class="fas fa-user-graduate mr-2"></i>
                                    Mahasiswa ({{ count($students) }})
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Admin Table -->
                    <div id="admin-content" class="tab-content">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-blue-800">Daftar Admin</h3>
                            <p class="text-gray-600">Pengguna dengan domain @it.admin.pens.ac.id</p>
                        </div>
                        
                        @if(count($admins) > 0)
                            <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                                <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold">
                                            <th class="px-6 py-4 text-left">ID</th>
                                            <th class="px-6 py-4 text-left">Nama</th>
                                            <th class="px-6 py-4 text-left">Email</th>
                                            <th class="px-6 py-4 text-left">Bergabung</th>
                                            <th class="px-6 py-4 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($admins as $admin)
                                            <tr class="border-b hover:bg-red-50 transition-colors duration-200">
                                                <td class="border px-4 py-3 font-medium">{{ $admin->id }}</td>
                                                <td class="border px-4 py-3">{{ $admin->name }}</td>
                                                <td class="border px-4 py-3">{{ $admin->email }}</td>
                                                <td class="border px-4 py-3">{{ $admin->created_at->format('d/m/Y') }}</td>
                                                <td class="border px-4 py-3">
                                                    <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition duration-300">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-users text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">Belum ada admin terdaftar</p>
                            </div>
                        @endif
                    </div>

                    <!-- Dosen Table -->
                    <div id="dosen-content" class="tab-content hidden">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-blue-800">Daftar Dosen</h3>
                            <p class="text-gray-600">Pengguna dengan domain @it.lecturer.pens.ac.id</p>
                        </div>
                        
                        @if(count($lecturers) > 0)
                            <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                                <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold">
                                            <th class="px-6 py-4 text-left">ID</th>
                                            <th class="px-6 py-4 text-left">Nama</th>
                                            <th class="px-6 py-4 text-left">Email</th>
                                            <th class="px-6 py-4 text-left">Bergabung</th>
                                            <th class="px-6 py-4 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lecturers as $lecturer)
                                            <tr class="border-b hover:bg-green-50 transition-colors duration-200">
                                                <td class="border px-4 py-3 font-medium">{{ $lecturer->id }}</td>
                                                <td class="border px-4 py-3">{{ $lecturer->name }}</td>
                                                <td class="border px-4 py-3">{{ $lecturer->email }}</td>
                                                <td class="border px-4 py-3">{{ $lecturer->created_at->format('d/m/Y') }}</td>
                                                <td class="border px-4 py-3">
                                                    <form action="{{ route('admin.users.destroy', $lecturer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition duration-300">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-chalkboard-teacher text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">Belum ada dosen terdaftar</p>
                            </div>
                        @endif
                    </div>

                    <!-- Mahasiswa Table -->
                    <div id="mahasiswa-content" class="tab-content hidden">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-blue-800">Daftar Mahasiswa</h3>
                            <p class="text-gray-600">Pengguna dengan domain @it.student.pens.ac.id</p>
                        </div>
                        
                        @if(count($students) > 0)
                            <div class="overflow-x-auto rounded-lg shadow-md bg-blue-50">
                                <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold">
                                            <th class="px-6 py-4 text-left">ID</th>
                                            <th class="px-6 py-4 text-left">Nama</th>
                                            <th class="px-6 py-4 text-left">Email</th>
                                            <th class="px-6 py-4 text-left">Bergabung</th>
                                            <th class="px-6 py-4 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr class="border-b hover:bg-blue-50 transition-colors duration-200">
                                                <td class="border px-4 py-3 font-medium">{{ $student->id }}</td>
                                                <td class="border px-4 py-3">{{ $student->name }}</td>
                                                <td class="border px-4 py-3">{{ $student->email }}</td>
                                                <td class="border px-4 py-3">{{ $student->created_at->format('d/m/Y') }}</td>
                                                <td class="border px-4 py-3">
                                                    <form action="{{ route('admin.users.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition duration-300">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-user-graduate text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">Belum ada mahasiswa terdaftar</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all tab buttons
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Add active state to selected tab button
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        // Set default active tab (Admin)
        document.addEventListener('DOMContentLoaded', function() {
            showTab('admin');
        });

        // Show success/error messages
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
</x-app-layout>