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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $berita = $query->paginate(10); // Paginasi, 10 item per halaman

        // Anda perlu membuat view untuk ini, misalnya: admin.berita.index
        return view('admin.berita.index', compact('berita'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Anda perlu membuat view untuk ini, misalnya: admin.berita.create
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:berita,judul',
            'isi' => 'required|string',
            'gambar_url' => 'nullable|url|max:2048', // Jika menggunakan URL
            // 'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Jika upload file
            'target_role' => 'required|in:dosen,mahasiswa,semua',
            'status' => 'required|in:draft,terbit',
            'published_at' => 'nullable|date',
        ]);

        $berita = new Berita();
        $berita->user_id = Auth::id(); // ID admin yang sedang login
        $berita->judul = $validatedData['judul'];
        // Slug akan dibuat otomatis oleh model jika tidak diisi
        // $berita->slug = Str::slug($validatedData['judul']); 
        $berita->isi = $validatedData['isi'];
        $berita->target_role = $validatedData['target_role'];
        $berita->status = $validatedData['status'];
        
        if (!empty($validatedData['published_at'])) {
            $berita->published_at = Carbon::parse($validatedData['published_at']);
        } else if ($validatedData['status'] === 'terbit') {
            // Jika status terbit dan published_at tidak diisi, set ke waktu sekarang
            $berita->published_at = now();
        }

        // Logika untuk gambar_url (jika admin input URL)
        if (!empty($validatedData['gambar_url'])) {
            $berita->gambar_url = $validatedData['gambar_url'];
        }

        // Contoh logika jika Anda ingin upload file gambar (perlu setup disk di filesystems.php)
        /*
        if ($request->hasFile('gambar_file')) {
            // Hapus gambar lama jika ada (saat update)
            // if ($berita->exists && $berita->gambar_url) { Storage::disk('public')->delete($berita->gambar_url); }
            $path = $request->file('gambar_file')->store('berita_images', 'public');
            $berita->gambar_url = $path;
        }
        */

        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function show(Berita $berita)
    {
        // Untuk admin melihat detail, atau bisa juga untuk preview publik
        // Anda perlu membuat view untuk ini, misalnya: admin.berita.show
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function edit(Berita $berita)
    {
        // Anda perlu membuat view untuk ini, misalnya: admin.berita.edit
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Berita $berita)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:berita,judul,' . $berita->id,
            'isi' => 'required|string',
            'gambar_url' => 'nullable|url|max:2048',
            // 'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_role' => 'required|in:dosen,mahasiswa,semua',
            'status' => 'required|in:draft,terbit',
            'published_at' => 'nullable|date',
        ]);

        $berita->user_id = Auth::id(); // Update user_id jika admin lain yang mengedit (opsional)
        $berita->judul = $validatedData['judul'];
        // Slug akan diupdate otomatis oleh model jika judul berubah dan slug tidak diisi manual
        // $berita->slug = Str::slug($validatedData['judul']);
        $berita->isi = $validatedData['isi'];
        $berita->target_role = $validatedData['target_role'];
        $berita->status = $validatedData['status'];

        if (!empty($validatedData['published_at'])) {
            $berita->published_at = Carbon::parse($validatedData['published_at']);
        } elseif ($validatedData['status'] === 'terbit' && is_null($berita->published_at)) {
            // Jika status diubah ke terbit dan belum pernah ada published_at, set ke waktu sekarang
            $berita->published_at = now();
        } elseif ($validatedData['status'] === 'draft') {
            // Jika diubah ke draft, mungkin Anda ingin menghapus published_at atau membiarkannya
            // $berita->published_at = null; // Opsional
        }
        
        if (!empty($validatedData['gambar_url'])) {
            $berita->gambar_url = $validatedData['gambar_url'];
        } elseif ($request->exists('hapus_gambar_url') && $request->hapus_gambar_url == '1') {
             // Jika ada checkbox untuk menghapus gambar_url
            $berita->gambar_url = null;
        }

        // Contoh logika jika Anda ingin upload file gambar (dan menghapus yang lama)
        /*
        if ($request->hasFile('gambar_file')) {
            // Hapus gambar lama jika ada
            if ($berita->gambar_url) {
                Storage::disk('public')->delete($berita->gambar_url);
            }
            $path = $request->file('gambar_file')->store('berita_images', 'public');
            $berita->gambar_url = $path;
        } elseif ($request->boolean('hapus_gambar_file')) { // Jika ada input untuk menghapus file gambar
             if ($berita->gambar_url) {
                Storage::disk('public')->delete($berita->gambar_url);
                $berita->gambar_url = null;
            }
        }
        */

        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berita $berita)
    {
        // Contoh logika jika Anda menyimpan file gambar dan ingin menghapusnya dari storage
        /*
        if ($berita->gambar_url) {
            Storage::disk('public')->delete($berita->gambar_url);
        }
        */
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
