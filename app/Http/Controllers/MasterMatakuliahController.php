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
    /**
     * Menampilkan daftar semua master mata kuliah.
     */
    public function index(Request $request): View
    {
        $query = MasterMatakuliah::with('prodi'); // Eager load relasi prodi

        // Filter berdasarkan Program Studi
        if ($request->filled('id_prodi')) {
            $query->where('id_prodi', $request->id_prodi);
        }

        // Pencarian berdasarkan kode MK atau nama MK
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_mk', 'like', $searchTerm)
                  ->orWhere('nama_mk', 'like', $searchTerm);
            });
        }

        $masterMatakuliah = $query->orderBy('nama_mk', 'asc')->paginate(10)->withQueryString();
        $prodiList = Prodi::orderBy('nama_prodi')->get(); // Untuk dropdown filter

        return view('admin.mastermatakuliah.index', compact('masterMatakuliah', 'prodiList'));
    }

    /**
     * Menampilkan form untuk membuat master mata kuliah baru.
     */
    public function create(): View
    {
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        return view('admin.mastermatakuliah.create', compact('prodiList'));
    }

    /**
     * Menyimpan master mata kuliah baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'kode_mk' => [
                'required', 'string', 'max:20',
                Rule::unique('master_matakuliah', 'kode_mk')
            ],
            'nama_mk' => 'required|string|max:150',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktek' => 'required|integer|min:0',
            'sks_lapangan' => 'required|integer|min:0',
            'semester_default' => 'nullable|integer|min:1|max:14', // Semester penawaran default
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'deskripsi' => 'nullable|string',
        ]);

        // Pastikan minimal ada satu jenis SKS yang lebih dari 0 jika SKS total adalah 0
        if (($validatedData['sks_teori'] + $validatedData['sks_praktek'] + $validatedData['sks_lapangan']) == 0) {
            // Anda bisa mengembalikan error atau membiarkannya jika SKS 0 diperbolehkan
            // return back()->withErrors(['sks_total' => 'Minimal total SKS harus lebih dari 0.'])->withInput();
        }

        DB::beginTransaction();
        try {
            MasterMatakuliah::create($validatedData);
            DB::commit();
            return redirect()->route('admin.mastermatakuliah.index') // Sesuaikan nama route jika perlu
                         ->with('success', 'Master Mata Kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating Master Mata Kuliah: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Master Mata Kuliah. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail spesifik master mata kuliah (opsional).
     */
    public function show($id_master_mk): View
    {
        $masterMatakuliah = MasterMatakuliah::with(['prodi', 'jadwalKuliah.tahunAjaran']) // jadwalKuliah adalah relasi hasMany ke Matakuliah (Jadwal)
                                          ->findOrFail($id_master_mk);
        // Anda bisa menghitung berapa kali MK ini dijadwalkan, dll.
        return view('admin.mastermatakuliah.show', compact('masterMatakuliah'));
    }

    /**
     * Menampilkan form untuk mengedit data master mata kuliah.
     */
    public function edit($id_master_mk): View
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        return view('admin.mastermatakuliah.edit', compact('masterMatakuliah', 'prodiList'));
    }

    /**
     * Memperbarui data master mata kuliah di database.
     */
    public function update(Request $request, $id_master_mk): RedirectResponse
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);

        $validatedData = $request->validate([
            'kode_mk' => [
                'required', 'string', 'max:20',
                Rule::unique('master_matakuliah', 'kode_mk')->ignore($masterMatakuliah->id_master_mk, 'id_master_mk')
            ],
            'nama_mk' => 'required|string|max:150',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktek' => 'required|integer|min:0',
            'sks_lapangan' => 'required|integer|min:0',
            'semester_default' => 'nullable|integer|min:1|max:14',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'deskripsi' => 'nullable|string',
        ]);

        if (($validatedData['sks_teori'] + $validatedData['sks_praktek'] + $validatedData['sks_lapangan']) == 0) {
            // Logika serupa dengan store jika SKS total 0 tidak diperbolehkan
        }

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

    /**
     * Menghapus data master mata kuliah dari database.
     */
    public function destroy($id_master_mk): RedirectResponse
    {
        $masterMatakuliah = MasterMatakuliah::findOrFail($id_master_mk);

        // PENTING: Cek apakah master mata kuliah masih digunakan dalam Jadwal Kuliah (tabel matakuliah)
        // Asumsi ada relasi `jadwalKuliah()` di model `MasterMatakuliah`
        if ($masterMatakuliah->jadwalKuliah()->exists()) {
             return redirect()->route('admin.mastermatakuliah.index')->with('error', 'Master Mata Kuliah tidak dapat dihapus karena sudah pernah atau sedang dijadwalkan.');
        }
        // Pertimbangkan status "tidak aktif" daripada menghapus fisik jika sudah ada history
        // penggunaannya, meskipun belum dijadwalkan (misal, jika ada rencana kurikulum).

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