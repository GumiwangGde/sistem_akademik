<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        
        $dosen = Dosen::with('user')->where('user_id', $user->id)->first();
            
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan.'
            ], 404);
        }
        
        $profileData = [
            'id_dosen' => $dosen->id_dosen,
            'user_id' => $dosen->user_id,
            'nidn' => $dosen->nidn,
            'nama' => $dosen->user->name,
            'email' => $dosen->user->email,
            'is_dosen_wali' => (bool) $dosen->is_dosen_wali,
            'created_at' => $dosen->created_at?->toIso8601String(),
            'updated_at' => $dosen->updated_at?->toIso8601String(),
        ];
        
        if ($dosen->is_dosen_wali) {
            $kelas = Kelas::where('id_dosen_wali', $dosen->id_dosen)->select(['id_kelas', 'nama_kelas', 'status'])->get();
            $profileData['kelas_wali'] = $kelas;
        }
        
        return response()->json([
            'dosen' => $profileData,
            'message' => 'Data dosen berhasil diambil.'
        ]);
    }
    
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan.'
            ], 404);
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
        
        try {
            DB::beginTransaction();
            
            $dosenUpdateData = [];

            if (!empty($dosenUpdateData)) {
                $dosen->update($dosenUpdateData);
            }
            
            $userUpdateData = [];
            if ($request->filled('password')) {
                $userUpdateData['password'] = Hash::make($request->input('password'));
            }
            
            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }
            
            DB::commit();
            
            $updatedDosen = Dosen::with('user')->find($dosen->id_dosen); 
            
            $profileDataResponse = [
                'id_dosen' => $updatedDosen->id_dosen,
                'user_id' => $updatedDosen->user_id,
                'nidn' => $updatedDosen->nidn,
                'nama' => $updatedDosen->user->name,
                'email' => $updatedDosen->user->email,
                'is_dosen_wali' => (bool) $updatedDosen->is_dosen_wali,
                'created_at' => $updatedDosen->created_at?->toIso8601String(),
                'updated_at' => $updatedDosen->updated_at?->toIso8601String(),
            ];
            
            return response()->json([
                'dosen' => $profileDataResponse,
                'message' => 'Profil berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Update Profile Error: ' . $e->getMessage(), ['user_id' => $user->id]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}