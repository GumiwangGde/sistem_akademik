<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class DosenBeritaController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil berita yang statusnya 'terbit' dan target_role-nya 'dosen' atau 'semua'
        // serta published_at sudah lewat atau null
        $beritaUntukDosen = Berita::where('status', 'terbit')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->whereIn('target_role', ['dosen', 'semua'])
            ->latest('published_at') // Urutkan berdasarkan tanggal terbit terbaru
            ->paginate(10); // Misalnya, paginasi

        // Untuk API mobile, Anda mungkin akan mengembalikan JSON
        return response()->json([
            'berita' => $beritaUntukDosen,
            'message' => 'Daftar berita untuk dosen berhasil diambil.'
        ]);

        // Jika untuk web view:
        // return view('mobile.dosen.berita.index', compact('beritaUntukDosen'));
    }

    public function show($slug) // Atau menggunakan ID
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
        // return view('mobile.dosen.berita.show', compact('berita'));
    }
}