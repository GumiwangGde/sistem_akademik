<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai; // Diperlukan untuk membuat record nilai saat FRS dibuat
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Jika menggunakan transaksi

class FrsController extends Controller
{
    /**
     * Get available matakuliah for FRS.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function getAvailableMatakuliah(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get all matakuliah for mahasiswa's kelas
        // Asumsi relasi 'dosen.user' untuk nama dosen pengampu
        $matakuliah = Matakuliah::with(['dosen.user', 'kelas', 'ruang'])
            // VVV BARIS KUNCI VVV
            ->where('kelas_id', $mahasiswa->id_kelas) // Pastikan ini sesuai dengan semester mahasiswa juga jika perlu
            // ^^^ BARIS KUNCI ^^^
            ->get();
            
        // Get existing FRS entries for the current student
        $existingFrsMkIds = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->pluck('id_mk')
            ->toArray();
            
        // Filter out matakuliah that are already in FRS
        $availableMatakuliah = $matakuliah->filter(function($item) use ($existingFrsMkIds) {
            return !in_array($item->id_mk, $existingFrsMkIds);
        })->map(function($mk) { // Format output jika perlu
            return [
                'id_mk' => $mk->id_mk,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks' => $mk->sks,
                'semester' => $mk->semester,
                'dosen_pengampu' => $mk->dosen && $mk->dosen->user ? $mk->dosen->user->name : 'N/A',
                'ruang' => $mk->ruang ? $mk->ruang->nama_ruang : 'N/A',
                'hari' => $mk->hari,
                'jam_mulai' => $mk->jam_mulai,
                'jam_selesai' => $mk->jam_selesai,
            ];
        });
        
        return response()->json([
            'matakuliah' => $availableMatakuliah->values(), // Menggunakan values() untuk reset keys array
            'message' => 'Daftar mata kuliah tersedia berhasil diambil'
        ]);
    }

    /**
     * Get mahasiswa's FRS entries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFRS(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get all FRS entries with matakuliah details
        // Asumsi relasi 'matakuliah.dosen.user'
        $frsEntries = FRS::with(['matakuliah', 'matakuliah.dosen.user', 'matakuliah.ruang', 'matakuliah.kelas'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan terbaru
            ->get()
            ->map(function($frs){
                 return [
                    'id_frs' => $frs->id_frs,
                    'status' => $frs->status,
                    'matakuliah' => [
                        'id_mk' => $frs->matakuliah->id_mk,
                        'kode_mk' => $frs->matakuliah->kode_mk,
                        'nama_mk' => $frs->matakuliah->nama_mk,
                        'sks' => $frs->matakuliah->sks,
                        'semester' => $frs->matakuliah->semester,
                        'dosen_pengampu' => $frs->matakuliah->dosen && $frs->matakuliah->dosen->user ? $frs->matakuliah->dosen->user->name : 'N/A',
                        'ruang' => $frs->matakuliah->ruang ? $frs->matakuliah->ruang->nama_ruang : 'N/A',
                        'hari' => $frs->matakuliah->hari,
                        'jam_mulai' => $frs->matakuliah->jam_mulai,
                        'jam_selesai' => $frs->matakuliah->jam_selesai,
                    ]
                 ];
            });
            
        return response()->json([
            'frs' => $frsEntries,
            'message' => 'Data FRS berhasil diambil'
        ]);
    }

    /**
     * Create new FRS entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFRS(Request $request)
    {
        $request->validate([
            'id_mk' => 'required|exists:matakuliah,id_mk'
        ]);
        
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Check if already exists
        $existingFrs = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_mk', $request->id_mk)
            ->first();
            
        if ($existingFrs) {
            return response()->json([
                'message' => 'Mata kuliah ini sudah ada dalam FRS Anda.'
            ], 422); // 422 Unprocessable Entity
        }
        
        // Pastikan matakuliah sesuai dengan kelas mahasiswa atau aturan lain jika ada
        $matakuliah = Matakuliah::find($request->id_mk);
        if (!$matakuliah || $matakuliah->kelas_id != $mahasiswa->id_kelas) {
            // Tambahkan logika validasi lain jika matakuliah bisa diambil lintas kelas/semester
            // return response()->json([
            //     'message' => 'Mata kuliah tidak tersedia untuk kelas Anda.'
            // ], 422);
        }

        DB::beginTransaction();
        try {
            // Create new FRS entry
            $frs = new FRS();
            $frs->id_mahasiswa = $mahasiswa->id_mahasiswa;
            $frs->id_mk = $request->id_mk;
            $frs->status = 'pending'; // Default status
            $frs->save();
            
            // Create empty nilai record associated with this FRS
            // Ini penting agar saat dosen input nilai, record nilai sudah ada
            Nilai::create([
                'id_frs' => $frs->id_frs,
                'status_penilaian' => 'belum_dinilai',
                // nilai_angka dan nilai_huruf akan null by default
            ]);

            DB::commit();
            
            return response()->json([
                'frs' => $frs->load('matakuliah'), // Kirim FRS yang baru dibuat beserta detail matakuliahnya
                'message' => 'FRS berhasil ditambahkan dan menunggu persetujuan.'
            ], 201); // 201 Created

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Gagal membuat FRS. Terjadi kesalahan.',
                'error' => $e->getMessage() // Hanya untuk debugging, jangan tampilkan di produksi
            ], 500);
        }
    }

    /**
     * Delete FRS entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_frs // Menggunakan id_frs dari path
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFRS(Request $request, $id_frs) // $id_frs dari parameter route
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Find FRS entry
        $frs = FRS::where('id_frs', $id_frs)
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa) // Pastikan FRS milik mahasiswa yang login
            ->first();
            
        if (!$frs) {
            return response()->json([
                'message' => 'Data FRS tidak ditemukan atau Anda tidak berhak menghapusnya.'
            ], 404);
        }
        
        // Only allow deleting FRS if its status is 'pending'
        if ($frs->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya FRS dengan status "pending" yang dapat dihapus.'
            ], 422); // 422 Unprocessable Entity
        }
        
        // Delete FRS (relasi ke nilai akan terhapus otomatis jika onDelete('cascade') diset di migrasi nilai)
        // Jika tidak ada cascade, hapus manual record nilai: Nilai::where('id_frs', $frs->id_frs)->delete();
        $frs->delete();
        
        return response()->json([
            'message' => 'FRS berhasil dihapus.'
        ]); // 200 OK atau 204 No Content
    }
}
