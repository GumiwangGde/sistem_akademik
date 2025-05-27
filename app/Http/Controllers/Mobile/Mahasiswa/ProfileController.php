<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth::user(); 

        $mahasiswa = Mahasiswa::with(['user', 'kelas.dosenWali.user', 'prodi', 'kelas.tahunAjaran', 'kelas.prodi']) 
            ->where('user_id', $user->id)
            ->first();
            
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $ipkKumulatif = $mahasiswa->hitungIpkKumulatif(); 
        $batasSksSemesterIni = $mahasiswa->hitungBatasSks();
        
        $profileData = [
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'user_id' => $mahasiswa->user_id,
            'nrp' => $mahasiswa->nrp,
            'nama' => $mahasiswa->user?->name ?? $mahasiswa->nama ?? 'N/A',
            'email' => $mahasiswa->user?->email ?? 'N/A', 
            
            'prodi' => $mahasiswa->prodi?->nama_prodi,
            'id_prodi_mahasiswa' => $mahasiswa->id_prodi,

            'id_kelas' => $mahasiswa->id_kelas,
            'kelas' => $mahasiswa->kelas?->nama_kelas,
            'tahun_ajaran_kelas' => $mahasiswa->kelas?->tahunAjaran?->nama_tahun_ajaran,
            
            'dosen_wali' => $mahasiswa->kelas?->dosenWali?->user?->name ?? $mahasiswa->kelas?->dosenWali?->nama ?? null,
            
            'created_at' => $mahasiswa->created_at ? $mahasiswa->created_at->toDateTimeString() : null,
            'updated_at' => $mahasiswa->updated_at ? $mahasiswa->updated_at->toDateTimeString() : null,
        ];
        
        return response()->json([
            'profile' => $profileData, 
            'message' => 'Data profil mahasiswa berhasil diambil'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $validatedData = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui.'
        ]);
    }
}
