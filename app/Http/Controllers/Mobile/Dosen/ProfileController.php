<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Kelas; // Needed for kelas_wali in profile
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get authenticated dosen profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Get dosen data with user relationships
        $dosen = Dosen::with('user')->where('user_id', $user->id)->first();
            
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Prepare profile data including NIDN and email
        $profileData = [
            'id_dosen' => $dosen->id_dosen,
            'user_id' => $dosen->user_id,
            'nidn' => $dosen->nidn,
            'nama' => $dosen->user->name, // Ambil nama dari tabel users
            'email' => $dosen->user->email,
            'tanggal_lahir' => $dosen->tanggal_lahir, // Added this, was missing in original output but present in update logic
            'jenis_kelamin' => $dosen->jenis_kelamin, // Added this
            'is_dosen_wali' => $dosen->is_dosen_wali,
            'created_at' => $dosen->created_at,
            'updated_at' => $dosen->updated_at
        ];
        
        // If dosen wali, get assigned kelas
        if ($dosen->is_dosen_wali) {
            $kelas = Kelas::where('id_dosen_wali', $dosen->id_dosen)->get();
            $profileData['kelas_wali'] = $kelas;
        }
        
        return response()->json([
            'dosen' => $profileData,
            'message' => 'Data dosen berhasil diambil'
        ]);
    }
    
    /**
     * Update dosen profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        // Get dosen data
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'tanggal_lahir' => 'sometimes|nullable|date',
            'jenis_kelamin' => 'sometimes|nullable|in:L,P',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Update dosen data
            $dosenData = $request->only(['tanggal_lahir', 'jenis_kelamin']);
            $dosenData = array_filter($dosenData, function($value) {
                return $value !== null;
            });
            
            if (!empty($dosenData)) {
                $dosen->fill($dosenData);
                $dosen->save();
            }
            
            // Update user data (name, email and password)
            $userData = [];
            if ($request->has('nama')) {
                $userData['name'] = $request->nama;
            }
            if ($request->has('email')) {
                $userData['email'] = $request->email;
            }
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            if (!empty($userData)) {
                $user->fill($userData);
                $user->save();
            }
            
            DB::commit();
            
            // Return updated profile
            // It's good practice to reload the model to get all transformations/casts applied
            $updatedDosen = Dosen::with('user')->find($dosen->id_dosen); 
            
            $profileData = [
                'id_dosen' => $updatedDosen->id_dosen,
                'user_id' => $updatedDosen->user_id,
                'nidn' => $updatedDosen->nidn,
                'nama' => $updatedDosen->user->name,
                'email' => $updatedDosen->user->email,
                'tanggal_lahir' => $updatedDosen->tanggal_lahir,
                'jenis_kelamin' => $updatedDosen->jenis_kelamin,
                'is_dosen_wali' => $updatedDosen->is_dosen_wali,
                'created_at' => $updatedDosen->created_at,
                'updated_at' => $updatedDosen->updated_at
            ];
            
            return response()->json([
                'dosen' => $profileData,
                'message' => 'Profile berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}