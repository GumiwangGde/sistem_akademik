<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Matakuliah; // Ini adalah model JadwalKuliah Anda
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FrsController extends Controller
{
    /**
     * Helper untuk mendapatkan Tahun Ajaran yang aktif.
     */
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('FrsController::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    /**
     * Memeriksa apakah periode FRS saat ini terbuka untuk Tahun Ajaran yang diberikan.
     */
    protected function isFrsPeriodOpen(TahunAjaran $tahunAjaran = null)
    {
        if (!$tahunAjaran) {
            Log::warning('isFrsPeriodOpen: $tahunAjaran adalah null.');
            return false;
        }

        if (empty($tahunAjaran->tanggal_mulai_frs) || empty($tahunAjaran->tanggal_selesai_frs)) {
            Log::warning('isFrsPeriodOpen: Tanggal FRS (tanggal_mulai_frs atau tanggal_selesai_frs) pada TahunAjaran KOSONG atau null.', [
                'id_tahun_ajaran' => $tahunAjaran->id,
                'nama_tahun_ajaran' => $tahunAjaran->nama_tahun_ajaran,
                'db_tanggal_mulai_frs_value' => $tahunAjaran->tanggal_mulai_frs,
                'db_tanggal_selesai_frs_value' => $tahunAjaran->tanggal_selesai_frs,
            ]);
            return false;
        }

        $now = Carbon::now(); // WIB jika app.timezone = Asia/Jakarta
        
        $mulaiFrsCarbon = null;
        $selesaiFrsCarbon = null;

        try {
            if ($tahunAjaran->tanggal_mulai_frs instanceof Carbon) {
                $mulaiFrsCarbon = $tahunAjaran->tanggal_mulai_frs->copy()->startOfDay();
            } else {
                $mulaiFrsCarbon = Carbon::parse($tahunAjaran->tanggal_mulai_frs)->startOfDay();
            }

            if ($tahunAjaran->tanggal_selesai_frs instanceof Carbon) {
                $selesaiFrsCarbon = $tahunAjaran->tanggal_selesai_frs->copy()->endOfDay();
            } else {
                $selesaiFrsCarbon = Carbon::parse($tahunAjaran->tanggal_selesai_frs)->endOfDay();
            }
            
            $isBetween = $now->between($mulaiFrsCarbon, $selesaiFrsCarbon);
            
            return $isBetween;

        } catch (\Exception $e) {
            Log::error('EXCEPTION saat parsing tanggal FRS di isFrsPeriodOpen: ' . $e->getMessage(), [
                'id_tahun_ajaran' => $tahunAjaran->id,
                'input_tanggal_mulai_frs' => $tahunAjaran->tanggal_mulai_frs,
                'input_tanggal_selesai_frs' => $tahunAjaran->tanggal_selesai_frs,
            ]);
            return false; 
        }
    }

    public function getAvailableMatakuliah(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::with('kelas.prodi')->where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }
        if (!$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas manapun'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif'], 404);
        }

        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json([
                'matakuliah' => [],
                'message' => 'Periode pengisian FRS untuk tahun ajaran (' . $activeTahunAjaran->nama_tahun_ajaran . ') ini sedang tidak aktif/dibuka.',
                'debug_info_controller' => [
                    'current_time_wib' => Carbon::now()->toDateTimeString(),
                    'ta_mulai_frs_val' => $activeTahunAjaran->tanggal_mulai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_mulai_frs->toDateString() : $activeTahunAjaran->tanggal_mulai_frs,
                    'ta_selesai_frs_val' => $activeTahunAjaran->tanggal_selesai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_selesai_frs->toDateString() : $activeTahunAjaran->tanggal_selesai_frs,
                ]
            ], 403);
        }

        $existingFrsMkIds = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->pluck('id_mk')
            ->toArray();

        $jadwalKuliahQuery = Matakuliah::with([
            'masterMatakuliah.prodi',
            'dosen.user',
            'ruang',
        ])
        ->where('id_tahun_ajaran', $activeTahunAjaran->id)
        ->where('kelas_id', $mahasiswa->id_kelas);

        $availableMatakuliah = $jadwalKuliahQuery->get()
            ->filter(function ($jadwal) use ($existingFrsMkIds) {
                return !in_array($jadwal->id_mk, $existingFrsMkIds);
            })
            ->map(function ($jadwal) {
                $masterMk = $jadwal->masterMatakuliah;
                $sks = 'N/A';
                if ($masterMk) {
                    $sks = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0);
                } elseif (isset($jadwal->sks)) { 
                    $sks = $jadwal->sks;
                }

                return [
                    'id_mk_jadwal' => $jadwal->id_mk,
                    'kode_mk' => $masterMk->kode_mk ?? $jadwal->kode_mk ?? 'N/A',
                    'nama_mk' => $masterMk->nama_mk ?? $jadwal->nama_mk ?? 'N/A',
                    'sks' => $sks,
                    'semester_default' => $masterMk->semester_default ?? 'N/A',
                    'semester_pelaksanaan' => $jadwal->semester ?? 'N/A',
                    'dosen_pengampu' => $jadwal->dosen && $jadwal->dosen->user ? $jadwal->dosen->user->name : 'N/A',
                    'prodi_mk' => $masterMk && $masterMk->prodi ? $masterMk->prodi->nama_prodi : 'N/A',
                    'ruang' => $jadwal->ruang ? $jadwal->ruang->nama_ruang : 'N/A',
                    'hari' => $jadwal->hari,
                    'jam_mulai' => $jadwal->jam_mulai ? Carbon::parse($jadwal->jam_mulai)->format('H:i') : 'N/A',
                    'jam_selesai' => $jadwal->jam_selesai ? Carbon::parse($jadwal->jam_selesai)->format('H:i') : 'N/A',
                ];
            });

        return response()->json([
            'matakuliah' => $availableMatakuliah->values(),
            'message' => 'Daftar mata kuliah (jadwal) tersedia berhasil diambil'
        ]);
    }
    
    public function getMyFRS(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk menampilkan FRS saat ini'], 404);
        }

        $frsEntries = FRS::with([
            'jadwalKuliah' => function ($query) {
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user',
                    'ruang',
                    'kelas'
                ]);
            },
            'tahunAjaran',
            'nilai'
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($frs) {
            $matakuliahJadwal = $frs->jadwalKuliah;
            $masterMk = $matakuliahJadwal ? $matakuliahJadwal->masterMatakuliah : null;
            $sks = 'N/A';
            if ($masterMk) {
                $sks = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0);
            } elseif ($matakuliahJadwal && isset($matakuliahJadwal->sks)) {
                $sks = $matakuliahJadwal->sks;
            }

            return [
                'id_frs' => $frs->id_frs,
                'status_frs' => $frs->status,
                'catatan_wali' => $frs->catatan_wali ?? null,
                'id_mk_jadwal' => $matakuliahJadwal->id_mk ?? null,
                'kode_mk' => $masterMk->kode_mk ?? $matakuliahJadwal->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk->nama_mk ?? $matakuliahJadwal->nama_mk ?? 'N/A',
                'sks' => $sks,
                'semester_pelaksanaan' => $matakuliahJadwal->semester ?? 'N/A',
                'dosen_pengampu' => $matakuliahJadwal && $matakuliahJadwal->dosen && $matakuliahJadwal->dosen->user
                                    ? $matakuliahJadwal->dosen->user->name
                                    : 'N/A',
                'ruang' => $matakuliahJadwal && $matakuliahJadwal->ruang ? $matakuliahJadwal->ruang->nama_ruang : 'N/A',
                'hari' => $matakuliahJadwal->hari ?? 'N/A',
                'jam_mulai' => $matakuliahJadwal && $matakuliahJadwal->jam_mulai ? Carbon::parse($matakuliahJadwal->jam_mulai)->format('H:i') : 'N/A',
                'jam_selesai' => $matakuliahJadwal && $matakuliahJadwal->jam_selesai ? Carbon::parse($matakuliahJadwal->jam_selesai)->format('H:i') : 'N/A',
                'tahun_ajaran_frs' => $frs->tahunAjaran->nama_tahun_ajaran ?? 'N/A',
                'nilai_akhir' => $frs->nilai->nilai_huruf ?? null,
                'status_penilaian' => $frs->nilai->status_penilaian ?? 'belum_dinilai',
            ];
        });

        return response()->json([
            'frs' => $frsEntries,
            'message' => 'Data FRS untuk tahun ajaran aktif berhasil diambil'
        ]);
    }

    /**
     * Membuat entri FRS baru.
     * Validasi memastikan 'id_mk_jadwal' dikirim dari client.
     */
    public function createFRS(Request $request)
    {
        $validatedData = $request->validate([
            'id_mk_jadwal' => 'required|exists:matakuliah,id_mk' // Validasi input dari client
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }
         if (!$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas manapun'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk membuat FRS'], 403);
        }

        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json(['message' => 'Periode pengisian FRS sedang tidak aktif/dibuka.'], 403);
        }

        $idMkRequest = $validatedData['id_mk_jadwal']; // Menggunakan nilai yang sudah divalidasi
        
        $matakuliahJadwal = Matakuliah::where('id_mk', $idMkRequest)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->where('kelas_id', $mahasiswa->id_kelas)
            ->first();

        if (!$matakuliahJadwal) {
            return response()->json([
                'message' => 'Mata kuliah (jadwal) tidak valid atau tidak tersedia untuk kelas dan tahun ajaran Anda.'
            ], 422);
        }

        $existingFrs = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_mk', $idMkRequest) // id_mk di tabel FRS merujuk ke id_mk di tabel matakuliah (jadwal)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->first();

        if ($existingFrs) {
            return response()->json([
                'message' => 'Mata kuliah ini sudah ada dalam FRS Anda untuk tahun ajaran ini.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $frs = new FRS();
            $frs->id_mahasiswa = $mahasiswa->id_mahasiswa;
            $frs->id_mk = $idMkRequest; // Menyimpan id_mk dari jadwal kuliah yang dipilih
            $frs->id_tahun_ajaran = $activeTahunAjaran->id;
            $frs->status = 'pending'; // Status awal FRS
            $frs->save();

            Nilai::create([
                'id_frs' => $frs->id_frs,
                'status_penilaian' => 'belum_dinilai',
            ]);

            DB::commit();

            // Memuat relasi untuk respons
            $frs->load([
                'jadwalKuliah' => function ($query) {
                    $query->with(['masterMatakuliah.prodi', 'dosen.user', 'ruang', 'kelas']);
                },
                'tahunAjaran'
            ]);
            $matakuliahJadwalRes = $frs->jadwalKuliah;
            $masterMkRes = $matakuliahJadwalRes ? $matakuliahJadwalRes->masterMatakuliah : null;
            $sksRes = 'N/A';
            if ($masterMkRes) {
                $sksRes = ($masterMkRes->sks_teori ?? 0) + ($masterMkRes->sks_praktek ?? 0) + ($masterMkRes->sks_lapangan ?? 0);
            } elseif ($matakuliahJadwalRes && isset($matakuliahJadwalRes->sks)) {
                $sksRes = $matakuliahJadwalRes->sks;
            }

            return response()->json([
                'frs_item' => [
                    'id_frs' => $frs->id_frs,
                    'status_frs' => $frs->status,
                    'id_mk_jadwal' => $matakuliahJadwalRes->id_mk ?? null,
                    'kode_mk' => $masterMkRes->kode_mk ?? $matakuliahJadwalRes->kode_mk ?? 'N/A',
                    'nama_mk' => $masterMkRes->nama_mk ?? $matakuliahJadwalRes->nama_mk ?? 'N/A',
                    'sks' => $sksRes,
                    'semester_pelaksanaan' => $matakuliahJadwalRes->semester ?? 'N/A',
                    'dosen_pengampu' => $matakuliahJadwalRes && $matakuliahJadwalRes->dosen && $matakuliahJadwalRes->dosen->user
                                        ? $matakuliahJadwalRes->dosen->user->name
                                        : 'N/A',
                    'tahun_ajaran_frs' => $frs->tahunAjaran->nama_tahun_ajaran ?? 'N/A',
                ],
                'message' => 'FRS berhasil ditambahkan dan menunggu persetujuan.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal membuat FRS: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Gagal membuat FRS. Terjadi kesalahan internal.',
            ], 500);
        }
    }

    /**
     * Menghapus entri FRS.
     * Hanya FRS dengan status 'pending' yang dapat dihapus.
     */
    public function deleteFRS(Request $request, $id_frs_param)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk memproses penghapusan FRS'], 403);
        }

        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json(['message' => 'Periode FRS sedang tidak aktif/dibuka. Tidak dapat menghapus FRS.'], 403);
        }

        DB::beginTransaction();
        try {
            $frs = FRS::where('id_frs', $id_frs_param)
                ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_tahun_ajaran', $activeTahunAjaran->id)
                ->first();

            if (!$frs) {
                DB::rollBack(); // Rollback jika FRS tidak ditemukan sebelum melanjutkan
                return response()->json([
                    'message' => 'Data FRS tidak ditemukan atau Anda tidak berhak menghapusnya.'
                ], 404);
            }

            // Validasi status FRS harus 'pending' untuk bisa dihapus
            if ($frs->status !== 'pending') {
                DB::rollBack(); // Rollback jika status tidak sesuai
                return response()->json([
                    'message' => 'Hanya FRS dengan status "pending" yang dapat dihapus.'
                ], 422); // 422 Unprocessable Entity
            }

            // Hapus record nilai terkait terlebih dahulu
            Nilai::where('id_frs', $frs->id_frs)->delete();
            // Kemudian hapus FRS
            $frs->delete();

            DB::commit();

            return response()->json(['message' => 'FRS berhasil dihapus.']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal menghapus FRS: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Gagal menghapus FRS. Terjadi kesalahan internal.',
            ], 500);
        }
    }

    /**
     * Endpoint debug untuk memeriksa periode FRS secara manual (opsional).
     */
    public function debugFrsPeriod()
    {
        $activeTahunAjaran = $this->getActiveTahunAjaran();
        
        if (!$activeTahunAjaran) {
            return response()->json([
                'debug_message' => 'Tidak ada Tahun Ajaran aktif yang ditemukan oleh getActiveTahunAjaran()',
                'is_period_open_result' => false
            ]);
        }

        $isOpen = $this->isFrsPeriodOpen($activeTahunAjaran);
        
        return response()->json([
            'tahun_ajaran_aktif_info' => [
                'id' => $activeTahunAjaran->id,
                'nama' => $activeTahunAjaran->nama_tahun_ajaran,
                'status' => $activeTahunAjaran->status,
                'tanggal_mulai_frs_db' => $activeTahunAjaran->tanggal_mulai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_mulai_frs->toDateString() : $activeTahunAjaran->tanggal_mulai_frs,
                'tanggal_selesai_frs_db' => $activeTahunAjaran->tanggal_selesai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_selesai_frs->toDateString() : $activeTahunAjaran->tanggal_selesai_frs,
            ],
            'waktu_server_sekarang_wib' => Carbon::now()->toDateTimeString(),
            'status_periode_frs_terbuka' => $isOpen,
            'info_tambahan' => 'Silakan periksa file log (storage/logs/laravel.log) untuk detail "PENGECEKAN PERIODE FRS DETAIL (WIB) FINAL" jika status periode terbuka masih salah.'
        ]);
    }

}
    