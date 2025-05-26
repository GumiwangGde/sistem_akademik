<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class MahasiswaBeritaController extends Controller
{
    public function index(Request $request)
    {
        $beritaUntukMahasiswa = Berita::where('status', 'terbit')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->whereIn('target_role', ['mahasiswa', 'semua'])
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'berita' => $beritaUntukMahasiswa,
            'message' => 'Daftar berita untuk mahasiswa berhasil diambil.'
        ]);
        // return view('mobile.mahasiswa.berita.index', compact('beritaUntukMahasiswa'));
    }

    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'terbit')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->whereIn('target_role', ['mahasiswa', 'semua'])
            ->firstOrFail();

        return response()->json([
            'berita' => $berita,
        ]);
        // return view('mobile.mahasiswa.berita.show', compact('berita'));
    }
}