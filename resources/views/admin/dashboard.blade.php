<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="ml-72">
            <h2 class="font-semibold text-3xl text-blue-800 leading-tight">
                {{ __('Dashboard Admin') }}
            </h2>
        </div>
    </x-slot>
    
    <!-- Dashboard Content -->
    <div class="py-12 pl-80"> <!-- Adjusted padding to avoid overlap with sidebar -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <!-- Role-based Message -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">{{ __("Welcome to your Dashboard!") }}</h3>
                        <p class="mt-2 text-gray-600 text-md">
                            {{ __("You're logged in as Admin! You have full access to manage the system.") }}
                        </p>
                    </div>

                    <!-- Dashboard Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Total Users Card -->
                        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center">
                            <h4 class="text-xl font-semibold text-blue-800">{{ __('Total Users') }}</h4>
                            <p class="mt-2 text-gray-600">Manage all registered users in the system.</p>
                            <h2 class="text-3xl font-bold text-blue-600">90</h2>
                            <a href="{{ route('admin.users.index') }}" class="mt-4 bg-blue-600 text-white rounded-full px-6 py-2 hover:bg-blue-700 transition duration-300">Manage Users</a>
                        </div>

                        <!-- Total Dosen Card -->
                        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center">
                            <h4 class="text-xl font-semibold text-green-800">{{ __('Total Dosen') }}</h4>
                            <p class="mt-2 text-gray-600">Manage all lecturers in the system.</p>
                            <h2 class="text-3xl font-bold text-green-600">89</h2>
                            <a href="{{ route('dosen.index') }}" class="mt-4 bg-green-600 text-white rounded-full px-6 py-2 hover:bg-green-700 transition duration-300">Manage Dosen</a>
                        </div>

                        <!-- Total Mahasiswa -->
                        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center">
                            <h4 class="text-xl font-semibold text-yellow-800">{{ __('Total Mahasiswa') }}</h4>
                            <p class="mt-2 text-gray-600">Manage all students in the system.</p>
                            <h2 class="text-3xl font-bold text-yellow-600">89</h2>
                            <a href="{{ route('mahasiswa.index') }}" class="mt-4 bg-yellow-600 text-white rounded-full px-6 py-2 hover:bg-yellow-700 transition duration-300">Manage Mahasiswa</a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-8 bg-gray-50 p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Manage Users Action -->
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center hover:bg-blue-50 transition duration-300">
                                <h5 class="text-lg font-semibold text-blue-800">Manage Users</h5>
                                <p class="text-gray-600">View and manage registered users.</p>
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">Go to Users</a>
                            </div>

                            <!-- Manage Dosen Action -->
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center hover:bg-green-50 transition duration-300">
                                <h5 class="text-lg font-semibold text-green-800">Manage Dosen</h5>
                                <p class="text-gray-600">View and manage lecturers.</p>
                                <a href="{{ route('dosen.index') }}" class="text-green-600 hover:underline mt-2 inline-block">Go to Dosen</a>
                            </div>

                            <!-- View Matakuliah -->
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center hover:bg-yellow-50 transition duration-300">
                                <h5 class="text-lg font-semibold text-yellow-800">Matakuliah</h5>
                                <p class="text-gray-600">View and manage courses.</p>
                                <a href="{{ route('matakuliah.index') }}" class="text-yellow-600 hover:underline mt-2 inline-block">View Courses</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>