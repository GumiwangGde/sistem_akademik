<?php

namespace App\Http\Controllers\Mobile\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Matakuliah; // This is your JadwalKuliah model
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Kelas; // For Mahasiswa's class details
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // For date comparisons

class FrsController extends Controller
{
    /**
     * Helper to get the active Tahun Ajaran.
     * @return \App\Models\TahunAjaran|null
     */
    protected function getActiveTahunAjaran()
    {
        return TahunAjaran::where('status', 'aktif')->first();
    }

    /**
     * Check if FRS period is currently open for the given Tahun Ajaran.
     * @param \App\Models\TahunAjaran $tahunAjaran
     * @return bool
     */
    protected function isFrsPeriodOpen(TahunAjaran $tahunAjaran = null)
    {
        if (!$tahunAjaran) {
            return false;
        }
        $now = Carbon::now();
        // Assuming column names are 'tgl_mulai_frs' and 'tgl_selesai_frs' in tahun_ajaran table
        // The document mentions "tanggal-tanggal penting (mulai/selesai kuliah, FRS)"
        // And "Admin mengatur tanggal-tanggal penting (mulai/selesai kuliah, periode FRS)."
        // And "SOP: Admin ... Status Awal: 'direncanakan' ..., tanggal-tanggal penting"
        // And "SOP Mahasiswa: Pastikan periode pengisian FRS ... sedang dibuka"
        $mulaiFrs = Carbon::parse($tahunAjaran->tgl_mulai_frs); // Or an appropriate field name
        $selesaiFrs = Carbon::parse($tahunAjaran->tgl_selesai_frs)->endOfDay(); // Or an appropriate field name

        return $now->between($mulaiFrs, $selesaiFrs);
    }

