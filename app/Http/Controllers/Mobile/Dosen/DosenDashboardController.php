<?php

namespace App\Http\Controllers\Mobile\Dosen; // Pastikan namespace sesuai dengan lokasi file

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    /**
     * Mendapatkan jadwal matakuliah dosen untuk hari ini.
     * Endpoint ini hanya untuk pengguna dengan role 'dosen'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwalHariIni(Request $request)
    {
        $user = Auth::user();

        // Sebenarnya middleware 'role:dosen' pada route sudah menangani ini,
        // namun validasi tambahan di controller bisa jadi lapisan keamanan ekstra.
        if (!$user->hasRole('dosen')) {
            return response()->json(['message' => 'Akses ditolak. Hanya untuk dosen.'], 403);
        }

        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan untuk user ini.'], 404);
        }

        // Set locale Carbon ke Indonesia untuk mendapatkan nama hari dalam Bahasa Indonesia
        Carbon::setLocale('id');
        // Mendapatkan nama hari ini (e.g., "Jumat", "Senin")
        // Asumsi kolom 'hari' di tabel 'matakuliah' menyimpan nama hari dalam Bahasa Indonesia
        // Contoh: "Senin", "Selasa", ..., "Minggu"
        $hariIni = Carbon::now()->translatedFormat('l');

        $jadwalHariIni = Matakuliah::with(['kelas', 'ruang']) // Eager load relasi yang dibutuhkan
            ->where('id_dosen', $dosen->id_dosen)
            ->where('hari', $hariIni) // Mencocokkan dengan nama hari dalam bahasa Indonesia
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Anda bisa memformat output matakuliah jika diperlukan
        $formattedJadwal = $jadwalHariIni->map(function ($item) {
            return [
                'id_mk' => $item->id_mk,
                'kode_mk' => $item->kode_mk,
                'nama_mk' => $item->nama_mk,
                'sks' => $item->sks,
                'semester' => $item->semester,
                'jam_mulai' => Carbon::parse($item->jam_mulai)->format('H:i'),
                'jam_selesai' => Carbon::parse($item->jam_selesai)->format('H:i'),
                'hari' => $item->hari,
                'kelas' => $item->kelas ? $item->kelas->nama_kelas : 'N/A', // Contoh menampilkan nama kelas
                'ruang' => $item->ruang ? $item->ruang->nama_ruang : 'N/A', // Contoh menampilkan nama ruang
            ];
        });

        return response()->json([
            'message' => 'Jadwal dosen untuk hari ini berhasil diambil.',
            'dosen' => [ // Opsional: kirim detail dosen
                'id_dosen' => $dosen->id_dosen,
                'nidn' => $dosen->nidn,
                'nama' => $user->name,
            ],
            'hari_ini' => $hariIni,
            'jadwal' => $formattedJadwal
        ]);
    }
}
