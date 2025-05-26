<?php

namespace App\Http\Controllers;

use App\Models\MasterMatakuliah;
use App\Models\Prodi; // Untuk dropdown prodi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Atau gunakan logger() helper
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MasterMatakuliahController extends Controller
{
    public function index(Request $request): View
    {
        $query = MasterMatakuliah::with('prodi'); 

        if ($request->filled('id_prodi')) {
            $query->where('id_prodi', $request->id_prodi);
        }
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_mk', 'like', $searchTerm)
                  ->orWhere('nama_mk', 'like', $searchTerm);
            });
        }

        $masterMatakuliah = $query->orderBy('nama_mk', 'asc')->paginate(10)->withQueryString();
        $prodiList = Prodi::orderBy('nama_prodi')->get(); 

        return view('admin.mastermatakuliah.index', compact('masterMatakuliah', 'prodiList'));
    }

    public function create(): View
    {
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        return view('admin.mastermatakuliah.create', compact('prodiList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'kode_mk' => [
                'required', 'string', 'max:20',
                Rule::unique('master_matakuliah', 'kode_mk')
            ],
            'nama_mk' => 'required|string|max:150',
            'sks' => 'required|integer|min:0',
            'semester_default' => 'nullable|integer|min:1|max:14', // Semester penawaran default
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'deskripsi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            MasterMatakuliah::create($validatedData);
            DB::commit();
            return redirect()->route('admin.mastermatakuliah.index')
                         ->with('success', 'Master Mata Kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating Master Mata Kuliah: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Master Mata Kuliah. Silakan coba lagi.');
        }
    }

    public function show($id_master_mk): View
    {
        $masterMatakuliah = MasterMatakuliah::with(['prodi', 'jadwalKuliah.tahunAjaran']) 
                                          ->findOrFail($id_master_mk);
        return view('admin.mastermatakuliah.show', compact('masterMatakuliah'));
    }

    public function edit($id_master_mk): View
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        return view('admin.mastermatakuliah.edit', compact('masterMatakuliah', 'prodiList'));
    }

    public function update(Request $request, $id_master_mk): RedirectResponse
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);

        $validatedData = $request->validate([
            'kode_mk' => [
                'required', 'string', 'max:20',
                Rule::unique('master_matakuliah', 'kode_mk')->ignore($masterMatakuliah->id_master_mk, 'id_master_mk')
            ],
            'nama_mk' => 'required|string|max:150',
            'sks' => 'required|integer|min:0',
            'semester_default' => 'nullable|integer|min:1|max:14',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'deskripsi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $masterMatakuliah->update($validatedData);
            DB::commit();
            return redirect()->route('admin.mastermatakuliah.index')
                         ->with('success', 'Master Mata Kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error updating Master Mata Kuliah: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Master Mata Kuliah. Silakan coba lagi.');
        }
    }

    public function destroy($id_master_mk): RedirectResponse
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);

        if ($masterMatakuliah->jadwalKuliah()->exists()) {
             return redirect()->route('admin.mastermatakuliah.index')->with('error', 'Master Mata Kuliah tidak dapat dihapus karena sudah pernah atau sedang dijadwalkan.');
        }

        DB::beginTransaction();
        try {
            $masterMatakuliah->delete();
            DB::commit();
            return redirect()->route('admin.mastermatakuliah.index')
                         ->with('success', 'Master Mata Kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting Master Mata Kuliah: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus Master Mata Kuliah.';
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus Master Mata Kuliah. Masih ada data penjadwalan yang terkait erat.';
            }
            return redirect()->route('admin.mastermatakuliah.index')->with('error', $errorMessage);
        }
    }
}