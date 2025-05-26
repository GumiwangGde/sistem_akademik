<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function getNilai(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $nilaiData = FRS::with([
            'nilai', 
            'jadwalKuliah' => function ($query) { 
                $query->with([
                    'masterMatakuliah', 
                ]);
            },
            'tahunAjaran'
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('status', 'disetujui') 
        ->whereHas('nilai', function ($query) { 
            $query->where('status_penilaian', 'sudah_dinilai');
        })
        ->orderBy('id_tahun_ajaran', 'desc') 
        ->orderBy('created_at', 'desc')    
        ->get()
        ->map(function ($frs) { 
            if (!$frs->jadwalKuliah || !$frs->jadwalKuliah->masterMatakuliah || !$frs->nilai) {
                Log::warning('Data FRS tidak lengkap untuk mapping nilai (Mahasiswa).', [
                    'id_frs' => $frs->id_frs, 
                    'has_jadwal_kuliah' => !is_null($frs->jadwalKuliah),
                    'has_master_mk' => !is_null($frs->jadwalKuliah?->masterMatakuliah),
                    'has_nilai' => !is_null($frs->nilai)
                ]);
                return null; 
            }

            $masterMk = $frs->jadwalKuliah->masterMatakuliah;
            
            $sks = $masterMk->sks_total ?? $masterMk->sks ?? 0;
            
            return [
                'id_frs' => $frs->id_frs,
                'id_mk_jadwal' => $frs->jadwalKuliah->id_mk, 
                'kode_mk' => $masterMk->kode_mk,
                'nama_mk' => $masterMk->nama_mk,
                'sks' => (int) $sks, 
                'semester_mk_diambil' => $frs->jadwalKuliah->semester, 
                'nilai_angka' => $frs->nilai->nilai_angka, 
                'nilai_huruf' => $frs->nilai->nilai_huruf,
                'status_penilaian' => $frs->nilai->status_penilaian,
                'tahun_ajaran_frs' => $frs->tahunAjaran?->nama_tahun_ajaran, 
            ];
        })
        ->filter() 
        ->values(); 

        $totalSksKumulatif = 0;
        $totalBobotNilaiKumulatif = 0;
        $ipkKumulatif = null;
        $bobotNilaiHuruf = ['A' => 4.0, 'A-' => 3.75, 'B+' => 3.25, 'B' => 3.0, 'B-' => 2.75, 'C+' => 2.25, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0];
        
        foreach ($nilaiData as $item) {
            if ($item && 
                isset($item['status_penilaian']) && $item['status_penilaian'] === 'sudah_dinilai' && 
                !empty($item['nilai_huruf']) && isset($bobotNilaiHuruf[$item['nilai_huruf']]) &&
                isset($item['sks']) && $item['sks'] > 0) {
                    
                $totalSksKumulatif += $item['sks'];
                $totalBobotNilaiKumulatif += ($bobotNilaiHuruf[$item['nilai_huruf']] * $item['sks']);
            }
        }
        
        if ($totalSksKumulatif > 0) {
            $ipkKumulatif = round($totalBobotNilaiKumulatif / $totalSksKumulatif, 2);
        }
            
        return response()->json([
            'semua_nilai' => $nilaiData, 
            'ipk_kumulatif' => $ipkKumulatif, 
            'total_sks_kumulatif' => $totalSksKumulatif, 
            'message' => 'Data semua nilai yang telah diproses berhasil diambil'
        ]);
    }
}