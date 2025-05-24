<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get authenticated mahasiswa data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = Auth::user(); // Menggunakan Auth::user() lebih umum
        
        // Get mahasiswa data with relationships
        $mahasiswa = Mahasiswa::with(['user', 'kelas', 'kelas.dosenWali.user']) 
            ->where('user_id', $user->id)
            ->first();
            
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Format data jika diperlukan
        $profileData = [
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'user_id' => $mahasiswa->user_id,
            'id_kelas' => $mahasiswa->id_kelas,
            'nrp' => $mahasiswa->nrp,
            'nama' => $mahasiswa->user->name, 
            'email' => $user->email, 
            'prodi' => $mahasiswa->prodi,
            'kelas' => $mahasiswa->kelas ? $mahasiswa->kelas->nama_kelas : null,
            'dosen_wali' => $mahasiswa->kelas && $mahasiswa->kelas->dosenWali && $mahasiswa->kelas->dosenWali->user
                            ? $mahasiswa->kelas->dosenWali->user->name
                            : null,
            'created_at' => $mahasiswa->created_at,
            'updated_at' => $mahasiswa->updated_at,
        ];
        
        return response()->json([
            'profile' => $profileData, 
            'message' => 'Data mahasiswa berhasil diambil'
        ]);
    }
}