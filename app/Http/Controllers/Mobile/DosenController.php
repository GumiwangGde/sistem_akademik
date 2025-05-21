<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\FRS;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DosenController extends Controller
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
        
        // Get dosen data with relationships
        $dosen = Dosen::where('user_id', $user->id)->first();
            
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // If dosen wali, get assigned kelas
        if ($dosen->is_dosen_wali) {
            $kelas = Kelas::where('id_dosen_wali', $dosen->id_dosen)->get();
            $dosen->kelas_wali = $kelas;
        }
        
        return response()->json([
            'dosen' => $dosen,
            'message' => 'Data dosen berhasil diambil'
        ]);
    }
    
    /**
     * Get matakuliah taught by dosen
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatakuliah(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Get matakuliah taught by this dosen
        $matakuliah = Matakuliah::with(['kelas', 'ruang'])
            ->where('id_dosen', $dosen->id_dosen)
            ->get();
            
        return response()->json([
            'matakuliah' => $matakuliah,
            'message' => 'Daftar mata kuliah berhasil diambil'
        ]);
    }
    
    /**
     * Get jadwal for dosen
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwal(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Get matakuliah taught by this dosen
        $jadwal = Matakuliah::with(['kelas', 'ruang'])
            ->where('id_dosen', $dosen->id_dosen)
            ->get();
        
        // Group by day
        $jadwalByHari = $jadwal->groupBy('hari');
        
        return response()->json([
            'jadwal' => $jadwalByHari,
            'message' => 'Jadwal berhasil diambil'
        ]);
    }
    
    /**
     * Get pending FRS that need approval
     * For dosen wali
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingFRS(Request $request)
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
                'message' => 'Hanya dosen wali yang dapat menyetujui FRS'
            ], 403);
        }
        
        // Get kelas where dosen is wali
        $kelasIds = Kelas::where('id_dosen_wali', $dosen->id_dosen)
            ->pluck('id_kelas')
            ->toArray();
            
        if (empty($kelasIds)) {
            return response()->json([
                'message' => 'Anda tidak menjadi wali untuk kelas manapun'
            ], 404);
        }
        
        // Get mahasiswa in these kelas
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasIds)
            ->pluck('id_mahasiswa')
            ->toArray();
            
        // Get pending FRS for these mahasiswa
        $pendingFrs = FRS::with(['mahasiswa', 'matakuliah'])
            ->whereIn('id_mahasiswa', $mahasiswaIds)
            ->where('status', 'pending')
            ->get();
            
        return response()->json([
            'pending_frs' => $pendingFrs,
            'message' => 'Daftar FRS pending berhasil diambil'
        ]);
    }
    
    /**
     * Approve or reject FRS
     * For dosen wali
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveFRS(Request $request)
    {
        $request->validate([
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => 'required|in:disetujui,ditolak'
        ]);
        
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
                'message' => 'Hanya dosen wali yang dapat menyetujui FRS'
            ], 403);
        }
        
        // Get kelas where dosen is wali
        $kelasIds = Kelas::where('id_dosen_wali', $dosen->id_dosen)
            ->pluck('id_kelas')
            ->toArray();
            
        // Get mahasiswa in these kelas
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasIds)
            ->pluck('id_mahasiswa')
            ->toArray();
            
        // Find FRS and check if mahasiswa is in dosen's wali class
        $frs = FRS::with('mahasiswa')
            ->where('id_frs', $request->id_frs)
            ->first();
            
        if (!$frs) {
            return response()->json([
                'message' => 'FRS tidak ditemukan'
            ], 404);
        }
        
        if (!in_array($frs->id_mahasiswa, $mahasiswaIds)) {
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk menyetujui FRS ini'
            ], 403);
        }
        
        // Update FRS status
        $frs->status = $request->status;
        $frs->save();
        
        return response()->json([
            'frs' => $frs,
            'message' => "FRS berhasil " . ($request->status == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }
    
    /**
     * Get mahasiswa for a specific matakuliah
     * For inputting nilai
     *
     * @param Request $request
     * @param int $id_mk
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMahasiswaByMatakuliah(Request $request, $id_mk)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Check if this is the dosen's matakuliah
        $matakuliah = Matakuliah::where('id_mk', $id_mk)
            ->where('id_dosen', $dosen->id_dosen)
            ->first();
            
        if (!$matakuliah) {
            return response()->json([
                'message' => 'Mata kuliah tidak ditemukan atau bukan milik Anda'
            ], 404);
        }
        
        // Get mahasiswa who have approved FRS for this matakuliah
        $mahasiswaData = FRS::with(['mahasiswa', 'nilai'])
            ->where('id_mk', $id_mk)
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($frs) {
                return [
                    'id_frs' => $frs->id_frs,
                    'id_mahasiswa' => $frs->mahasiswa->id_mahasiswa,
                    'nrp' => $frs->mahasiswa->nrp,
                    'nama' => $frs->mahasiswa->nama,
                    'id_nilai' => $frs->nilai->id_nilai,
                    'nilai_angka' => $frs->nilai->nilai_angka,
                    'nilai_huruf' => $frs->nilai->nilai_huruf,
                    'status_penilaian' => $frs->nilai->status_penilaian
                ];
            });
            
        return response()->json([
            'matakuliah' => $matakuliah,
            'mahasiswa' => $mahasiswaData,
            'message' => 'Data mahasiswa berhasil diambil'
        ]);
    }
    
    /**
     * Input nilai for mahasiswa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inputNilai(Request $request)
    {
        $request->validate([
            'id_nilai' => 'required|exists:nilai,id_nilai',
            'nilai_angka' => 'required|numeric|min:0|max:100',
        ]);
        
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Get nilai record
        $nilai = Nilai::with('frs.matakuliah')
            ->where('id_nilai', $request->id_nilai)
            ->first();
            
        if (!$nilai) {
            return response()->json([
                'message' => 'Data nilai tidak ditemukan'
            ], 404);
        }
        
        // Check if this matakuliah belongs to the dosen
        if ($nilai->frs->matakuliah->id_dosen != $dosen->id_dosen) {
            return response()->json([
                'message' => 'Anda tidak berwenang mengisi nilai ini'
            ], 403);
        }
        
        // Calculate nilai_huruf based on nilai_angka
        $nilaiHuruf = 'E';
        $nilaiAngka = $request->nilai_angka;
        
        if ($nilaiAngka >= 80) {
            $nilaiHuruf = 'A';
        } elseif ($nilaiAngka >= 70) {
            $nilaiHuruf = 'B';
        } elseif ($nilaiAngka >= 60) {
            $nilaiHuruf = 'C';
        } elseif ($nilaiAngka >= 50) {
            $nilaiHuruf = 'D';
        }
        
        // Update nilai
        $nilai->nilai_angka = $nilaiAngka;
        $nilai->nilai_huruf = $nilaiHuruf;
        $nilai->status_penilaian = 'sudah_dinilai';
        $nilai->save();
        
        return response()->json([
            'nilai' => $nilai,
            'message' => 'Nilai berhasil diinput'
        ]);
    }
    
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
        
        // Get kelas where dosen is wali
        $kelas = Kelas::where('id_dosen_wali', $dosen->id_dosen)->get();
        
        if ($kelas->isEmpty()) {
            return response()->json([
                'message' => 'Anda tidak menjadi wali untuk kelas manapun'
            ], 404);
        }
        
        $data = [];
        foreach ($kelas as $k) {
            $mahasiswa = Mahasiswa::where('id_kelas', $k->id_kelas)->get();
            $data[] = [
                'kelas' => $k,
                'mahasiswa' => $mahasiswa
            ];
        }
        
        return response()->json([
            'data' => $data,
            'message' => 'Data mahasiswa wali berhasil diambil'
        ]);
    }
}