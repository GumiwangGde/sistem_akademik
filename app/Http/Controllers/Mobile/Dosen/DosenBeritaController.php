<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class DosenBeritaController extends Controller
{
    public function index(Request $request)
    {
        $beritaUntukDosen = Berita::where('status', 'terbit')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->whereIn('target_role', ['dosen', 'semua'])
            ->latest('published_at') 
            ->paginate(10); 

        return response()->json([
            'berita' => $beritaUntukDosen,
            'message' => 'Daftar berita untuk dosen berhasil diambil.'
        ]);
    }

    public function show($slug) 
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'terbit')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->whereIn('target_role', ['dosen', 'semua'])
            ->firstOrFail();

        return response()->json([
            'berita' => $berita,
        ]);
    }
}