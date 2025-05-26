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

class MahasiswaDashboardController extends Controller
{
    /**
     * Helper untuk mendapatkan Tahun Ajaran yang aktif.
     * @return \App\Models\TahunAjaran|null
     */
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('MahasiswaDashboardController::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    /**
     * Mendapatkan nama hari dalam Bahasa Indonesia berdasarkan objek Carbon.
     * @param \Carbon\Carbon $date
     * @return string
     */
    protected function getNamaHariIndonesia(Carbon $date)
    {
        // Mengatur locale ke Indonesia agar nama hari sesuai
        // Pastikan locale 'id_ID' atau 'id' terinstall di server Anda jika menggunakan setLocale
        // Alternatifnya, kita buat array mapping manual
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $hariMap[$date->format('l')] ?? $date->format('l'); // Fallback ke nama Inggris jika tidak ada di map
    }

    /**
     * Get schedule (jadwal) for mahasiswa for the current day and active academic year.
     * Jadwal diambil dari FRS yang berstatus 'disetujui'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwalHariIni(Request $request)
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
                'jadwal_hari_ini' => [],
                'message' => 'Tidak ada tahun ajaran aktif saat ini.'
            ], 404);
        }

        // Tentukan hari ini dalam Bahasa Indonesia (Senin, Selasa, dst.)
        // Carbon::now() akan menggunakan timezone dari config/app.php
        // Pastikan timezone di config/app.php sudah 'Asia/Jakarta'
        $hariIniCarbon = Carbon::now(); 
        $namaHariIni = $this->getNamaHariIndonesia($hariIniCarbon);

        $jadwalHariIni = FRS::with([
            'jadwalKuliah' => function ($queryJadwal) use ($namaHariIni) {
                $queryJadwal->with([ // Eager load relasi dari model Matakuliah (JadwalKuliah)
                    'masterMatakuliah.prodi', 
                    'dosen.user',             
                    'ruang',                  
                    'kelas',                  
                    // 'tahunAjaran' // Mungkin tidak perlu karena sudah difilter di query utama FRS
                ])
                ->where('hari', $namaHariIni); // Filter berdasarkan hari ini
            },
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id)
        ->where('status', 'disetujui')
        // Tambahkan whereHas untuk memastikan hanya FRS yang jadwalKuliah-nya sesuai dengan hari ini
        // Ini penting agar FRS yang jadwalKuliah-nya tidak ada atau tidak di hari ini tidak ikut termuat
        ->whereHas('jadwalKuliah', function ($queryJadwal) use ($namaHariIni) {
            $queryJadwal->where('hari', $namaHariIni);
        })
        ->get()
        ->map(function ($frs) {
            // Karena sudah difilter dengan whereHas, $frs->jadwalKuliah seharusnya tidak null
            // Namun, pengecekan tetap baik untuk keamanan
            if (!$frs->jadwalKuliah) {
                return null;
            }

            $jadwal = $frs->jadwalKuliah;
            $masterMk = $jadwal->masterMatakuliah;
            
            $sks = 'N/A';
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
                'semester' => $jadwal->semester ?? 'N/A',
                'hari' => $jadwal->hari, // Seharusnya sama dengan $namaHariIni
                'jam_mulai' => $jadwal->jam_mulai ? Carbon::parse($jadwal->jam_mulai)->format('H:i') : null,
                'jam_selesai' => $jadwal->jam_selesai ? Carbon::parse($jadwal->jam_selesai)->format('H:i') : null,
                'dosen_pengampu' => $jadwal->dosen && $jadwal->dosen->user 
                                    ? $jadwal->dosen->user->name 
                                    : ($jadwal->dosen ? $jadwal->dosen->nama : 'N/A'),
                'ruang' => $jadwal->ruang ? $jadwal->ruang->nama_ruang : 'N/A',
                'kelas_matakuliah' => $jadwal->kelas ? $jadwal->kelas->nama_kelas : 'N/A',
                'prodi_mk' => $masterMk && $masterMk->prodi ? $masterMk->prodi->nama_prodi : 'N/A',
            ];
        })
        ->filter() // Menghapus item null
        ->sortBy('jam_mulai') // Mengurutkan jadwal hari ini berdasarkan jam mulai
        ->values(); // Mereset keys array
            
        return response()->json([
            'jadwal_hari_ini' => $jadwalHariIni,
            'hari_ini' => $namaHariIni,
            'tanggal_sekarang' => $hariIniCarbon->translatedFormat('l, d F Y'), // Contoh: Senin, 26 Mei 2025
            'tahun_ajaran_aktif' => $activeTahunAjaran->nama_tahun_ajaran,
            'message' => 'Jadwal kuliah untuk hari ini berhasil diambil'
        ]);
    }
}