    /**
     * Get available matakuliah (jadwal kuliah) for FRS for the active tahun ajaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableMatakuliah(Request $request)
    {
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
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif'], 404);
        }

        // Check if FRS period is open
        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json([
                'matakuliah' => [],
                'message' => 'Periode pengisian FRS untuk tahun ajaran ini sedang tidak aktif/dibuka.'
            ], 403); // Forbidden
        }

        // Get existing FRS entries for the current student for the active tahun ajaran
        $existingFrsMkIds = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran)
            ->pluck('id_mk') // This id_mk refers to the PK of matakuliah (jadwal_kuliah) table
            ->toArray();

        // Get all matakuliah (jadwal kuliah) for mahasiswa's kelas and active tahun ajaran
        // According to SOP: "Sistem menampilkan daftar Jadwal Kuliah yang tersedia untuk diambil
        // (sesuai Prodi, Kelas, dan Tahun Ajaran mahasiswa)."
        // The 'matakuliah' model is your jadwal_kuliah.
        $jadwalKuliahQuery = Matakuliah::with([
            'masterMatakuliah.prodi', // For details like nama_mk, sks from master
            'dosenPengampu.user',     // For dosen name
            'ruang',                  // For ruang name
            // 'kelas' relation on Matakuliah (jadwal_kuliah) should already be there
        ])
        ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran)
        ->where('id_kelas', $mahasiswa->id_kelas); // Filter by mahasiswa's class

        // Optionally, filter by semester if 'semester_pelaksanaan' is part of jadwal_kuliah
        // and relevant to the mahasiswa's current semester progression.
        // For now, we rely on 'id_kelas' which should implicitly handle semester placement.

        $availableMatakuliah = $jadwalKuliahQuery->get()
            ->filter(function ($jadwal) use ($existingFrsMkIds) {
                return !in_array($jadwal->id_mk, $existingFrsMkIds);
            })
            ->map(function ($jadwal) {
                return [
                    'id_mk_jadwal' => $jadwal->id_mk, // PK of the jadwal_kuliah entry
                    'kode_mk' => $jadwal->masterMatakuliah->kode_mk ?? $jadwal->kode_mk, // Prefer master, fallback to override
                    'nama_mk' => $jadwal->masterMatakuliah->nama_mk ?? $jadwal->nama_mk,
                    'sks' => $jadwal->masterMatakuliah->sks_teori + $jadwal->masterMatakuliah->sks_praktek + $jadwal->masterMatakuliah->sks_lapangan, // Assuming SKS structure in master_matakuliah
                    'semester_default' => $jadwal->masterMatakuliah->semester_default ?? 'N/A',
                    'semester_pelaksanaan' => $jadwal->semester_pelaksanaan ?? 'N/A', // If exists on jadwal_kuliah
                    'dosen_pengampu' => $jadwal->dosenPengampu && $jadwal->dosenPengampu->user ? $jadwal->dosenPengampu->user->name : 'N/A',
                    'prodi_mk' => $jadwal->masterMatakuliah->prodi->nama_prodi ?? 'N/A',
                    'ruang' => $jadwal->ruang ? $jadwal->ruang->nama_ruang : 'N/A', // Assuming 'nama_ruang' in 'ruang' table
                    'hari' => $jadwal->hari,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    // Add any other relevant fields from jadwal_kuliah or master_matakuliah
                ];
            });

        return response()->json([
            'matakuliah' => $availableMatakuliah->values(),
            'message' => 'Daftar mata kuliah (jadwal) tersedia berhasil diambil'
        ]);
    }

    /**
     * Get mahasiswa's FRS entries for the active tahun ajaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyFRS(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            // Still show FRS if no active TA, but indicate it might be from a past TA
            // Or return an error:
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk menampilkan FRS saat ini'], 404);
        }

        $frsEntries = FRS::with([
            'matakuliah' => function ($query) { // matakuliah is jadwal_kuliah
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosenPengampu.user',
                    'ruang',
                    'kelas' // Kelas dari jadwal kuliah
                ]);
            },
            'tahunAjaranRel', // Relasi ke tahun ajaran dari FRS
            'nilai' // Load nilai associated with FRS
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran) // Only FRS for active TA
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($frs) {
            $matakuliahJadwal = $frs->matakuliah;
            $masterMk = $matakuliahJadwal ? $matakuliahJadwal->masterMatakuliah : null;
            return [
                'id_frs' => $frs->id_frs,
                'status_frs' => $frs->status,
                'catatan_wali' => $frs->catatan_wali ?? null,
                'id_mk_jadwal' => $matakuliahJadwal->id_mk ?? null,
                'kode_mk' => $masterMk->kode_mk ?? $matakuliahJadwal->kode_mk ?? 'N/A',
                'nama_mk' => $masterMk->nama_mk ?? $matakuliahJadwal->nama_mk ?? 'N/A',
                'sks' => $masterMk ? ($masterMk->sks_teori + $masterMk->sks_praktek + $masterMk->sks_lapangan) : 'N/A',
                'dosen_pengampu' => $matakuliahJadwal && $matakuliahJadwal->dosenPengampu && $matakuliahJadwal->dosenPengampu->user
                                    ? $matakuliahJadwal->dosenPengampu->user->name
                                    : 'N/A',
                'ruang' => $matakuliahJadwal && $matakuliahJadwal->ruang ? $matakuliahJadwal->ruang->nama_ruang : 'N/A',
                'hari' => $matakuliahJadwal->hari ?? 'N/A',
                'jam_mulai' => $matakuliahJadwal->jam_mulai ?? 'N/A',
                'jam_selesai' => $matakuliahJadwal->jam_selesai ?? 'N/A',
                'tahun_ajaran_frs' => $frs->tahunAjaranRel->nama_tahun_ajaran ?? 'N/A', // e.g. 2023/2024 Ganjil
                'nilai_akhir' => $frs->nilai->nilai_huruf ?? null, // If nilai is loaded
                'status_penilaian' => $frs->nilai->status_penilaian ?? 'belum_dinilai',
            ];
        });

        return response()->json([
            'frs' => $frsEntries,
            'message' => 'Data FRS untuk tahun ajaran aktif berhasil diambil'
        ]);
    }

    /**
     * Create new FRS entry for the active tahun ajaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFRS(Request $request)
    {
        $validatedData = $request->validate([
            // id_mk_jadwal is the PK from 'matakuliah' (jadwal_kuliah) table
            'id_mk_jadwal' => 'required|exists:matakuliah,id_mk'
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

        // Check if FRS period is open
        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json(['message' => 'Periode pengisian FRS sedang tidak aktif/dibuka.'], 403);
        }

        $idMkRequest = $validatedData['id_mk_jadwal'];

        // Validate if matakuliah (jadwal) exists and is for the active TA and student's class
        $matakuliahJadwal = Matakuliah::where('id_mk', $idMkRequest)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran)
            ->where('id_kelas', $mahasiswa->id_kelas) // Ensure it's for the student's class
            ->first();

        if (!$matakuliahJadwal) {
            return response()->json([
                'message' => 'Mata kuliah (jadwal) tidak valid atau tidak tersedia untuk kelas dan tahun ajaran Anda.'
            ], 422);
        }

        // Check if FRS entry already exists for this matakuliah in the active TA
        $existingFrs = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_mk', $idMkRequest) // id_mk is FK to matakuliah (jadwal)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran)
            ->first();

        if ($existingFrs) {
            return response()->json([
                'message' => 'Mata kuliah ini sudah ada dalam FRS Anda untuk tahun ajaran ini.'
            ], 422);
        }

        // TODO: Add SKS limit validation if required
        // $totalSksDiambil = FRS::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        //     ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran)
        //     ->join('matakuliah', 'frs.id_mk', '=', 'matakuliah.id_mk')
        //     ->join('master_matakuliah', 'matakuliah.id_master_mk', '=', 'master_matakuliah.id_master_mk')
        //     ->sum(DB::raw('master_matakuliah.sks_teori + master_matakuliah.sks_praktek + master_matakuliah.sks_lapangan'));
        // $sksBaru = $matakuliahJadwal->masterMatakuliah->sks_total; // Assuming sks_total exists or calculate
        // if (($totalSksDiambil + $sksBaru) > $batasSksMahasiswa) {
        //     return response()->json(['message' => 'Melebihi batas SKS yang diperbolehkan.'], 422);
        // }


        DB::beginTransaction();
        try {
            $frs = new FRS();
            $frs->id_mahasiswa = $mahasiswa->id_mahasiswa;
            $frs->id_mk = $idMkRequest; // This is id_mk from matakuliah (jadwal) table
            $frs->id_tahun_ajaran = $activeTahunAjaran->id_tahun_ajaran;
            $frs->status = 'pending'; // Default status
            $frs->save();

            Nilai::create([
                'id_frs' => $frs->id_frs,
                'status_penilaian' => 'belum_dinilai',
                // nilai_angka, nilai_huruf, etc., will be null by default
            ]);

            DB::commit();

            // Load necessary details for the response
            $frs->load([
                'matakuliah' => function ($query) {
                    $query->with(['masterMatakuliah.prodi', 'dosenPengampu.user', 'ruang', 'kelas']);
                },
                'tahunAjaranRel'
            ]);
            $matakuliahJadwalRes = $frs->matakuliah;
            $masterMkRes = $matakuliahJadwalRes ? $matakuliahJadwalRes->masterMatakuliah : null;

            return response()->json([
                'frs_item' => [ // Returning the newly created FRS item in a consistent format
                    'id_frs' => $frs->id_frs,
                    'status_frs' => $frs->status,
                    'id_mk_jadwal' => $matakuliahJadwalRes->id_mk ?? null,
                    'kode_mk' => $masterMkRes->kode_mk ?? $matakuliahJadwalRes->kode_mk ?? 'N/A',
                    'nama_mk' => $masterMkRes->nama_mk ?? $matakuliahJadwalRes->nama_mk ?? 'N/A',
                     'sks' => $masterMkRes ? ($masterMkRes->sks_teori + $masterMkRes->sks_praktek + $masterMkRes->sks_lapangan) : 'N/A',
                    'dosen_pengampu' => $matakuliahJadwalRes && $matakuliahJadwalRes->dosenPengampu && $matakuliahJadwalRes->dosenPengampu->user
                                        ? $matakuliahJadwalRes->dosenPengampu->user->name
                                        : 'N/A',
                    'tahun_ajaran_frs' => $frs->tahunAjaranRel->nama_tahun_ajaran ?? 'N/A',
                ],
                'message' => 'FRS berhasil ditambahkan dan menunggu persetujuan.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Create FRS failed: ' . $e->getMessage()); // Log the error
            return response()->json([
                'message' => 'Gagal membuat FRS. Terjadi kesalahan internal.',
                // 'error' => $e->getMessage() // Avoid exposing detailed errors in production
            ], 500);
        }
    }

    /**
     * Delete FRS entry if status is pending and FRS period is open.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_frs_param
     * @return \Illuminate\Http\JsonResponse
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

        // Check if FRS period is open
        if (!$this->isFrsPeriodOpen($activeTahunAjaran)) {
            return response()->json(['message' => 'Periode FRS sedang tidak aktif/dibuka. Tidak dapat menghapus FRS.'], 403);
        }

        DB::beginTransaction();
        try {
            $frs = FRS::where('id_frs', $id_frs_param)
                ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_tahun_ajaran', $activeTahunAjaran->id_tahun_ajaran) // Ensure it's for active TA
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

            // Delete associated nilai record first if no cascade on delete
            Nilai::where('id_frs', $frs->id_frs)->delete();
            $frs->delete();

            DB::commit();

            return response()->json(['message' => 'FRS berhasil dihapus.']); // 200 OK default

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Delete FRS failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menghapus FRS. Terjadi kesalahan internal.',
            ], 500);
        }
    }
}