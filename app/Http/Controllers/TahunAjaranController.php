<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan daftar semua tahun ajaran.
     */
    public function index(Request $request): View
    {
        $query = TahunAjaran::query();

        if ($request->filled('search_tahun')) {
            $query->where('tahun_mulai', 'like', '%' . $request->search_tahun . '%')
                  ->orWhere('nama_tahun_ajaran', 'like', '%' . $request->search_tahun . '%');
        }
        if ($request->filled('search_semester')) {
            $query->where('semester', $request->search_semester);
        }
        if ($request->filled('search_status')) {
            $query->where('status', $request->search_status);
        }

        $tahunAjaran = $query->orderBy('tahun_mulai', 'desc')
                              ->orderBy('semester', 'desc')
                              ->paginate(10)
                              ->withQueryString();

        return view('admin.tahunajaran.index', compact('tahunAjaran'));
    }

    /**
     * Menampilkan form untuk membuat tahun ajaran baru.
     */
    public function create(): View
    {
        return view('admin.tahunajaran.create');
    }

    /**
     * Menyimpan tahun ajaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:100',
            'kode_tahun_ajaran' => [
                'required', 'string', 'max:20',
                Rule::unique('tahun_ajaran', 'kode_tahun_ajaran')
            ],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 5),
            'tahun_selesai' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 6) . '|gte:tahun_mulai',
            'status' => ['required', Rule::in(['aktif', 'tidak aktif', 'direncanakan'])],
            'tanggal_mulai_perkuliahan' => 'nullable|date',
            'tanggal_selesai_perkuliahan' => 'nullable|date|after_or_equal:tanggal_mulai_perkuliahan',
            'tanggal_mulai_frs' => 'nullable|date',
            'tanggal_selesai_frs' => 'nullable|date|after_or_equal:tanggal_mulai_frs',
        ]);

        DB::beginTransaction();
        try {
            // Jika status baru adalah 'aktif', pastikan tidak ada tahun ajaran lain yang aktif
            if ($validatedData['status'] === 'aktif') {
                TahunAjaran::where('status', 'aktif')->update(['status' => 'tidak aktif']);
            }

            TahunAjaran::create($validatedData);
            DB::commit();
            return redirect()->route('admin.tahunajaran.index') // Sesuaikan nama route
                         ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating Tahun Ajaran: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Tahun Ajaran. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail spesifik tahun ajaran (opsional).
     */
    public function show($id_tahun_ajaran): View
    {
        $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
        // Anda bisa memuat data terkait jika perlu, misal jumlah kelas atau jadwal di tahun ajaran ini
        // $jumlahKelas = $tahunAjaran->kelas()->count();
        return view('admin.tahunajaran.show', compact('tahunAjaran'));
    }

    /**
     * Menampilkan form untuk mengedit data tahun ajaran.
     */
    public function edit($id_tahun_ajaran): View
    {
        $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
        return view('admin.tahunajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Memperbarui data tahun ajaran di database.
     */
    public function update(Request $request, $id_tahun_ajaran): RedirectResponse
    {
        $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);

        $validatedData = $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:100',
            'kode_tahun_ajaran' => [
                'required', 'string', 'max:20',
                Rule::unique('tahun_ajaran', 'kode_tahun_ajaran')->ignore($tahunAjaran->id)
            ],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 5),
            'tahun_selesai' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 6) . '|gte:tahun_mulai',
            'status' => ['required', Rule::in(['aktif', 'tidak aktif', 'direncanakan'])],
            'tanggal_mulai_perkuliahan' => 'nullable|date',
            'tanggal_selesai_perkuliahan' => 'nullable|date|after_or_equal:tanggal_mulai_perkuliahan',
            'tanggal_mulai_frs' => 'nullable|date',
            'tanggal_selesai_frs' => 'nullable|date|after_or_equal:tanggal_mulai_frs',
        ]);

        DB::beginTransaction();
        try {
            // Jika status baru adalah 'aktif', pastikan tidak ada tahun ajaran lain yang aktif
            // kecuali tahun ajaran yang sedang diedit ini sendiri.
            if ($validatedData['status'] === 'aktif') {
                TahunAjaran::where('status', 'aktif')->where('id', '!=', $tahunAjaran->id)->update(['status' => 'tidak aktif']);
            }

            $tahunAjaran->update($validatedData);
            DB::commit();
            return redirect()->route('admin.tahunajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error updating Tahun Ajaran: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Tahun Ajaran. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus data tahun ajaran dari database.
     */
    public function destroy($id_tahun_ajaran): RedirectResponse
    {
        $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);

        // PENTING: Cek apakah tahun ajaran masih digunakan oleh data lain (Kelas, Jadwal Kuliah, FRS)
        // Ini adalah contoh sederhana, Anda mungkin perlu pengecekan yang lebih komprehensif.
        if ($tahunAjaran->kelas()->exists() || $tahunAjaran->jadwalKuliah()->exists() || $tahunAjaran->frs()->exists()) {
             return redirect()->route('admin.tahunajaran.index')->with('error', 'Tahun Ajaran tidak dapat dihapus karena masih digunakan oleh data Kelas, Jadwal Kuliah, atau FRS.');
        }
        // Tidak boleh menghapus tahun ajaran yang statusnya 'aktif'
        if ($tahunAjaran->status === 'aktif') {
            return redirect()->route('admin.tahunajaran.index')->with('error', 'Tahun Ajaran yang aktif tidak dapat dihapus.');
        }


        DB::beginTransaction();
        try {
            $tahunAjaran->delete();
            DB::commit();
            return redirect()->route('admin.tahunajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting Tahun Ajaran: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus Tahun Ajaran.';
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus Tahun Ajaran. Masih ada data lain yang terkait erat dengan entitas ini.';
            }
            return redirect()->route('admin.tahunajaran.index')->with('error', $errorMessage);
        }
    }

    /**
     * Method untuk mengaktifkan tahun ajaran tertentu.
     */
    public function setActive(Request $request, $id_tahun_ajaran): RedirectResponse
    {
        $tahunAjaranToActivate = TahunAjaran::findOrFail($id_tahun_ajaran);

        DB::beginTransaction();
        try {
            // Nonaktifkan semua tahun ajaran lain yang mungkin aktif
            TahunAjaran::where('status', 'aktif')->where('id', '!=', $tahunAjaranToActivate->id)->update(['status' => 'tidak aktif']);

            // Aktifkan tahun ajaran yang dipilih
            $tahunAjaranToActivate->status = 'aktif';
            $tahunAjaranToActivate->save();

            DB::commit();
            return redirect()->route('admin.tahunajaran.index')->with('success', 'Tahun Ajaran ' . $tahunAjaranToActivate->nama_tahun_ajaran . ' berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error activating Tahun Ajaran: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.tahunajaran.index')->with('error', 'Gagal mengaktifkan Tahun Ajaran.');
        }
    }
}