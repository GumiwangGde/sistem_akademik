<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::with(['user', 'kelas'])->get();
        return view('admin.mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil daftar kelas untuk dropdown
        $kelas = Kelas::all();
        return view('admin.mahasiswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'nrp' => 'required|unique:mahasiswa',
            'prodi' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas'
        ]);
    
        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Beri role mahasiswa
        $user->assignRole('mahasiswa');
    
        // Buat mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'id_kelas' => $request->id_kelas,
            'nrp' => $request->nrp,
            'nama' => $request->name,
            'prodi' => $request->prodi
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::with(['user', 'kelas'])->findOrFail($id);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $kelas = Kelas::all();
        
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'nrp' => 'required|unique:mahasiswa,nrp,' . $id . ',id_mahasiswa',
            'prodi' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas'
        ]);

        // Temukan mahasiswa berdasarkan id
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Validasi email unik kecuali untuk user ini sendiri
        $request->validate([
            'email' => 'unique:users,email,' . $mahasiswa->user_id
        ]);

        // Update data user yang terkait dengan mahasiswa
        $user = User::findOrFail($mahasiswa->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update data mahasiswa
        $mahasiswa->update([
            'id_kelas' => $request->id_kelas,
            'nrp' => $request->nrp,
            'nama' => $request->name,
            'prodi' => $request->prodi
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->user()->delete();  // Hapus user juga
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}