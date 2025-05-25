<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan model User di-import jika belum

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
        $user = Auth::user(); // Mengambil user yang sedang terautentikasi

        // Mengambil data mahasiswa beserta relasi yang dibutuhkan
        // Penyesuaian: Menambahkan 'prodi' ke eager loading
        $mahasiswa = Mahasiswa::with(['user', 'kelas.dosenWali.user', 'prodi', 'kelas.tahunAjaran', 'kelas.prodi']) 
            ->where('user_id', $user->id)
            ->first();
            
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Format data untuk respons JSON
        // Penyesuaian:
        // 1. Mengambil 'nama' dari $mahasiswa->nama (jika ini nama utama mahasiswa)
        //    atau tetap $mahasiswa->user->name jika nama di tabel user yang jadi acuan.
        //    Berdasarkan model Mahasiswa Anda, ada field 'nama', jadi kita gunakan itu.
        // 2. Mengambil nama prodi dari relasi: $mahasiswa->prodi->nama_prodi
        $profileData = [
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'user_id' => $mahasiswa->user_id,
            'nrp' => $mahasiswa->nrp,
            'nama' => $mahasiswa->nama, // Mengambil nama dari tabel mahasiswa
            'email' => $mahasiswa->user ? $mahasiswa->user->email : $user->email, // Email dari relasi user, fallback ke auth user
            
            'prodi' => $mahasiswa->prodi ? $mahasiswa->prodi->nama_prodi : null,
            'id_prodi_mahasiswa' => $mahasiswa->id_prodi,

            'id_kelas' => $mahasiswa->id_kelas,
            'kelas' => $mahasiswa->kelas ? $mahasiswa->kelas->nama_kelas : null,
            'tahun_ajaran_kelas' => $mahasiswa->kelas && $mahasiswa->kelas->tahunAjaran ? $mahasiswa->kelas->tahunAjaran->nama_tahun_ajaran : null,
            'prodi_kelas' => $mahasiswa->kelas && $mahasiswa->kelas->prodi ? $mahasiswa->kelas->prodi->nama_prodi : null,
            
            'dosen_wali' => $mahasiswa->kelas && $mahasiswa->kelas->dosenWali && $mahasiswa->kelas->dosenWali->user
                            ? $mahasiswa->kelas->dosenWali->user->name // Asumsi nama dosen ada di tabel user yang berelasi
                            : ($mahasiswa->kelas && $mahasiswa->kelas->dosenWali ? $mahasiswa->kelas->dosenWali->nama : null), // Fallback ke nama di tabel dosen jika ada
            
            'created_at' => $mahasiswa->created_at ? $mahasiswa->created_at->toDateTimeString() : null,
            'updated_at' => $mahasiswa->updated_at ? $mahasiswa->updated_at->toDateTimeString() : null,
        ];
        
        return response()->json([
            'profile' => $profileData, 
            'message' => 'Data profil mahasiswa berhasil diambil'
        ]);
    }
}
