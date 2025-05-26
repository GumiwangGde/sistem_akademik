<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Nilai;
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
            ];
        })
        ->filter() 
        ->values(); 

        return response()->json([
            'semua_nilai' => $nilaiData, 
            'message' => 'Data semua nilai yang telah diproses berhasil diambil'
        ]);
    }
}