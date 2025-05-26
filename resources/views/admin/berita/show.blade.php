<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Berita') }}
            </h2>
            <div>
                <a href="{{ route('admin.berita.edit', $berita->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $berita->judul }}</h3>
                    
                    <div class="text-sm text-gray-500 mb-4">
                        <span>Dibuat oleh: <strong>{{ $berita->user->name ?? 'N/A' }}</strong></span> |
                        <span>Pada: {{ $berita->created_at->translatedFormat('l, d F Y H:i') }}</span> |
                        <span>Status: 
                            @if($berita->status == 'terbit')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terbit</span>
                                @if($berita->published_at && $berita->published_at->isFuture())
                                    (Dijadwalkan: {{ $berita->published_at->translatedFormat('d M Y, H:i') }})
                                @elseif($berita->published_at)
                                     (Terbit pada: {{ $berita->published_at->translatedFormat('d M Y, H:i') }})
                                @endif
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                            @endif
                        </span> |
                        <span>Target: 
                            @if($berita->target_role == 'dosen')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Dosen</span>
                            @elseif($berita->target_role == 'mahasiswa')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Mahasiswa</span>
                            @elseif($berita->target_role == 'semua')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Semua</span>
                            @else
                                {{ ucfirst($item->target_role) }}
                            @endif
                        </span>
                    </div>

                    @if($berita->gambar_url)
                        <div class="mb-6">
                            <img src="{{ $berita->gambar_url }}" alt="Gambar Berita: {{ $berita->judul }}" class="w-full h-auto max-h-96 object-cover rounded-md shadow">
                        </div>
                    @endif

                    <div class="prose prose-sm sm:prose lg:prose-lg xl:prose-xl max-w-none text-gray-700">
                        {!! $berita->isi !!} {{-- Pastikan isi sudah disanitasi --}}
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 text-xs text-gray-500">
                        Slug: {{ $berita->slug }} <br>
                        Terakhir diperbarui: {{ $berita->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
