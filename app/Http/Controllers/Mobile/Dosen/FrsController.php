<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran; // Baru: Untuk mendapatkan tahun ajaran aktif
use App\Models\User; // Untuk relasi user
use Illuminate\Support\Facades\Log; // Opsional, untuk debugging
use Illuminate\Validation\Rule; // Untuk validasi status

class FrsController extends Controller
{
    /**
     * Helper untuk mendapatkan ID Tahun Ajaran yang aktif.
     * @return int|null
     */
    protected function getActiveTahunAjaranId()
    {
        // Menggunakan 'id' sebagai PK untuk tahun_ajaran, sesuaikan jika berbeda
        // Dokumen Anda menyebutkan 'kode' tapi FK biasanya ke 'id'. Saya asumsikan PK adalah 'id'.
        // Jika PK-nya adalah 'id_tahun_ajaran', gunakan itu.
        // Untuk konsistensi, karena FRS memiliki 'id_tahun_ajaran', kita akan cari berdasarkan itu.
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        return $activeTahunAjaran ? $activeTahunAjaran->id_tahun_ajaran : null;
    }

    /**
     * Get pending FRS that need approval for Dosen Wali for the active academic year.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingFRS(Request $request)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat melihat FRS pending'], 403);
        }

        $activeTahunAjaranId = $this->getActiveTahunAjaranId();
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        // Dapatkan ID kelas-kelas perwalian dosen untuk tahun ajaran aktif
        // Menggunakan relasi 'kelasWali' jika sudah didefinisikan dengan benar di model Dosen
        // Atau query manual:
        $kelasWaliIds = Kelas::where('id_dosen_wali', $dosen->id_dosen) // PK dosen adalah id_dosen
                               ->where('id_tahun_ajaran', $activeTahunAjaranId) // Filter berdasarkan tahun ajaran aktif
                               ->pluck('id_kelas') // PK kelas adalah id_kelas
                               ->toArray();

        if (empty($kelasWaliIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Anda tidak menjadi wali untuk kelas manapun pada tahun ajaran aktif ini.'
            ], 200);
        }

        // Dapatkan ID mahasiswa dari kelas-kelas perwalian tersebut
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasWaliIds)
                                 // Mahasiswa juga idealnya terkait dengan tahun ajaran aktif,
                                 // namun keterkaitan utama adalah melalui kelasnya.
                                 ->pluck('id_mahasiswa') // PK mahasiswa adalah id_mahasiswa
                                 ->toArray();

        if (empty($mahasiswaIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Tidak ada mahasiswa di kelas perwalian Anda pada tahun ajaran aktif ini.'
            ], 200);
        }

        // Ambil FRS yang pending untuk mahasiswa tersebut pada tahun ajaran aktif
        $pendingFrs = FRS::with([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']); // Memuat relasi user, prodi, dan kelas dari mahasiswa
            },
            'matakuliah' => function ($query) { // matakuliah di FRS adalah jadwal_kuliah
                $query->with([
                    'masterMatakuliah.prodi', // Dari jadwal ke master MK, lalu ke prodi master MK
                    'dosenPengampu.user',     // Dosen pengampu jadwal kuliah & user terkait
                    'kelas',                  // Kelas tempat jadwal kuliah ini
                    'tahunAjaranRel'          // Relasi ke tahun ajaran dari jadwal kuliah (jika ada, atau bisa diambil dari FRS)
                                              // Sebaiknya konsisten. FRS sudah punya id_tahun_ajaran.
                ]);
            },
            'tahunAjaranRel' // Relasi dari FRS ke TahunAjaran
        ])
        ->whereIn('id_mahasiswa', $mahasiswaIds)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) // Filter FRS berdasarkan tahun ajaran aktif
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'pending_frs' => $pendingFrs,
            'message' => 'Daftar FRS pending untuk tahun ajaran aktif berhasil diambil'
        ]);
    }

    /**
     * Approve or reject an FRS item for Dosen Wali.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveFRS(Request $request)
    {
        $validatedData = $request->validate([
            'id_frs' => 'required|exists:frs,id_frs', // PK frs adalah id_frs
            'status' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_wali' => 'nullable|string|max:255' // Opsional: catatan dari dosen wali
        ]);

        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat memproses FRS'], 403);
        }

        $activeTahunAjaranId = $this->getActiveTahunAjaranId();
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        $frs = FRS::with('mahasiswa.kelas') // Eager load mahasiswa dan kelasnya
                  ->find($validatedData['id_frs']);

        // Seharusnya tidak null karena ada rule 'exists', tapi sebagai fallback
        if (!$frs) {
            return response()->json(['message' => 'FRS tidak ditemukan'], 404);
        }

        // Pastikan FRS yang diproses adalah untuk tahun ajaran aktif
        if ($frs->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json(['message' => 'FRS ini bukan untuk tahun ajaran aktif'], 403);
        }

        $mahasiswa = $frs->mahasiswa;
        if (!$mahasiswa || !$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa atau data kelas mahasiswa terkait FRS tidak ditemukan'], 404);
        }

        // Verifikasi apakah mahasiswa dari FRS ini adalah mahasiswa perwalian dosen
        // dan apakah kelas mahasiswa tersebut untuk tahun ajaran aktif
        if ($mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda di tahun ajaran aktif.'
            ], 403);
        }

        $frs->status = $validatedData['status'];
        if (isset($validatedData['catatan_wali'])) {
            $frs->catatan_wali = $validatedData['catatan_wali']; // Jika ada kolom catatan
        }
        // $frs->approved_by_dosen_wali_id = $dosen->id_dosen; // Opsional jika ingin mencatat siapa yang approve
        // $frs->approval_date = now(); // Opsional
        $frs->save();

        // Load relasi yang dibutuhkan oleh Flutter setelah update
        $frs->load([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'matakuliah' => function ($query) {
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosenPengampu.user',
                    'kelas',
                    'tahunAjaranRel'
                ]);
            },
            'tahunAjaranRel'
        ]);

        return response()->json([
            'frs' => $frs,
            'message' => "FRS berhasil " . ($validatedData['status'] == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }

    /**
     * Get all FRS (pending, disetujui, ditolak) for a specific mahasiswa
     * who is under the current Dosen Wali, for the active academic year.
     *
     * @param Request $request
     * @param int $id_mahasiswa_param (mengganti nama variabel agar tidak konflik dengan model)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFrsForMahasiswa(Request $request, $id_mahasiswa_param)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat melihat FRS mahasiswa'], 403);
        }

        $activeTahunAjaranId = $this->getActiveTahunAjaranId();
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        $mahasiswa = Mahasiswa::with(['user', 'prodi', 'kelas']) // Eager load relasi mahasiswa
                              ->find($id_mahasiswa_param); // PK mahasiswa adalah id_mahasiswa

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        // Verifikasi apakah mahasiswa ini adalah mahasiswa perwalian dosen yang sedang login
        // dan apakah kelas mahasiswa tersebut untuk tahun ajaran aktif
        if (!$mahasiswa->kelas || $mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk melihat FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda di tahun ajaran aktif.'
            ], 403);
        }

        // Ambil semua FRS untuk mahasiswa tersebut pada tahun ajaran aktif
        $allFrsMahasiswa = FRS::with([
            'mahasiswa' => function ($query) { // Meskipun sudah ada $mahasiswa, untuk konsistensi struktur FRS
                $query->with(['user', 'prodi', 'kelas']);
            },
            'matakuliah' => function ($query) {
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosenPengampu.user',
                    'kelas',
                    'tahunAjaranRel'
                ]);
            },
            'tahunAjaranRel'
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) // Filter FRS berdasarkan tahun ajaran aktif
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            // Key 'frs_items' jika Flutter mengharapkan itu, atau 'frs_list' dll.
            'frs_mahasiswa' => $allFrsMahasiswa,
            'mahasiswa_detail' => $mahasiswa, // Kirim juga detail mahasiswa jika diperlukan di UI
            'message' => 'Semua data FRS untuk mahasiswa ' . $mahasiswa->nama . ' pada tahun ajaran aktif berhasil diambil'
        ]);
    }
}