<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Kelas;

class WaliController extends Controller
{
    /**
     * Get mahasiswa in dosen wali's kelas
     * For dosen wali
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMahasiswaWali(Request $request)
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
                'message' => 'Anda bukan dosen wali'
            ], 403);
        }
        
        // Get kelas where dosen is wali, and load mahasiswa for each kelas
        $kelasWali = Kelas::where('id_dosen_wali', $dosen->id_dosen)
                           ->with('mahasiswa.user') // Eager load mahasiswa and their user data
                           ->get();
        
        if ($kelasWali->isEmpty()) {
            return response()->json([
                // 'data' => [], // optional to return empty array
                'message' => 'Anda tidak menjadi wali untuk kelas manapun saat ini'
            ], 404); // or 200 with empty data
        }
        
        // The data is already structured by $kelasWali containing its mahasiswa
        // If you want the exact previous structure:
        $data = $kelasWali->map(function($k) {
            return [
                'kelas' => $k, // Contains all kelas attributes
                'mahasiswa' => $k->mahasiswa // Mahasiswa already loaded with user
            ];
        });

        return response()->json([
            'data' => $data,
            'message' => 'Data mahasiswa wali berhasil diambil'
        ]);
    }
}