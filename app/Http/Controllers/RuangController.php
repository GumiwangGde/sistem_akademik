<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index()
    {
        $ruang = Ruang::all();
        return view('admin.ruang.index', compact('ruang'));
    }

    public function create()
    {
        return view('admin.ruang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        Ruang::create($validated);
        return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil ditambahkan');
    }

    public function show($id)
    {
        $ruang = Ruang::findOrFail($id);
        return view('admin.ruang.show', compact('ruang'));
    }

    public function edit($id)
    {
        $ruang = Ruang::findOrFail($id);
        return view('admin.ruang.edit', compact('ruang'));
    }

    public function update(Request $request, $id)
    {
        $ruang = Ruang::findOrFail($id);
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $ruang->update($validated);
        return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ruang = Ruang::findOrFail($id);
        $ruang->delete();
        return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil dihapus');
    }
}
