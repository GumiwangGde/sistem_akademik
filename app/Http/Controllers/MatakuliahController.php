<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatakuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matakuliah = Matakuliah::with(['dosen.user', 'kelas'])->get();
        return view('admin.matakuliah.index', compact('matakuliah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $dosen = Dosen::with('user')->get();
        $ruang = Ruang::all();
        return view('admin.matakuliah.create', compact('kelas', 'dosen', 'ruang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'kode_mk' => 'required|string|max:20|unique:matakuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => 'required|string|max:20',
            'ruang_id' => 'required|exists:ruang,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('matakuliah.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Buat matakuliah baru
        Matakuliah::create($request->all());

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $dosen = Dosen::with('user')->get();
        $kelas = Kelas::all();
        $ruang = Ruang::all();
        
        return view('admin.matakuliah.edit', compact('matakuliah', 'dosen', 'kelas', 'ruang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'kode_mk' => 'required|string|max:20|unique:matakuliah,kode_mk,' . $id . ',id_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => 'required|string|max:20',
            'ruang_id' => 'required|exists:ruang,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('matakuliah.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update matakuliah
        $matakuliah->update($request->all());

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus!');
    }
}