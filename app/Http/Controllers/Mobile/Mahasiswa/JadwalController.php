<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Get schedule (jadwal) for mahasiswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwal(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Get approved FRS entries with matakuliah details
        $jadwalByHari = FRS::with([ // Variabel diubah menjadi $jadwalByHari
                'matakuliah', 
                'matakuliah.dosen.user', // Untuk nama dosen
                'matakuliah.ruang',      // Untuk nama ruang
                'matakuliah.kelas'       // Untuk nama kelas matakuliah (jika berbeda dari kelas mahasiswa)
            ])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('status', 'disetujui') // Hanya FRS yang disetujui yang masuk jadwal
            ->get()
            ->map(function ($frs) {
                // Hanya mengembalikan objek matakuliah yang sudah di-load relasinya
                if ($frs->matakuliah) {
                    return [
                        'id_mk' => $frs->matakuliah->id_mk,
                        'kode_mk' => $frs->matakuliah->kode_mk,
                        'nama_mk' => $frs->matakuliah->nama_mk,
                        'sks' => $frs->matakuliah->sks,
                        'semester' => $frs->matakuliah->semester,
                        'hari' => $frs->matakuliah->hari,
                        'jam_mulai' => $frs->matakuliah->jam_mulai ? Carbon::parse($frs->matakuliah->jam_mulai)->format('H:i') : null,
                        'jam_selesai' => $frs->matakuliah->jam_selesai ? Carbon::parse($frs->matakuliah->jam_selesai)->format('H:i') : null,
                        'dosen_pengampu' => $frs->matakuliah->dosen && $frs->matakuliah->dosen->user 
                                            ? $frs->matakuliah->dosen->user->name 
                                            : 'N/A',
                        'ruang' => $frs->matakuliah->ruang ? $frs->matakuliah->ruang->nama_ruang : 'N/A',
                        'kelas_matakuliah' => $frs->matakuliah->kelas ? $frs->matakuliah->kelas->nama_kelas : 'N/A',
                    ];
                }
                return null;
            })
            ->filter() // Menghapus item null jika ada matakuliah yang tidak ter-load
            ->sortBy(function ($item) { // Mengurutkan berdasarkan hari dan jam mulai
                // Membuat nilai yang bisa diurutkan untuk hari
                $hariOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
                return ($hariOrder[$item['hari']] ?? 8) . $item['jam_mulai'];
            })
            ->groupBy('hari'); // Mengelompokkan berdasarkan hari
            
        return response()->json([
            'jadwal' => $jadwalByHari, // Sekarang variabel ini sudah terdefinisi dengan benar
            'message' => 'Jadwal berhasil diambil'
        ]);
    }
}
