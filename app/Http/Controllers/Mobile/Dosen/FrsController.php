<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\FRS;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FrsController extends Controller
{
    /**
     * Helper untuk mendapatkan ID Tahun Ajaran yang aktif.
     * Mengembalikan nilai dari primary key model TahunAjaran.
     * @return int|null
     */
    protected function getActiveTahunAjaranId()
    {
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        
        // Jika ditemukan tahun ajaran aktif, kembalikan ID-nya (sesuai primaryKey model)
        // Model TahunAjaran Anda mendeklarasikan protected $primaryKey = 'id';
        return $activeTahunAjaran ? $activeTahunAjaran->id : null;
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

        $activeTahunAjaranId = $this->getActiveTahunAjaranId(); // Sekarang ini akan mengembalikan ID yang benar
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        // Dapatkan ID kelas-kelas perwalian dosen untuk tahun ajaran aktif
        $kelasWaliIds = Kelas::where('id_dosen_wali', $dosen->id_dosen) 
                               ->where('id_tahun_ajaran', $activeTahunAjaranId) // FK ini harus merujuk ke 'id' dari tahun_ajaran
                               ->pluck('id_kelas')
                               ->toArray();

        if (empty($kelasWaliIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Anda tidak menjadi wali untuk kelas manapun pada tahun ajaran aktif ini.'
            ], 200);
        }

        // Dapatkan ID mahasiswa dari kelas-kelas perwalian tersebut
        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasWaliIds)
                                 ->pluck('id_mahasiswa')
                                 ->toArray();

        if (empty($mahasiswaIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Tidak ada mahasiswa di kelas perwalian Anda pada tahun ajaran aktif ini.'
            ], 200);
        }

        // Ambil FRS yang pending untuk mahasiswa tersebut pada tahun ajaran aktif
        // Pastikan relasi di model FRS dan Matakuliah sudah benar
        $pendingFrs = FRS::with([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'jadwalKuliah' => function ($query) { // Menggunakan nama relasi 'jadwalKuliah' dari model FRS
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', // Menggunakan nama relasi 'dosen' dari model Matakuliah (jadwal)
                    'kelas',
                    'tahunAjaran' // Relasi dari Matakuliah (jadwal) ke TahunAjaran
                ]);
            },
            'tahunAjaran' // Relasi dari FRS ke TahunAjaran
        ])
        ->whereIn('id_mahasiswa', $mahasiswaIds)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) // FK ini harus merujuk ke 'id' dari tahun_ajaran
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
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_wali' => 'nullable|string|max:255'
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

        $frs = FRS::with('mahasiswa.kelas')
                  ->find($validatedData['id_frs']);

        if (!$frs) {
            return response()->json(['message' => 'FRS tidak ditemukan'], 404);
        }

        if ($frs->id_tahun_ajaran != $activeTahunAjaranId) { // FK ini harus merujuk ke 'id' dari tahun_ajaran
            return response()->json(['message' => 'FRS ini bukan untuk tahun ajaran aktif'], 403);
        }

        $mahasiswa = $frs->mahasiswa;
        if (!$mahasiswa || !$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa atau data kelas mahasiswa terkait FRS tidak ditemukan'], 404);
        }

        if ($mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) { // FK id_tahun_ajaran di kelas juga harus merujuk ke 'id' dari tahun_ajaran
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda di tahun ajaran aktif.'
            ], 403);
        }

        $frs->status = $validatedData['status'];
        if (isset($validatedData['catatan_wali'])) {
            $frs->catatan_wali = $validatedData['catatan_wali'];
        }
        $frs->save();

        $frs->load([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'jadwalKuliah' => function ($query) { // Menggunakan nama relasi 'jadwalKuliah' dari model FRS
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', // Menggunakan nama relasi 'dosen' dari model Matakuliah (jadwal)
                    'kelas',
                    'tahunAjaran' // Relasi dari Matakuliah (jadwal) ke TahunAjaran
                ]);
            },
            'tahunAjaran' // Relasi dari FRS ke TahunAjaran
        ]);

        return response()->json([
            'frs' => $frs,
            'message' => "FRS berhasil " . ($validatedData['status'] == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }

    /**
     * Get all FRS (pending, disetujui, ditolak) for a specific mahasiswa
     * who is under the current Dosen Wali, for the active academic year.
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

        $mahasiswa = Mahasiswa::with(['user', 'prodi', 'kelas'])
                              ->find($id_mahasiswa_param);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        if (!$mahasiswa->kelas || $mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) { // FK id_tahun_ajaran di kelas juga harus merujuk ke 'id' dari tahun_ajaran
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk melihat FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda di tahun ajaran aktif.'
            ], 403);
        }

        $allFrsMahasiswa = FRS::with([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'jadwalKuliah' => function ($query) { // Menggunakan nama relasi 'jadwalKuliah' dari model FRS
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', // Menggunakan nama relasi 'dosen' dari model Matakuliah (jadwal)
                    'kelas',
                    'tahunAjaran' // Relasi dari Matakuliah (jadwal) ke TahunAjaran
                ]);
            },
            'tahunAjaran' // Relasi dari FRS ke TahunAjaran
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) // FK ini harus merujuk ke 'id' dari tahun_ajaran
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'frs_mahasiswa' => $allFrsMahasiswa,
            'mahasiswa_detail' => $mahasiswa,
            'message' => 'Semua data FRS untuk mahasiswa ' . $mahasiswa->nama . ' pada tahun ajaran aktif berhasil diambil'
        ]);
    }
}