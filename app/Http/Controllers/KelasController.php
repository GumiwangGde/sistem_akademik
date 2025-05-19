<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data kelas beserta dosen wali
        $kelas = Kelas::with('dosenWali.user')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengambil hanya dosen yang memiliki is_dosen_wali = 1
        $dosen = Dosen::where('is_dosen_wali', 1)->with('user')->get();
        return view('admin.kelas.create', compact('dosen'));
    }

    /**
     * Menyimpan kelas baru ke dalam database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen', // Dosen Wali boleh kosong jika status inactive
        ]);

        // Memastikan dosen wali hanya diisi jika status kelas active
        if ($validated['status'] == 'active' && !$validated['id_dosen_wali']) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.']);
        }

        try {
            // Menyimpan data kelas ke dalam database
            Kelas::create($validated);
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Error creating class: ' . $e->getMessage());
            return redirect()->route('kelas.index')->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail kelas
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        // Ambil kelas beserta dosen wali dan mahasiswa
        $kelas = Kelas::with(['dosenWali.user', 'mahasiswa.user'])->findOrFail($id);
        
        // Ambil mahasiswa dalam kelas ini
        $mahasiswa = $kelas->mahasiswa;
        
        // Hitung jumlah mahasiswa
        $mahasiswaCount = $mahasiswa->count();
        
        return view('admin.kelas.detail', compact('kelas', 'mahasiswa', 'mahasiswaCount'));
    }

    /**
     * Menampilkan form untuk mengedit kelas
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        // Mengambil hanya dosen yang memiliki is_dosen_wali = 1
        $dosen = Dosen::where('is_dosen_wali', 1)->with('user')->get();
        return view('admin.kelas.edit', compact('kelas', 'dosen'));
    }

    /**
     * Memperbarui data kelas
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'id_dosen_wali' => 'nullable|exists:dosen,id_dosen', // Dosen Wali boleh kosong jika status inactive
        ]);
         $kelas = Kelas::findOrFail($id);

        // Memastikan dosen wali hanya diisi jika status kelas active
        if ($validated['status'] == 'active' && !$validated['id_dosen_wali']) {
            return back()->withErrors(['id_dosen_wali' => 'Dosen wali harus diisi jika status kelas aktif.']);
        }

        try {
            // Memperbarui data kelas
            $kelas->update($validated);
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating class: ' . $e->getMessage());
            return redirect()->route('kelas.index')->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    /**
     * Mengaktifkan kelas
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Kelas $kelas)
    {
        try {
            // Periksa apakah kelas memiliki dosen wali
            if (!$kelas->id_dosen_wali) {
                return redirect()->route('kelas.index')->with('error', 'Kelas tidak dapat diaktifkan karena tidak memiliki dosen wali.');
            }

            // Mengubah status kelas menjadi 'active'
            $kelas->status = 'active';
            $kelas->save();

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diaktifkan!');
        } catch (\Exception $e) {
            Log::error('Error activating class: ' . $e->getMessage());
            return redirect()->route('kelas.index')->with('error', 'Gagal mengaktifkan kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kelas
     *
     * @param  int  $id ID kelas yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Cari kelas berdasarkan ID
            $kelas = Kelas::findOrFail($id);
            
            // Debug info
            Log::info('Attempting to delete class with ID: ' . $id);
            
            // Hapus kelas
            $result = $kelas->delete();
            
            // Cek apakah penghapusan berhasil
            if ($result) {
                Log::info('Class deleted successfully: ' . $id);
                return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
            } else {
                Log::warning('Failed to delete class: ' . $id);
                return redirect()->route('kelas.index')->with('error', 'Gagal menghapus kelas!');
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error deleting class: ' . $e->getMessage());
            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}