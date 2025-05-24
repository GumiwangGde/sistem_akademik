<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah;
// Kelas and Ruang might be needed if Matakuliah model has direct relations or for eager loading details
// use App\Models\Kelas; 
// use App\Models\Ruang; 

class MatakuliahController extends Controller
{
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
        
        $query = Matakuliah::with(['kelas', 'ruang']) 
            ->where('id_dosen', $dosen->id_dosen);

        $semester = $request->query('semester');

        if ($semester) {
            $query->where('semester', $semester); 
        }

        $matakuliah = $query->get();
            
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
        
        // Get matakuliah taught by this dosen (which forms the schedule)
        // Adjust 'with' as per your Matakuliah model relations
        $jadwal = Matakuliah::with(['kelas', 'ruang']) 
            ->where('id_dosen', $dosen->id_dosen)
            ->get();
        
        // Group by day
        $jadwalByHari = $jadwal->groupBy('hari'); 
        // Make sure your Matakuliah model has a 'hari' attribute or accessor
        
        return response()->json([
            'jadwal' => $jadwalByHari,
            'message' => 'Jadwal berhasil diambil'
        ]);
    }
}