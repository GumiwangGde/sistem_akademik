<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    
    public function index()
    {
        $dosen = Dosen::with('user')->get();
        return view('admin.dosen.index', compact('dosen'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'nidn' => 'required|unique:dosen',
        ]);
    
        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Beri role dosen
        $user->assignRole('dosen');
    
        // Buat dosen
        $dosen = Dosen::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
            'is_dosen_wali' => $request->has('is_dosen_wali'),
        ]);

        // Redirect ke halaman edit dosen yang baru saja dibuat
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->user()->delete();  // Hapus user juga
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
    public function edit($id)
{
    // Ambil data dosen berdasarkan id dan hubungan dengan user
    $dosen = Dosen::with('user')->findOrFail($id);

    // Kirim data dosen ke halaman edit
    return view('admin.dosen.edit', compact('dosen'));
}

public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'name' => 'required',
        'email' => 'required|email', // Validasi dasar email
        'nidn' => 'required|unique:dosen,nidn,' . $id . ',id_dosen',
    ]);

    // Temukan dosen berdasarkan id
    $dosen = Dosen::findOrFail($id);
    
    // Validasi email unik kecuali untuk user ini sendiri
    $request->validate([
        'email' => 'unique:users,email,' . $dosen->user_id
    ]);

    // Update data user yang terkait dengan dosen
    $user = User::findOrFail($dosen->user_id);
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

    // Update data dosen
    $dosen->update([
        'nidn' => $request->nidn,
        'is_dosen_wali' => $request->has('is_dosen_wali') ? 1 : 0,
    ]);

    // Redirect ke halaman daftar dosen dengan pesan sukses
    return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
}

}
