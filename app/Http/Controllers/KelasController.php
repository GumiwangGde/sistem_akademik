<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        // Mengambil data kelas beserta dosen wali
        $kelas = Kelas::with('dosenWali')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    // Menampilkan form untuk membuat kelas baru
    public function create()
    {
        // Mengambil semua dosen untuk dipilih sebagai dosen wali
        $dosen = Dosen::all();
        return view('admin.kelas.create', compact('dosen'));
    }

    // Menyimpan kelas baru ke dalam database
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen', // Dosen Wali boleh kosong jika status inactive
        ]);

        // Memastikan dosen wali hanya diisi jika status kelas active
        if ($validated['status'] == 'active' && !$validated['id_dosen_wali']) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.']);
        }

        // Menyimpan data kelas ke dalam database
        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat!'); // Redirect ke route 'kelas.index'
    }

    // Menampilkan form untuk mengedit kelas
    public function edit(Kelas $kelas)
    {
        // Mengambil semua dosen untuk dipilih sebagai dosen wali
        $dosen = Dosen::all();
        return view('admin.kelas.edit', compact('kelas', 'dosen'));
    }

    // Memperbarui data kelas
    public function update(Request $request, Kelas $kelas)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen', // Dosen Wali boleh kosong jika status inactive
        ]);

        // Memastikan dosen wali hanya diisi jika status kelas active
        if ($validated['status'] == 'active' && !$validated['id_dosen_wali']) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.']);
        }

        // Memperbarui data kelas
        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!'); // Redirect ke route 'kelas.index'
    }

    // Mengaktifkan kelas
    public function activate(Kelas $kelas)
    {
        // Mengubah status kelas menjadi 'active'
        $kelas->status = 'active';
        $kelas->save();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diaktifkan!'); // Redirect ke route 'kelas.index'
    }

    // Menghapus kelas
    public function destroy(Kelas $kelas)
    {
        // Menghapus data kelas
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!'); // Redirect ke route 'kelas.index'
    }
}
