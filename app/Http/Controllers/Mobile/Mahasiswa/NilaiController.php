<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Nilai; // Model Nilai
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Get nilai for mahasiswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNilai(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get FRS entries with nilai and matakuliah details
        // Hanya FRS yang disetujui yang akan memiliki nilai relevan
        $nilaiData = FRS::with(['nilai', 'matakuliah'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('status', 'disetujui') // Hanya tampilkan nilai dari FRS yang disetujui
            ->get()
            ->map(function ($frs) {
                // Pastikan relasi nilai dan matakuliah ada
                if (!$frs->matakuliah || !$frs->nilai) {
                    return null; // Atau handle error/skip item ini
                }
                return [
                    'id_mk' => $frs->matakuliah->id_mk,
                    'kode_mk' => $frs->matakuliah->kode_mk,
                    'nama_mk' => $frs->matakuliah->nama_mk,
                    'sks' => $frs->matakuliah->sks,
                    'semester' => $frs->matakuliah->semester, // Semester dari matakuliah
                    'nilai_angka' => $frs->nilai->nilai_angka,
                    'nilai_huruf' => $frs->nilai->nilai_huruf,
                    'status_penilaian' => $frs->nilai->status_penilaian,
                ];
            })
            ->filter(); // Menghapus item null jika ada
            
        // Calculate IP (Indeks Prestasi)
        $totalSks = 0;
        $totalBobotNilaiKaliSks = 0; // Total (bobot huruf * sks)
        $ip = null;

        // Konversi nilai huruf ke bobot
        $bobot = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'E' => 0];
        
        foreach ($nilaiData as $item) {
            if ($item['status_penilaian'] === 'sudah_dinilai' && !is_null($item['nilai_huruf']) && isset($bobot[$item['nilai_huruf']])) {
                $totalSks += $item['sks'];
                $totalBobotNilaiKaliSks += ($bobot[$item['nilai_huruf']] * $item['sks']);
            }
        }
        
        if ($totalSks > 0) {
            $ip = round($totalBobotNilaiKaliSks / $totalSks, 2);
        }
        
        return response()->json([
            'nilai' => $nilaiData->values(), // Menggunakan values() untuk reset keys array
            'ip_semester' => $ip, // Ini IP berdasarkan matkul yang diambil dan dinilai di FRS ini
            'total_sks_dinilai' => $totalSks,
            'message' => 'Data nilai berhasil diambil'
        ]);
    }
}
