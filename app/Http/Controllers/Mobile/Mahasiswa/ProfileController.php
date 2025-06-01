<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $userUpdated = false;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
                $userUpdated = true;
            }
            
            if ($userUpdated) {
                $user->save();
            } else {
                DB::rollBack(); 
                 return response()->json([
                     'message' => 'Tidak ada data password yang valid dikirim untuk diperbarui.'
                 ], 400); 
            }

            DB::commit();

            return response()->json([
                'message' => 'Password berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Password Mahasiswa Gagal (Simplified): ' . $e->getMessage(), [
                'user_id' => $user->id, 
                'request_data' => $request->except('password', 'password_confirmation') 
            ]);
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui password.'], 500);
        }
    }
}
