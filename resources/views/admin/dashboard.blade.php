<x-app-layout>
    {{-- Header Title Only --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Admin') }}
        </h2>
        
    </x-slot>
    
    {{-- Content --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                    // Ambil role pertama user
                    $role = Auth::user()->getRoleNames()->first();
                    @endphp
                    {{-- Tampilkan pesan sesuai role --}}
                    @if($role == 'admin')
                        {{ __("You're logged in as Admin!") }}
                    @elseif($role == 'dosen')
                        {{ __("You're logged in as Dosen!") }}
                    @elseif($role == 'mahasiswa')
                        {{ __("You're logged in as Mahasiswa!") }}
                    @else
                        {{ __("You're logged in!") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>