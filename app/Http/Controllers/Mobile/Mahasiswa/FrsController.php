<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FrsController extends Controller
{
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('FrsController (Mahasiswa)::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    protected function isFrsPeriodOpen(TahunAjaran $tahunAjaran = null)
    {
        if (!$tahunAjaran) {
            Log::warning('isFrsPeriodOpen (Mahasiswa): $tahunAjaran adalah null.');
            return false;
        }

        if (empty($tahunAjaran->tanggal_mulai_frs) || empty($tahunAjaran->tanggal_selesai_frs)) {
            Log::warning('isFrsPeriodOpen (Mahasiswa): Tanggal FRS (mulai atau selesai) pada TahunAjaran KOSONG.', [
                'id_tahun_ajaran' => $tahunAjaran->id,
                'db_tanggal_mulai_frs_value' => $tahunAjaran->tanggal_mulai_frs,
                'db_tanggal_selesai_frs_value' => $tahunAjaran->tanggal_selesai_frs,
            ]);
            return false;
        }

        $now = Carbon::now();
        
        try {
            $mulaiFrsCarbon = ($tahunAjaran->tanggal_mulai_frs instanceof Carbon)
                ? $tahunAjaran->tanggal_mulai_frs->copy()->startOfDay()
                : Carbon::parse($tahunAjaran->tanggal_mulai_frs)->startOfDay();

            $selesaiFrsCarbon = ($tahunAjaran->tanggal_selesai_frs instanceof Carbon)
                ? $tahunAjaran->tanggal_selesai_frs->copy()->endOfDay()
                : Carbon::parse($tahunAjaran->tanggal_selesai_frs)->endOfDay();
            
            return $now->between($mulaiFrsCarbon, $selesaiFrsCarbon);

        } catch (\Exception $e) {
            Log::error('EXCEPTION saat parsing tanggal FRS di isFrsPeriodOpen (Mahasiswa): ' . $e->getMessage(), [
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
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas manapun yang valid untuk mengambil FRS'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json([
                'matakuliah' => [],
                'message' => 'Periode pengisian FRS untuk tahun ajaran (' . $activeTahunAjaran->nama_tahun_ajaran . ') ini sedang tidak aktif/dibuka.',
                'debug_info_controller' => [
                    'current_time_server' => Carbon::now()->toDateTimeString(),
                    'ta_mulai_frs_val' => $activeTahunAjaran->tanggal_mulai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_mulai_frs->toDateString() : $activeTahunAjaran->tanggal_mulai_frs,
                    'ta_selesai_frs_val' => $activeTahunAjaran->tanggal_selesai_frs instanceof Carbon ? $activeTahunAjaran->tanggal_selesai_frs->toDateString() : $activeTahunAjaran->tanggal_selesai_frs,
                ]
            ], 403);
        }

        $existingFrsMkIds = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->pluck('id_mk') 
            ->toArray();

        $availableMatakuliah = Matakuliah::with([
            'masterMatakuliah.prodi',
            'dosen.user',
            'ruang',
        ])
        ->where('id_tahun_ajaran', $activeTahunAjaran->id)
        ->where('kelas_id', $mahasiswa->id_kelas) 
        ->get()
        ->filter(function ($jadwal) use ($existingFrsMkIds) {
            return !in_array($jadwal->id_mk, $existingFrsMkIds);
        })
        ->map(function ($jadwal) {
            $masterMk = $jadwal->masterMatakuliah;
            $sks = $masterMk?->sks_total ?? $masterMk?->sks ?? 0;

            return [
                'id_mk_jadwal' => $jadwal->id_mk,
                'kode_mk' => $masterMk?->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk?->nama_mk ?? 'N/A',
                'sks' => (int) $sks,
                'semester_default' => $masterMk?->semester_default ?? 'N/A',
                'semester_pelaksanaan' => $jadwal->semester ?? 'N/A',
                'dosen_pengampu' => $jadwal->dosen?->user?->name ?? $jadwal->dosen?->nama ?? 'N/A',
                'prodi_mk' => $masterMk?->prodi?->nama_prodi ?? 'N/A',
                'ruang' => $jadwal->ruang?->nama_ruang ?? 'N/A',
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jam_mulai ? Carbon::parse($jadwal->jam_mulai)->format('H:i') : null,
                'jam_selesai' => $jadwal->jam_selesai ? Carbon::parse($jadwal->jam_selesai)->format('H:i') : null,
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
            $masterMk = $matakuliahJadwal?->masterMatakuliah;
            $sks = $masterMk?->sks_total ?? $masterMk?->sks ?? 0;

            return [
                'id_frs' => $frs->id_frs,
                'status_frs' => $frs->status,
                'catatan_wali' => $frs->catatan_wali,
                'id_mk_jadwal' => $matakuliahJadwal?->id_mk,
                'kode_mk' => $masterMk?->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk?->nama_mk ?? 'N/A',
                'sks' => (int) $sks,
                'semester_pelaksanaan' => $matakuliahJadwal?->semester ?? 'N/A',
                'dosen_pengampu' => $matakuliahJadwal?->dosen?->user?->name ?? $matakuliahJadwal?->dosen?->nama ?? 'N/A',
                'ruang' => $matakuliahJadwal?->ruang?->nama_ruang ?? 'N/A',
                'hari' => $matakuliahJadwal?->hari ?? 'N/A',
                'jam_mulai' => $matakuliahJadwal?->jam_mulai ? Carbon::parse($matakuliahJadwal->jam_mulai)->format('H:i') : null,
                'jam_selesai' => $matakuliahJadwal?->jam_selesai ? Carbon::parse($matakuliahJadwal->jam_selesai)->format('H:i') : null,
                'tahun_ajaran_frs' => $frs->tahunAjaran?->nama_tahun_ajaran ?? 'N/A',
                'nilai_akhir' => $frs->nilai?->nilai_huruf,
                'status_penilaian' => $frs->nilai?->status_penilaian ?? 'belum_dinilai',
            ];
        });

        return response()->json([
            'frs' => $frsEntries,
            'message' => 'Data FRS untuk tahun ajaran aktif berhasil diambil'
        ]);
    }

    public function createFRS(Request $request)
    {
        $validatedData = $request->validate([
            'id_mk_jadwal' => 'required|exists:matakuliah,id_mk' 
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }
         if (!$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas manapun yang valid untuk mengambil FRS'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk membuat FRS'], 403);
        }

        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json(['message' => 'Periode pengisian FRS sedang tidak aktif/dibuka.'], 403);
        }

        $idMkRequest = $validatedData['id_mk_jadwal'];
        
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
            ->where('id_mk', $idMkRequest) 
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
            $frs->id_mk = $idMkRequest; 
            $frs->id_tahun_ajaran = $activeTahunAjaran->id;
            $frs->status = 'pending';
            $frs->save();

            Nilai::create([
                'id_frs' => $frs->id_frs,
                'status_penilaian' => 'belum_dinilai',
            ]);

            DB::commit();

            $frs->load([
                'jadwalKuliah' => function ($query) {
                    $query->with(['masterMatakuliah.prodi', 'dosen.user', 'ruang']);
                },
                'tahunAjaran'
            ]);

            $matakuliahJadwalRes = $frs->jadwalKuliah;
            $masterMkRes = $matakuliahJadwalRes?->masterMatakuliah;
            $sksRes = $masterMkRes?->sks_total ?? $masterMkRes?->sks ?? 0;

            return response()->json([
                'frs_item' => [ 
                    'id_frs' => $frs->id_frs,
                    'status_frs' => $frs->status,
                    'id_mk_jadwal' => $matakuliahJadwalRes?->id_mk,
                    'kode_mk' => $masterMkRes?->kode_mk ?? 'N/A',
                    'nama_mk' => $masterMkRes?->nama_mk ?? 'N/A',
                    'sks' => (int) $sksRes,
                    'semester_pelaksanaan' => $matakuliahJadwalRes?->semester ?? 'N/A',
                    'dosen_pengampu' => $matakuliahJadwalRes?->dosen?->user?->name ?? $matakuliahJadwalRes?->dosen?->nama ?? 'N/A',
                    'tahun_ajaran_frs' => $frs->tahunAjaran?->nama_tahun_ajaran ?? 'N/A',
                ],
                'message' => 'FRS berhasil ditambahkan dan menunggu persetujuan.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal membuat FRS (Mahasiswa): ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Gagal membuat FRS. Terjadi kesalahan internal.',
            ], 500);
        }
    }

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
                DB::rollBack();
                return response()->json([
                    'message' => 'Data FRS tidak ditemukan atau Anda tidak berhak menghapusnya.'
                ], 404);
            }

            if ($frs->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'message' => 'Hanya FRS dengan status "pending" yang dapat dihapus.'
                ], 422);
            }

            Nilai::where('id_frs', $frs->id_frs)->delete();
            $frs->delete();

            DB::commit();

            return response()->json(['message' => 'FRS berhasil dihapus.']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal menghapus FRS (Mahasiswa): ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Gagal menghapus FRS. Terjadi kesalahan internal.',
            ], 500);
        }
    }
}