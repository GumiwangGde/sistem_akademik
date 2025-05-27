<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Illuminate\Support\Collection; 

class JadwalController extends Controller
{
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('JadwalController::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    public function getJadwal(Request $request)
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
                'jadwal' => [],
                'message' => 'Tidak ada tahun ajaran aktif saat ini.'
            ], 404);
        }
        
        $jadwalByHari = FRS::with([
            'jadwalKuliah' => function ($query) { 
                $query->with([ 
                    'masterMatakuliah.prodi', 
                    'dosen.user',             
                    'ruang',                  
                    'kelas',                  
                    'tahunAjaran'             
                ]);
            },
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id) 
        ->where('status', 'disetujui')
        ->get()
        ->map(function ($frs) {
            if ($frs->jadwalKuliah) { 
                $jadwal = $frs->jadwalKuliah; 
                $masterMk = $jadwal->masterMatakuliah; 
            
                $sks = 'N/A';
                if ($masterMk && isset($masterMk->sks_teori)) {
                    $sks = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0);
                } elseif (isset($jadwal->sks)) { 
                    $sks = $jadwal->sks;
                }

                return [
                    'id_mk_jadwal' => $jadwal->id_mk, 
                    'kode_mk' => $masterMk->kode_mk ?? $jadwal->kode_mk ?? 'N/A',
                    'nama_mk' => $masterMk->nama_mk ?? $jadwal->nama_mk ?? 'N/A',
                    'sks' => (int) $sks,
                    'semester' => $jadwal->semester ?? 'N/A', 
                    'hari' => $jadwal->hari, 
                    'jam_mulai' => $jadwal->jam_mulai ? Carbon::parse($jadwal->jam_mulai)->format('H:i') : null,
                    'jam_selesai' => $jadwal->jam_selesai ? Carbon::parse($jadwal->jam_selesai)->format('H:i') : null,
                    'dosen_pengampu' => $jadwal->dosen && $jadwal->dosen->user 
                                        ? $jadwal->dosen->user->name 
                                        : ($jadwal->dosen ? $jadwal->dosen->nama : 'N/A'),
                    'ruang' => $jadwal->ruang ? $jadwal->ruang->nama_ruang : 'N/A',
                    'kelas_matakuliah' => $jadwal->kelas ? $jadwal->kelas->nama_kelas : 'N/A',
                    'prodi_mk' => $masterMk && $masterMk->prodi ? $masterMk->prodi->nama_prodi : 'N/A',
                ];
            }
            return null;
        })
        ->filter() 
        ->sortBy(function ($item) { 
            $hariOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7, null => 8];
            if (is_null($item) || !isset($item['hari'])) {
                return 8; 
            }
            return ($hariOrder[$item['hari']] ?? 8) . ($item['jam_mulai'] ?? '99:99');
        })
        ->groupBy('hari')
        ->map(function (Collection $itemsPerHari) { 
            return $itemsPerHari->sortBy('jam_mulai')->values();
        });
            
        return response()->json([
            'jadwal' => $jadwalByHari,
            'message' => 'Jadwal kuliah untuk tahun ajaran aktif berhasil diambil'
        ]);
    }
}
