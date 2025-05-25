<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Atau gunakan logger() helper
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProdiController extends Controller
{
    /**
     * Menampilkan daftar semua program studi.
     */
    public function index(Request $request): View
    {
        $query = Prodi::query();

        if ($request->filled('search_nama')) {
            $query->where('nama_prodi', 'like', '%' . $request->search_nama . '%');
        }
        if ($request->filled('search_kode')) {
            $query->where('kode_prodi', 'like', '%' . $request->search_kode . '%');
        }
        if ($request->filled('search_jenjang')) {
            $query->where('jenjang', 'like', '%' . $request->search_jenjang . '%');
        }

        $prodi = $query->orderBy('nama_prodi', 'asc')->paginate(10)->withQueryString();

        return view('admin.prodi.index', compact('prodi'));
    }

    /**
     * Menampilkan form untuk membuat program studi baru.
     */
    public function create(): View
    {
        // Jenjang bisa berupa array tetap atau diambil dari tabel lain jika lebih dinamis
        $jenjangList = ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Spesialis'];
        return view('admin.prodi.create', compact('jenjangList'));
    }

    /**
     * Menyimpan program studi baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'kode_prodi' => [
                'required', 'string', 'max:20',
                Rule::unique('prodi', 'kode_prodi')
            ],
            'nama_prodi' => 'required|string|max:100',
            'jenjang' => 'nullable|string|max:10', // Sesuaikan jika jenjang wajib diisi
        ]);

        DB::beginTransaction();
        try {
            Prodi::create($validatedData);
            DB::commit();
            return redirect()->route('admin.prodi.index') // Sesuaikan nama route jika perlu
                         ->with('success', 'Program Studi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating Prodi: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Program Studi. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail spesifik program studi (opsional).
     */
    public function show($id_prodi): View
    {
        $prodi = Prodi::withCount(['mahasiswa', 'kelas', 'masterMatakuliah'])->findOrFail($id_prodi);
        // withCount akan menambahkan properti seperti mahasiswa_count, kelas_count, dst.
        return view('admin.prodi.show', compact('prodi'));
    }

    /**
     * Menampilkan form untuk mengedit data program studi.
     */
    public function edit($id_prodi): View
    {
        $prodi = Prodi::findOrFail($id_prodi);
        $jenjangList = ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Spesialis'];
        return view('admin.prodi.edit', compact('prodi', 'jenjangList'));
    }

    /**
     * Memperbarui data program studi di database.
     */
    public function update(Request $request, $id_prodi): RedirectResponse
    {
        $prodi = Prodi::findOrFail($id_prodi);

        $validatedData = $request->validate([
            'kode_prodi' => [
                'required', 'string', 'max:20',
                Rule::unique('prodi', 'kode_prodi')->ignore($prodi->id_prodi, 'id_prodi')
            ],
            'nama_prodi' => 'required|string|max:100',
            'jenjang' => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $prodi->update($validatedData);
            DB::commit();
            return redirect()->route('admin.prodi.index')
                         ->with('success', 'Program Studi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error updating Prodi: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Program Studi. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus data program studi dari database.
     */
    public function destroy($id_prodi): RedirectResponse
    {
        $prodi = Prodi::findOrFail($id_prodi);

        // PENTING: Cek apakah prodi masih digunakan oleh data lain
        // (Mahasiswa, Kelas, MasterMatakuliah)
        if ($prodi->mahasiswa()->exists() || $prodi->kelas()->exists() || $prodi->masterMatakuliah()->exists()) {
             return redirect()->route('admin.prodi.index')->with('error', 'Program Studi tidak dapat dihapus karena masih digunakan oleh data Mahasiswa, Kelas, atau Mata Kuliah.');
        }

        DB::beginTransaction();
        try {
            $prodi->delete();
            DB::commit();
            return redirect()->route('admin.prodi.index')
                         ->with('success', 'Program Studi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting Prodi: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus Program Studi.';
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus Program Studi. Masih ada data lain yang terkait erat dengan entitas ini.';
            }
            return redirect()->route('admin.prodi.index')->with('error', $errorMessage);
        }
    }
}