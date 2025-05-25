<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use App\Models\Matakuliah; // Untuk mengecek keterkaitan saat menghapus
use App\Models\TahunAjaran; // Untuk contoh filter di show/edit (opsional)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   // Untuk transaksi
use Illuminate\Support\Facades\Log;  // Atau gunakan logger() helper
use Illuminate\Validation\Rule;    // Untuk validasi unik
use Illuminate\View\View;          // Return type hinting
use Illuminate\Http\RedirectResponse; // Return type hinting

class RuangController extends Controller
{
    /**
     * Menampilkan daftar semua ruang.
     */
    public function index(Request $request): View
    {
        $query = Ruang::query();

        // Contoh: Jika ada fungsionalitas pencarian
        if ($request->filled('search')) {
            $query->where('nama_ruang', 'like', '%' . $request->search . '%');
        }

        $ruang = $query->latest()->paginate(10)->withQueryString();
        return view('admin.ruang.index', compact('ruang'));
    }

    /**
     * Menampilkan form untuk membuat ruang baru.
     */
    public function create(): View
    {
        return view('admin.ruang.create');
    }

    /**
     * Menyimpan ruang baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_ruang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ruang', 'nama_ruang') // Nama ruang sebaiknya unik
            ],
            'kapasitas' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            Ruang::create($validatedData);
            DB::commit();
            return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
            // Ganti 'admin.ruang.index' dengan nama route Anda jika berbeda
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating ruang: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan ruang. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail spesifik ruang.
     * Bisa ditambahkan informasi jadwal yang menggunakan ruang ini pada tahun ajaran aktif.
     */
    public function show($id_ruang): View // Menggunakan $id_ruang jika PK Anda adalah id_ruang
    {
        // Asumsikan primary key Ruang adalah 'id'. Jika berbeda, sesuaikan findOrFail.
        $ruang = Ruang::findOrFail($id_ruang);

        // Opsional: Tampilkan jadwal kuliah yang menggunakan ruang ini
        // $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
        // $jadwalDiRuangIni = [];
        // if ($tahunAjaranAktif) {
        //     $jadwalDiRuangIni = $ruang->matakuliah() // Asumsi ada relasi matakuliah() di model Ruang
        //                              ->where('id_tahun_ajaran', $tahunAjaranAktif->id)
        //                              ->with(['masterMatakuliah', 'dosen.user', 'kelas'])
        //                              ->get();
        // }
        // return view('admin.ruang.show', compact('ruang', 'jadwalDiRuangIni'));

        return view('admin.ruang.show', compact('ruang'));
    }

    /**
     * Menampilkan form untuk mengedit data ruang.
     */
    public function edit($id_ruang): View
    {
        $ruang = Ruang::findOrFail($id_ruang);
        return view('admin.ruang.edit', compact('ruang'));
    }

    /**
     * Memperbarui data ruang di database.
     */
    public function update(Request $request, $id_ruang): RedirectResponse
    {
        $ruang = Ruang::findOrFail($id_ruang);
        $validatedData = $request->validate([
            'nama_ruang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ruang', 'nama_ruang')->ignore($ruang->getKey(), $ruang->getKeyName())
                // Menggunakan getKey() dan getKeyName() untuk generalisasi primary key
            ],
            'kapasitas' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $ruang->update($validatedData);
            DB::commit();
            return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error updating ruang: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui ruang. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus data ruang dari database.
     */
    public function destroy($id_ruang): RedirectResponse
    {
        $ruang = Ruang::findOrFail($id_ruang);

        // PENTING: Cek apakah ruang masih digunakan dalam jadwal kuliah (Matakuliah)
        // Asumsi ada relasi `matakuliah()` di model `Ruang` yang merujuk ke `hasMany(Matakuliah::class, 'ruang_id')`
        if ($ruang->matakuliah()->exists()) {
            return redirect()->route('admin.ruang.index')->with('error', 'Ruang tidak dapat dihapus karena masih digunakan dalam Jadwal Kuliah.');
        }

        DB::beginTransaction();
        try {
            $ruang->delete();
            DB::commit();
            return redirect()->route('admin.ruang.index')->with('success', 'Ruang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting ruang: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus ruang.';
             // Pesan error spesifik jika karena foreign key constraint (meskipun pengecekan di atas seharusnya mencegah ini)
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus ruang. Masih ada data lain yang terkait dengan ruang ini.';
            }
            return redirect()->route('admin.ruang.index')->with('error', $errorMessage);
        }
    }
}