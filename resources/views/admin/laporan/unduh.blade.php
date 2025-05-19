<x-app-layout>
    <x-slot name="header">
        <div class="ml-72 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-6 px-8 rounded-b-lg shadow-lg">
            <h2 class="font-bold text-3xl">{{ __('Unduh Laporan Sistem') }}</h2>
            <p class="mt-2 text-sm">{{ __('Hasilkan laporan ringkasan data sistem universitas') }}</p>
        </div>
    </x-slot>

    <div class="py-12 pl-80 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informasi Laporan -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800">{{ __('Laporan Ringkasan Sistem') }}</h3>
                <p class="mt-2 text-gray-600">
                    {{ __('Laporan ini berisi ringkasan metrik sistem, termasuk jumlah pengguna (admin, dosen, mahasiswa), dosen, mahasiswa, mata kuliah, kelas, dan ruangan, per ') }}{{ now()->format('d F Y') }}.
                </p>
            </div>

            <!-- Tombol Unduh -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Unduh Laporan PDF') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('Klik tombol di bawah untuk mengunduh laporan dalam format PDF.') }}</p>
                <a href="{{ route('admin.laporan.unduh') }}" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                    {{ __('Unduh Laporan PDF') }}
                </a>
            </div>

            <!-- Kembali ke Dashboard -->
            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">{{ __('Kembali ke Dashboard') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>