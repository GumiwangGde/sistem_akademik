<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Kelas;

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
        
        // Check if dosen wali
        if (!$dosen->is_dosen_wali) {
            return response()->json([
                'message' => 'Hanya dosen wali yang dapat melihat FRS pending'
            ], 403);
        }
        
        // Get kelas where dosen is wali
        $kelasIds = Kelas::where('id_dosen_wali', $dosen->id_dosen)
            ->pluck('id_kelas')
            ->toArray();
            
        if (empty($kelasIds)) {
            return response()->json([
                'message' => 'Anda tidak menjadi wali untuk kelas manapun saat ini'
            ], 404); // Or 200 with an empty list
        }
        
        // Get mahasiswa in these kelas
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasIds)
            ->pluck('id_mahasiswa')
            ->toArray();
            
        // Get pending FRS for these mahasiswa
        $pendingFrs = FRS::with(['mahasiswa.user', 'matakuliah.dosen.user']) // Eager load details
            ->whereIn('id_mahasiswa', $mahasiswaIds)
            ->where('status', 'pending')
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
        $request->validate([
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => 'required|in:disetujui,ditolak'
        ]);
        
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Check if dosen wali
        if (!$dosen->is_dosen_wali) {
            return response()->json([
                'message' => 'Hanya dosen wali yang dapat menyetujui atau menolak FRS'
            ], 403);
        }
        
        // Get FRS to be approved/rejected
        $frs = FRS::with('mahasiswa')->find($request->id_frs);
            
        if (!$frs) {
            return response()->json([
                'message' => 'FRS tidak ditemukan'
            ], 404);
        }

        // Check if the mahasiswa related to the FRS is under this dosen wali
        $mahasiswaKelas = Kelas::find($frs->mahasiswa->id_kelas);
        if (!$mahasiswaKelas || $mahasiswaKelas->id_dosen_wali != $dosen->id_dosen) {
             return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk FRS mahasiswa ini'
            ], 403);
        }
        
        // Update FRS status
        $frs->status = $request->status;
        // Optionally, set approver_id if you have such a field
        // $frs->approved_by_dosen_wali_id = $dosen->id_dosen;
        // $frs->approval_date = now();
        $frs->save();
        
        return response()->json([
            'frs' => $frs->load(['mahasiswa.user', 'matakuliah']), // Return with loaded relations
            'message' => "FRS berhasil " . ($request->status == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }
}