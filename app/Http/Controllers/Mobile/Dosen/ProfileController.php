<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Kelas; // Diperlukan untuk kelas_wali dalam profil
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk debugging jika perlu
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Mengambil profil dosen yang terautentikasi.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Ambil data dosen beserta relasi user
        // Penting: Pastikan relasi 'user' sudah didefinisikan dengan benar di model Dosen
        $dosen = Dosen::with('user')->where('user_id', $user->id)->first();
            
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan.'
            ], 404);
        }
        
        // Siapkan data profil
        // Pastikan field yang dikembalikan sesuai dengan yang dibutuhkan oleh model Dosen di Flutter
        $profileData = [
            'id_dosen' => $dosen->id_dosen,
            'user_id' => $dosen->user_id,
            'nidn' => $dosen->nidn,
            'nama' => $dosen->user->name, // Ambil nama dari tabel users
            'email' => $dosen->user->email, // Ambil email dari tabel users
            'tanggal_lahir' => $dosen->tanggal_lahir,
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'is_dosen_wali' => (bool) $dosen->is_dosen_wali, // Cast ke boolean untuk konsistensi
            'created_at' => $dosen->created_at?->toIso8601String(), // Format ke ISO8601
            'updated_at' => $dosen->updated_at?->toIso8601String(), // Format ke ISO8601
        ];
        
        // Jika dosen adalah dosen wali, ambil data kelas yang diampu
        if ($dosen->is_dosen_wali) {
            // Anda mungkin ingin memilih field tertentu dari Kelas untuk mengurangi ukuran respons
            $kelas = Kelas::where('id_dosen_wali', $dosen->id_dosen)->select(['id_kelas', 'nama_kelas', 'status'])->get();
            $profileData['kelas_wali'] = $kelas;
        }
        
        return response()->json([
            'dosen' => $profileData,
            'message' => 'Data dosen berhasil diambil.'
        ]);
    }
    
    /**
     * Memperbarui profil dosen yang terautentikasi.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan.'
            ], 404);
        }
        
        // Aturan validasi
        // 'sometimes' berarti field hanya divalidasi jika ada dalam request.
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id, // Pastikan user_id disertakan untuk mengabaikan email user saat ini
            'tanggal_lahir' => 'sometimes|nullable|date_format:Y-m-d', // Spesifikasikan format tanggal
            'jenis_kelamin' => 'sometimes|nullable|string|in:L,P',
            // PENTING: 'confirmed' berarti request HARUS menyertakan field 'password_confirmation' yang cocok.
            // Jika Flutter tidak mengirim 'password_confirmation', validasi akan gagal jika 'password' ada.
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422); // Kode status 422 untuk Unprocessable Entity
        }
        
        try {
            DB::beginTransaction();
            
            // Update data pada tabel Dosen (tanggal_lahir, jenis_kelamin)
            // Hanya update field yang ada dalam request dan tidak null (sesuai logika array_filter sebelumnya)
            $dosenUpdateData = [];
            if ($request->has('tanggal_lahir')) { // Cek 'has' untuk bisa mengirim null secara eksplisit jika 'nullable'
                $dosenUpdateData['tanggal_lahir'] = $request->input('tanggal_lahir');
            }
            if ($request->has('jenis_kelamin')) {
                $dosenUpdateData['jenis_kelamin'] = $request->input('jenis_kelamin');
            }

            if (!empty($dosenUpdateData)) {
                $dosen->update($dosenUpdateData);
            }
            
            // Update data pada tabel User (name, email, password)
            $userUpdateData = [];
            if ($request->filled('nama')) { // 'filled' mengecek apakah field ada dan tidak kosong
                $userUpdateData['name'] = $request->input('nama');
            }
            if ($request->filled('email')) {
                $userUpdateData['email'] = $request->input('email');
            }
            if ($request->filled('password')) { // Hanya hash dan update password jika field 'password' diisi dan tidak kosong
                $userUpdateData['password'] = Hash::make($request->input('password'));
            }
            
            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }
            
            DB::commit();
            
            // Ambil ulang data dosen yang sudah terupdate beserta relasi user untuk respons
            // Ini adalah praktik yang baik untuk memastikan data yang dikembalikan adalah yang paling baru.
            $updatedDosen = Dosen::with('user')->find($dosen->id_dosen); 
            
            $profileDataResponse = [
                'id_dosen' => $updatedDosen->id_dosen,
                'user_id' => $updatedDosen->user_id,
                'nidn' => $updatedDosen->nidn,
                'nama' => $updatedDosen->user->name,
                'email' => $updatedDosen->user->email,
                'tanggal_lahir' => $updatedDosen->tanggal_lahir,
                'jenis_kelamin' => $updatedDosen->jenis_kelamin,
                'is_dosen_wali' => (bool) $updatedDosen->is_dosen_wali,
                'created_at' => $updatedDosen->created_at?->toIso8601String(),
                'updated_at' => $updatedDosen->updated_at?->toIso8601String(),
            ];
            
            // CATATAN PENTING MENGENAI CACHING (jika ada):
            // Jika Anda menggunakan sistem caching (misalnya Redis, Memcached, Laravel Route/Config/Model Caching)
            // untuk endpoint GET /profile, Anda PERLU meng-invalidate atau memperbarui cache di sini
            // setelah data berhasil diupdate agar request GET berikutnya dari Flutter mendapatkan data terbaru.
            // Contoh (konseptual): Cache::forget('dosen_profile_' . $user->id);

            return response()->json([
                'dosen' => $profileDataResponse,
                'message' => 'Profil berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Update Profile Error: ' . $e->getMessage(), ['user_id' => $user->id]); // Log error untuk diagnosis
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui profil.',
                'error' => $e->getMessage() // Di produksi, mungkin jangan kirim detail error mentah
            ], 500);
        }
    }
}