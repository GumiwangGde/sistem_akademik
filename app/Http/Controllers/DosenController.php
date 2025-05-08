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
        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->user()->delete();  // Hapus user juga
        $dosen->delete();

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $dosen->user_id,
            'nidn' => 'required|unique:dosen,nidn,' . $id . ',id_dosen',
        ]);
        
        // Update user data
        $dosen->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8',
            ]);
            
            $dosen->user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        // Update dosen data
        $dosen->update([
            'nidn' => $request->nidn,
            'is_dosen_wali' => $request->has('is_dosen_wali'),
        ]);
        
        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }
}
