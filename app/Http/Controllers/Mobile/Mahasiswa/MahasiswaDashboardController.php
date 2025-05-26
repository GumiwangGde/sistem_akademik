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

    public function getJadwalHariIni(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json([
                'jadwal_hari_ini' => [],
                'message' => 'Tidak ada tahun ajaran aktif saat ini.'
            ], 404);
        }

        $hariIniCarbon = Carbon::now(); 
        $namaHariIni = $this->getNamaHariIndonesia($hariIniCarbon);

        $jadwalHariIni = FRS::with([
            'jadwalKuliah.masterMatakuliah.prodi',
            'jadwalKuliah.dosen.user',
            'jadwalKuliah.ruang',
            'jadwalKuliah.kelas',
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id)
        ->where('status', 'disetujui')
        ->whereHas('jadwalKuliah', function ($queryJadwal) use ($namaHariIni) {
            $queryJadwal->where('hari', $namaHariIni);
        })
        ->get()
        ->map(function ($frs) {
            if (!$frs->jadwalKuliah) {
                return null;
            }

            $jadwal = $frs->jadwalKuliah;
            $masterMk = $jadwal->masterMatakuliah;
            
            return [
                'id_frs' => $frs->id_frs,
                'id_mk_jadwal' => $jadwal->id_mk,
                'kode_mk' => $masterMk?->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk?->nama_mk ?? 'N/A',
                'sks' => (int) ($masterMk?->sks_total ?? 0),
                'semester' => $jadwal->semester ?? 'N/A',
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jam_mulai ? Carbon::parse($jadwal->jam_mulai)->format('H:i') : null,
                'jam_selesai' => $jadwal->jam_selesai ? Carbon::parse($jadwal->jam_selesai)->format('H:i') : null,
                'dosen_pengampu' => $jadwal->dosen?->user?->name ?? $jadwal->dosen?->nama ?? 'N/A', // <-- PERBAIKAN NULLSAFE
                'ruang' => $jadwal->ruang?->nama_ruang ?? 'N/A',                                     // <-- PERBAIKAN NULLSAFE
                'kelas_matakuliah' => $jadwal->kelas?->nama_kelas ?? 'N/A',                           // <-- PERBAIKAN NULLSAFE
                'prodi_mk' => $masterMk?->prodi?->nama_prodi ?? 'N/A',                               // <-- PERBAIKAN NULLSAFE
            ];
        })
        ->filter()
        ->sortBy('jam_mulai')
        ->values();
                
        return response()->json([
            'jadwal_hari_ini' => $jadwalHariIni,
            'hari_ini' => $namaHariIni,
            'tanggal_sekarang' => sprintf('%s, %s', $namaHariIni, $hariIniCarbon->format('d F Y')),
            'tahun_ajaran_aktif' => $activeTahunAjaran->nama_tahun_ajaran,
            'message' => 'Jadwal kuliah untuk hari ini berhasil diambil'
        ]);
    }
}
