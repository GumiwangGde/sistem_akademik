<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log; // Opsional, untuk debugging

class FrsController extends Controller
{
    /**
     * Get pending FRS that need approval
     * For dosen wali
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingFRS(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        if (!$dosen->is_dosen_wali) {
            return response()->json([
                'message' => 'Hanya dosen wali yang dapat melihat FRS pending'
            ], 403);
        }
        
        $kelasIds = Kelas::where('id_dosen_wali', $dosen->id_dosen)
            ->pluck('id_kelas')
            ->toArray();
            
        if (empty($kelasIds)) {
            return response()->json([
                'pending_frs' => [], // Kembalikan list kosong jika tidak ada kelas wali
                'message' => 'Anda tidak menjadi wali untuk kelas manapun saat ini, tidak ada FRS pending.'
            ], 200); // Bisa juga 404, tapi 200 dengan list kosong lebih umum untuk "tidak ada data"
        }
        
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasIds)
            ->pluck('id_mahasiswa')
            ->toArray();
        
        if (empty($mahasiswaIds)) {
             return response()->json([
                'pending_frs' => [],
                'message' => 'Tidak ada mahasiswa di kelas perwalian Anda, tidak ada FRS pending.'
            ], 200);
        }
            
        $pendingFrs = FRS::with(['mahasiswa.user', 'matakuliah.dosen.user'])
            ->whereIn('id_mahasiswa', $mahasiswaIds)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan terbaru
            ->get();
            
        return response()->json([
            'pending_frs' => $pendingFrs,
            'message' => 'Daftar FRS pending berhasil diambil'
        ]);
    }
    
    /**
     * Approve or reject FRS
     * For dosen wali
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveFRS(Request $request)
    {
        $validator = $request->validate([ // Menggunakan $request->validate() lebih ringkas
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => 'required|in:disetujui,ditolak'
        ]);
        
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }
        
        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat menyetujui atau menolak FRS'], 403);
        }
        
        $frs = FRS::with('mahasiswa')->find($request->id_frs); // $request->id_frs sudah divalidasi
            
        if (!$frs) {
            // Seharusnya tidak terjadi karena 'exists' rule, tapi sebagai fallback
            return response()->json(['message' => 'FRS tidak ditemukan'], 404);
        }

        // Verifikasi apakah mahasiswa dari FRS ini adalah mahasiswa perwalian dosen
        $mahasiswa = $frs->mahasiswa; // Mahasiswa sudah di-load
        if (!$mahasiswa) {
             return response()->json(['message' => 'Mahasiswa terkait FRS tidak ditemukan'], 404);
        }

        $kelasMahasiswa = Kelas::find($mahasiswa->id_kelas);
        if (!$kelasMahasiswa || $kelasMahasiswa->id_dosen_wali != $dosen->id_dosen) {
             return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk FRS mahasiswa ini'
            ], 403);
        }
        
        $frs->status = $request->status;
        // $frs->approved_by_dosen_wali_id = $dosen->id_dosen; // Opsional
        // $frs->approval_date = now(); // Opsional
        $frs->save();
        
        return response()->json([
            // Load relasi yang dibutuhkan oleh Flutter setelah update
            'frs' => $frs->load(['mahasiswa.user', 'matakuliah.dosen.user']), 
            'message' => "FRS berhasil " . ($request->status == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }

    /**
     * Get all FRS (pending, disetujui, ditolak) for a specific mahasiswa
     * who is under the current Dosen Wali.
     *
     * @param Request $request
     * @param int $id_mahasiswa
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFrsForMahasiswa(Request $request, $id_mahasiswa)
    {
        $user = $request->user(); // Dosen yang sedang login
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat melihat FRS mahasiswa'], 403);
        }

        // Cari mahasiswa berdasarkan ID yang diberikan
        $mahasiswa = Mahasiswa::find($id_mahasiswa);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        // Verifikasi apakah mahasiswa ini adalah mahasiswa perwalian dosen yang sedang login
        $kelasMahasiswa = Kelas::find($mahasiswa->id_kelas);
        if (!$kelasMahasiswa || $kelasMahasiswa->id_dosen_wali != $dosen->id_dosen) {
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk melihat FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda.'
            ], 403);
        }

        // Ambil semua FRS untuk mahasiswa tersebut
        $allFrsMahasiswa = FRS::with(['mahasiswa.user', 'matakuliah.dosen.user'])
            ->where('id_mahasiswa', $id_mahasiswa)
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan terbaru
            ->get();

        return response()->json([
            'frs_items' => $allFrsMahasiswa, // Key 'frs_items' sesuai dengan yang diharapkan Flutter
            'message' => 'Semua data FRS untuk mahasiswa ' . $mahasiswa->nama . ' berhasil diambil'
        ]);
    }
}
