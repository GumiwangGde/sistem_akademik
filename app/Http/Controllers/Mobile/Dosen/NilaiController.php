<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\FRS;
use App\Models\Nilai;
// Mahasiswa model might be needed for specific formatting or if FRS relation doesn't bring all info
// use App\Models\Mahasiswa; 

class NilaiController extends Controller
{
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
                'message' => 'Mata kuliah tidak ditemukan atau bukan mata kuliah yang Anda ampu'
            ], 404); // Or 403 if found but not theirs
        }
        
        // Get mahasiswa who have approved FRS for this matakuliah
        // And their existing nilai record (if any)
        $mahasiswaData = FRS::with(['mahasiswa.user', 'nilai']) // Eager load user details
            ->where('id_mk', $id_mk)
            ->where('status', 'disetujui') // Only those whose FRS is approved
            ->get()
            ->map(function ($frs) {
                // Ensure nilai exists or provide a default structure
                $nilaiData = $frs->nilai ?? new Nilai(); // Or handle null explicitly

                return [
                    'id_frs' => $frs->id_frs,
                    'id_mahasiswa' => $frs->mahasiswa->id_mahasiswa,
                    'nrp' => $frs->mahasiswa->nrp,
                    'nama' => $frs->mahasiswa->user->name, // Assuming Mahasiswa has a user relation for name
                    'id_nilai' => $nilaiData->id_nilai, // Can be null if no Nilai record yet
                    'nilai_angka' => $nilaiData->nilai_angka,
                    'nilai_huruf' => $nilaiData->nilai_huruf,
                    'status_penilaian' => $nilaiData->status_penilaian ?? 'belum_dinilai' // Default if no record
                ];
            });
            
        return response()->json([
            'matakuliah' => $matakuliah->load('dosen.user'),
            'mahasiswa' => $mahasiswaData,
            'message' => 'Data mahasiswa untuk mata kuliah berhasil diambil'
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
            // 'id_frs' is more appropriate than 'id_nilai' if creating/updating based on FRS
            // If you always have an id_nilai, then it's fine.
            // Let's assume you pass id_frs and we find or create Nilai from it.
            'id_frs' => 'required|exists:frs,id_frs', 
            'nilai_angka' => 'required|numeric|min:0|max:100',
        ]);
        
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }
        
        // Get FRS record to find/update associated Nilai
        $frs = FRS::with('matakuliah')->find($request->id_frs);

        if (!$frs) {
            return response()->json(['message' => 'Data FRS tidak ditemukan'], 404);
        }

        // Check if this matakuliah belongs to the dosen
        if ($frs->matakuliah->id_dosen != $dosen->id_dosen) {
            return response()->json([
                'message' => 'Anda tidak berwenang mengisi nilai untuk mata kuliah ini'
            ], 403);
        }

        // Find or create the Nilai record for this FRS
        // Assuming a one-to-one: FRS hasOne Nilai, and Nilai belongsTo FRS
        // And your Nilai model has id_frs as foreign key.
        $nilai = Nilai::firstOrNew(['id_frs' => $frs->id_frs]);

        // if (!$nilai->exists) { // If it's a new record
        //     $nilai->id_mahasiswa = $frs->id_mahasiswa; // Set other necessary fields
        //     $nilai->id_mk = $frs->id_mk;
        // }
        
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
        // $nilai->tanggal_penilaian = now(); // Optional
        $nilai->save();
        
        return response()->json([
            'nilai' => $nilai->load('frs.mahasiswa.user'), // Return with loaded relations
            'message' => 'Nilai berhasil diinput/diperbarui'
        ]);
    }
}