<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\TahunAjaran; // Model baru
use App\Models\Prodi;       // Model baru
use App\Models\Mahasiswa;   // Untuk detail kelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Untuk transaksi
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['dosenWali.user', 'tahunAjaran', 'prodi']);

        // Filter berdasarkan Tahun Ajaran
        if ($request->filled('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }

        // Filter berdasarkan Prodi
        if ($request->filled('id_prodi')) {
            $query->where('id_prodi', $request->id_prodi);
        }
        
        // Filter berdasarkan Status
        if ($request->filled('status_kelas')) {
            $query->where('status', $request->status_kelas);
        }

        // Pencarian berdasarkan nama kelas atau nama dosen wali
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_kelas', 'like', $searchTerm)
                  ->orWhereHas('dosenWali.user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                  });
            });
        }

        $kelas = $query->latest('created_at')->paginate(10)->withQueryString(); // withQueryString untuk menjaga filter saat paginasi

        // Data untuk dropdown filter
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $prodiList = Prodi::orderBy('nama_prodi')->get();

        return view('admin.kelas.index', compact('kelas', 'tahunAjaranList', 'prodiList'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        $dosenWaliList = Dosen::where('is_dosen_wali', true)->with('user')->get();
        $tahunAjaranList = TahunAjaran::whereIn('status', ['aktif', 'direncanakan'])
                                      ->orderBy('tahun_mulai', 'desc')
                                      ->orderBy('semester', 'desc')
                                      ->get();
        $prodiList = Prodi::orderBy('nama_prodi')->get();

        return view('admin.kelas.create', compact('dosenWaliList', 'tahunAjaranList', 'prodiList'));
    }

    /**
     * Menyimpan kelas baru ke dalam database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
            'id_prodi' => 'required|exists:prodi,id_prodi',
        ]);

        if ($validated['status'] === 'active' && empty($validated['id_dosen_wali'])) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.'])->withInput();
        }
        // Jika status inactive, dosen wali bisa null
        if ($validated['status'] === 'inactive') {
            $validated['id_dosen_wali'] = null;
        }


        DB::beginTransaction();
        try {
            Kelas::create($validated);
            DB::commit();
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
            // Sesuaikan nama route jika berbeda, misal: redirect()->route('kelas.index')
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating class: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kelas. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail kelas.
     * (Mengganti nama method 'detail' menjadi 'show' agar konsisten dengan resource controller)
     */
    public function show($id_kelas)
    {
        $kelas = Kelas::with([
            'dosenWali.user',
            'tahunAjaran',
            'prodi',
            'mahasiswa.user' // Memuat mahasiswa beserta data user mereka
        ])->findOrFail($id_kelas);
        
        $mahasiswaCount = $kelas->mahasiswa->count();
        
        return view('admin.kelas.detail', compact('kelas', 'mahasiswaCount'));
        // Pastikan view 'admin.kelas.show' ada atau sesuaikan dengan nama view Anda.
    }

    /**
     * Menampilkan form untuk mengedit kelas.
     */
    public function edit($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $dosenWaliList = Dosen::where('is_dosen_wali', true)->with('user')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $prodiList = Prodi::orderBy('nama_prodi')->get();

        return view('admin.kelas.edit', compact('kelas', 'dosenWaliList', 'tahunAjaranList', 'prodiList'));
    }

    /**
     * Memperbarui data kelas di database.
     */
    public function update(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
            'id_prodi' => 'required|exists:prodi,id_prodi',
        ]);

        if ($validated['status'] === 'active' && empty($validated['id_dosen_wali'])) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.'])->withInput();
        }
        // Jika status inactive, dosen wali di-set null
        if ($validated['status'] === 'inactive') {
            $validated['id_dosen_wali'] = null;
        }


        DB::beginTransaction();
        try {
            $kelas->update($validated);
            DB::commit();
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating class: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kelas. Silakan coba lagi.');
        }
    }

    /**
     * Mengaktifkan atau menonaktifkan status kelas.
     * (Menggabungkan fungsi activate dan bisa juga untuk deactivate)
     */
    public function toggleStatus(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $newStatus = $kelas->status === 'active' ? 'inactive' : 'active';

        DB::beginTransaction();
        try {
            if ($newStatus === 'active') {
                if (!$kelas->id_dosen_wali) {
                    DB::rollBack(); // Tidak perlu commit jika gagal
                    return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat diaktifkan karena tidak memiliki Dosen Wali.');
                }
                if (!$kelas->id_tahun_ajaran) {
                    DB::rollBack();
                    return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat diaktifkan karena tidak memiliki Tahun Ajaran.');
                }
                if (!$kelas->id_prodi) {
                    DB::rollBack();
                    return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat diaktifkan karena tidak memiliki Program Studi.');
                }
            }

            $kelas->status = $newStatus;
            if ($newStatus === 'inactive') {
                // Opsional: jika kelas dinonaktifkan, apakah dosen walinya juga di-set null?
                // $kelas->id_dosen_wali = null; // Tergantung aturan bisnis
            }
            $kelas->save();

            DB::commit();
            $message = $newStatus === 'active' ? 'Kelas berhasil diaktifkan!' : 'Kelas berhasil dinonaktifkan!';
            return redirect()->route('admin.kelas.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling class status: ' . $e->getMessage());
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal mengubah status kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kelas dari database.
     */
    public function destroy($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);

        // Tambahan: Periksa apakah kelas masih memiliki mahasiswa atau data terkait lainnya
        if ($kelas->mahasiswa()->exists()) {
             return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus kelas. Masih ada mahasiswa terdaftar di kelas ini.');
        }
        // Anda bisa menambahkan pengecekan lain di sini (misal jadwal kuliah, dll)


        DB::beginTransaction();
        try {
            $kelas->delete();
            DB::commit();
            Log::info('Class deleted successfully: ' . $id_kelas);
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting class: ' . $e->getMessage());
            // Cek apakah error disebabkan oleh foreign key constraint
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus kelas. Masih ada data lain yang terkait dengan kelas ini.');
            }
            return redirect()->route('admin.kelas.index')->with('error', 'Terjadi kesalahan saat menghapus kelas.');
        }
    }
}
