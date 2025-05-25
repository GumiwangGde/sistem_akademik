<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Nilai; // Model Nilai
use App\Models\TahunAjaran; // Diperlukan untuk mendapatkan tahun ajaran aktif
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk logging jika diperlukan

class NilaiController extends Controller
{
    /**
     * Helper untuk mendapatkan Tahun Ajaran yang aktif.
     * @return \App\Models\TahunAjaran|null
     */
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('NilaiController::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    /**
     * Get nilai for mahasiswa for the active academic year.
     * Nilai diambil dari FRS yang berstatus 'disetujui' pada tahun ajaran aktif.
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

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json([
                'nilai_semester_aktif' => [], // Menggunakan key yang konsisten dengan respons sukses
                'ip_semester' => null,
                'total_sks_semester_dinilai' => 0,
                'tahun_ajaran_nilai' => null,
                'message' => 'Tidak ada tahun ajaran aktif saat ini untuk menampilkan nilai.'
            ], 404); 
        }
        
        // Mengambil entri FRS yang disetujui untuk mahasiswa pada tahun ajaran aktif
        // dan memuat relasi nilai serta detail jadwal kuliah (termasuk master matakuliah).
        $nilaiData = FRS::with([
            'nilai', // Relasi dari FRS ke Nilai
            // Menggunakan nama relasi 'jadwalKuliah' dari model FRS ke model Matakuliah (JadwalKuliah)
            'jadwalKuliah' => function ($query) { 
                $query->with([
                    'masterMatakuliah', // Dari JadwalKuliah ke MasterMatakuliah
                    // 'dosen.user', // Bisa ditambahkan jika ingin menampilkan dosen pengampu
                    // 'tahunAjaran' // Tahun ajaran dari jadwal kuliah, bisa untuk verifikasi
                ]);
            },
            // 'tahunAjaran' // Relasi dari FRS ke TahunAjaran (sudah difilter di query utama)
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id) // Filter FRS berdasarkan tahun ajaran aktif
        ->where('status', 'disetujui') // Hanya FRS yang sudah disetujui yang relevan untuk nilai
        ->get()
        ->map(function ($frs) use ($activeTahunAjaran) { // Pass $activeTahunAjaran jika diperlukan untuk fallback
            // Pastikan relasi nilai dan jadwalKuliah (beserta masterMatakuliah di dalamnya) ada
            if (!$frs->jadwalKuliah || !$frs->nilai) {
                Log::warning('Data FRS tidak lengkap untuk mapping nilai.', [
                    'id_frs' => $frs->id_frs, 
                    'has_jadwal_kuliah' => !is_null($frs->jadwalKuliah),
                    'has_nilai' => !is_null($frs->nilai)
                ]);
                return null; 
            }

            $jadwal = $frs->jadwalKuliah;
            $masterMk = $jadwal->masterMatakuliah; 
            
            $sks = 0; 
            if ($masterMk && isset($masterMk->sks_teori)) { 
                $sks = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0);
            } elseif (isset($jadwal->sks)) { 
                $sks = $jadwal->sks;
            }
            
            return [
                'id_frs' => $frs->id_frs,
                'id_mk_jadwal' => $jadwal->id_mk,
                'kode_mk' => $masterMk->kode_mk ?? $jadwal->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk->nama_mk ?? $jadwal->nama_mk ?? 'N/A',
                'sks' => (int) $sks, 
                'semester_mk' => $masterMk->semester_default ?? $jadwal->semester ?? 'N/A', 
                'nilai_angka' => $frs->nilai->nilai_angka,
                'nilai_huruf' => $frs->nilai->nilai_huruf,
                'status_penilaian' => $frs->nilai->status_penilaian,
                // 'tahun_ajaran_frs' => $frs->tahunAjaran ? $frs->tahunAjaran->nama_tahun_ajaran : $activeTahunAjaran->nama_tahun_ajaran, // Contoh jika ingin menampilkan
            ];
        })
        ->filter(); // Menghapus item null dari hasil map

        // Kalkulasi IP Semester untuk Tahun Ajaran Aktif
        $totalSksDiambilDanDinilai = 0;
        $totalBobotNilaiKaliSks = 0;
        $ipSemester = null;

        // Bobot nilai standar (sesuaikan jika skema penilaian berbeda)
        $bobotNilaiHuruf = ['A' => 4.0, 'A-' => 3.75, 'B+' => 3.25, 'B' => 3.0, 'B-' => 2.75, 'C+' => 2.25, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0];
        
        foreach ($nilaiData as $item) {
            // Pastikan item tidak null setelah filter dan semua field yang dibutuhkan ada
            if ($item && isset($item['status_penilaian']) && $item['status_penilaian'] === 'sudah_dinilai' && 
                !empty($item['nilai_huruf']) && isset($bobotNilaiHuruf[$item['nilai_huruf']]) &&
                isset($item['sks']) && $item['sks'] > 0) {
                    
                $totalSksDiambilDanDinilai += $item['sks'];
                $totalBobotNilaiKaliSks += ($bobotNilaiHuruf[$item['nilai_huruf']] * $item['sks']);
            }
        }
        
        if ($totalSksDiambilDanDinilai > 0) {
            $ipSemester = round($totalBobotNilaiKaliSks / $totalSksDiambilDanDinilai, 2);
        }
            
        return response()->json([
            'nilai_semester_aktif' => $nilaiData->values(), 
            'ip_semester' => $ipSemester,
            'total_sks_semester_dinilai' => $totalSksDiambilDanDinilai,
            'tahun_ajaran_nilai' => $activeTahunAjaran->nama_tahun_ajaran,
            'message' => 'Data nilai untuk tahun ajaran aktif berhasil diambil'
        ]);
    }
}
