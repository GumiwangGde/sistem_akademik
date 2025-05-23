<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * Get authenticated mahasiswa data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Get mahasiswa data with relationships
        $mahasiswa = Mahasiswa::with(['kelas', 'kelas.dosenWali'])
            ->where('user_id', $user->id)
            ->first();
            
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'mahasiswa' => $mahasiswa,
            'message' => 'Data mahasiswa berhasil diambil'
        ]);
    }
    
    /**
     * Get available matakuliah for FRS
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableMatakuliah(Request $request)
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get all matakuliah for mahasiswa's kelas
        $matakuliah = Matakuliah::with(['dosen', 'kelas', 'ruang'])
            ->where('kelas_id', $mahasiswa->id_kelas)
            ->get();
            
        // Get existing FRS entries
        $existingFrs = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->pluck('id_mk')
            ->toArray();
            
        // Filter out matakuliah that are already in FRS
        $availableMatakuliah = $matakuliah->filter(function($item) use ($existingFrs) {
            return !in_array($item->id_mk, $existingFrs);
        });
        
        return response()->json([
            'matakuliah' => $availableMatakuliah->values(),
            'message' => 'Daftar mata kuliah tersedia berhasil diambil'
        ]);
    }
    
    /**
     * Get mahasiswa's FRS entries
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFRS(Request $request)
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get all FRS entries with matakuliah details
        $frsEntries = FRS::with(['matakuliah', 'matakuliah.dosen', 'matakuliah.ruang'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->get();
            
        return response()->json([
            'frs' => $frsEntries,
            'message' => 'Data FRS berhasil diambil'
        ]);
    }
    
    /**
     * Create new FRS entry
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFRS(Request $request)
    {
        $request->validate([
            'id_mk' => 'required|exists:matakuliah,id_mk'
        ]);
        
        $user = $request->user();
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
                'message' => 'Mata kuliah ini sudah diambil dalam FRS'
            ], 422);
        }
        
        // Create new FRS entry
        $frs = new FRS();
        $frs->id_mahasiswa = $mahasiswa->id_mahasiswa;
        $frs->id_mk = $request->id_mk;
        $frs->status = 'pending';
        $frs->save();
        
        // Create empty nilai record
        $nilai = new Nilai();
        $nilai->id_frs = $frs->id_frs;
        $nilai->status_penilaian = 'belum_dinilai';
        $nilai->save();
        
        return response()->json([
            'frs' => $frs->load('matakuliah'),
            'message' => 'FRS berhasil dibuat dan menunggu persetujuan'
        ], 201);
    }
    
    /**
     * Delete FRS entry
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFRS(Request $request, $id)
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Find FRS entry
        $frs = FRS::where('id_frs', $id)
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->first();
            
        if (!$frs) {
            return response()->json([
                'message' => 'Data FRS tidak ditemukan'
            ], 404);
        }
        
        // Only allow deleting pending FRS
        if ($frs->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya FRS dengan status pending yang dapat dihapus'
            ], 422);
        }
        
        // Delete FRS (cascade will delete nilai record too)
        $frs->delete();
        
        return response()->json([
            'message' => 'FRS berhasil dihapus'
        ]);
    }
    
    /**
     * Get schedule (jadwal) for mahasiswa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwal(Request $request)
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get approved FRS entries with matakuliah details
        $jadwal = FRS::with(['matakuliah', 'matakuliah.dosen', 'matakuliah.ruang'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($frs) {
                return $frs->matakuliah;
            });
        
        // Group by day
        $jadwalByHari = $jadwal->groupBy('hari');
        
        return response()->json([
            'jadwal' => $jadwalByHari,
            'message' => 'Jadwal berhasil diambil'
        ]);
    }
    
    /**
     * Get nilai for mahasiswa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNilai(Request $request)
    {
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get FRS entries with nilai and matakuliah details
        $nilaiData = FRS::with(['nilai', 'matakuliah'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($frs) {
                return [
                    'id_mk' => $frs->matakuliah->id_mk,
                    'kode_mk' => $frs->matakuliah->kode_mk,
                    'nama_mk' => $frs->matakuliah->nama_mk,
                    'sks' => $frs->matakuliah->sks,
                    'semester' => $frs->matakuliah->semester,
                    'nilai_angka' => $frs->nilai->nilai_angka,
                    'nilai_huruf' => $frs->nilai->nilai_huruf,
                    'status_penilaian' => $frs->nilai->status_penilaian,
                ];
            });
        
        // Calculate IP if has nilai
        $totalSks = 0;
        $totalNilai = 0;
        $ip = null;
        
        foreach ($nilaiData as $item) {
            if ($item['status_penilaian'] === 'sudah_dinilai' && !is_null($item['nilai_angka'])) {
                $totalSks += $item['sks'];
                $totalNilai += ($item['nilai_angka'] * $item['sks']);
            }
        }
        
        if ($totalSks > 0) {
            $ip = round($totalNilai / $totalSks, 2);
        }
        
        return response()->json([
            'nilai' => $nilaiData,
            'ip' => $ip,
            'total_sks' => $totalSks,
            'message' => 'Data nilai berhasil diambil'
        ]);
    }
}