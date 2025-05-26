<?php

namespace App\Http\Controllers; // Pastikan namespace sesuai dengan lokasi file

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\User; // Untuk mengambil daftar admin/user pembuat berita
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Jika Anda akan mengimplementasikan upload file gambar
use Illuminate\Support\Str; // Untuk slug
use Carbon\Carbon;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with('user')->latest(); // Urutkan berdasarkan terbaru

        // Fitur pencarian (opsional)
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Fitur filter berdasarkan target role (opsional)
        if ($request->has('target_role') && $request->target_role != '') {
            $query->where('target_role', $request->target_role);
        }

        // Fitur filter berdasarkan status (opsional)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $berita = $query->paginate(10); 

        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:berita,judul',
            'isi' => 'required|string',
            'gambar_url' => 'nullable|url|max:2048', 
            'target_role' => 'required|in:dosen,mahasiswa,semua',
            'status' => 'required|in:draft,terbit',
            'published_at' => 'nullable|date',
        ]);

        $berita = new Berita();
        $berita->user_id = Auth::id(); 
        $berita->judul = $validatedData['judul'];
        $berita->slug = Str::slug($validatedData['judul']); 
        $berita->isi = $validatedData['isi'];
        $berita->target_role = $validatedData['target_role'];
        $berita->status = $validatedData['status'];
        
        if (!empty($validatedData['published_at'])) {
            $berita->published_at = Carbon::parse($validatedData['published_at']);
        } else if ($validatedData['status'] === 'terbit') {
            $berita->published_at = now();
        }

        if (!empty($validatedData['gambar_url'])) {
            $berita->gambar_url = $validatedData['gambar_url'];
        }

        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita'));
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:berita,judul,' . $berita->id,
            'isi' => 'required|string',
            'gambar_url' => 'nullable|url|max:2048',
            'target_role' => 'required|in:dosen,mahasiswa,semua',
            'status' => 'required|in:draft,terbit',
            'published_at' => 'nullable|date',
        ]);

        $berita->user_id = Auth::id(); 
        $berita->judul = $validatedData['judul'];
        $berita->slug = Str::slug($validatedData['judul']); 
        $berita->isi = $validatedData['isi'];
        $berita->target_role = $validatedData['target_role'];
        $berita->status = $validatedData['status'];

        if (!empty($validatedData['published_at'])) {
            $berita->published_at = Carbon::parse($validatedData['published_at']);
        } elseif ($validatedData['status'] === 'terbit' && is_null($berita->published_at)) {
            $berita->published_at = now();
        } elseif ($validatedData['status'] === 'draft') {
            $berita->published_at = null; 
        }
        
        if (!empty($validatedData['gambar_url'])) {
            $berita->gambar_url = $validatedData['gambar_url'];
        } elseif ($request->exists('hapus_gambar_url') && $request->hapus_gambar_url == '1') {
            $berita->gambar_url = null;
        }

        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
