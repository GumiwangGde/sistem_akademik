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
    protected function getActiveTahunAjaranId()
    {
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        
        return $activeTahunAjaran ? $activeTahunAjaran->id : null;
    }

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

        $kelasWaliIds = Kelas::where('id_dosen_wali', $dosen->id_dosen) 
                               ->where('id_tahun_ajaran', $activeTahunAjaranId) 
                               ->pluck('id_kelas')
                               ->toArray();

        if (empty($kelasWaliIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Anda tidak menjadi wali untuk kelas manapun pada tahun ajaran aktif ini.'
            ], 200);
        }

        $mahasiswaIds = Mahasiswa::whereIn('id_kelas', $kelasWaliIds)
                                 ->pluck('id_mahasiswa')
                                 ->toArray();

        if (empty($mahasiswaIds)) {
            return response()->json([
                'pending_frs' => [],
                'message' => 'Tidak ada mahasiswa di kelas perwalian Anda pada tahun ajaran aktif ini.'
            ], 200);
        }

        $pendingFrs = FRS::with([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'jadwalKuliah' => function ($query) { 
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', 
                    'kelas',
                    'tahunAjaran' 
                ]);
            },
            'tahunAjaran'
        ])
        ->whereIn('id_mahasiswa', $mahasiswaIds)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) 
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'pending_frs' => $pendingFrs,
            'message' => 'Daftar FRS pending untuk tahun ajaran aktif berhasil diambil'
        ]);
    }

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
            'jadwalKuliah' => function ($query) { 
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', 
                    'kelas',
                    'tahunAjaran' 
                ]);
            },
            'tahunAjaran' 
        ]);

        return response()->json([
            'frs' => $frs,
            'message' => "FRS berhasil " . ($validatedData['status'] == 'disetujui' ? 'disetujui' : 'ditolak')
        ]);
    }

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
            'jadwalKuliah' => function ($query) { 
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user', 
                    'kelas',
                    'tahunAjaran' 
                ]);
            },
            'tahunAjaran' 
        ])
        ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
        ->where('id_tahun_ajaran', $activeTahunAjaranId) 
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'frs_mahasiswa' => $allFrsMahasiswa,
            'mahasiswa_detail' => $mahasiswa,
            'message' => 'Semua data FRS untuk mahasiswa ' . $mahasiswa->nama . ' pada tahun ajaran aktif berhasil diambil'
        ]);
    }

    public function editFrsByDosenWali(Request $request, FRS $frs)
    {
        $validatedData = $request->validate([
            // ID jadwal kuliah BARU yang akan menggantikan yang lama di FRS ini
            'id_mk_jadwal_baru' => 'required|exists:matakuliah,id_mk', // 'matakuliah' adalah nama tabel jadwal kuliah Anda
            'catatan_wali_edit' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        if (!$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Hanya dosen wali yang dapat mengedit FRS'], 403);
        }

        $activeTahunAjaranId = $this->getActiveTahunAjaranId();
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        // Muat relasi mahasiswa dan kelasnya untuk verifikasi
        $frs->load('mahasiswa.kelas');

        if ($frs->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json(['message' => 'FRS ini bukan untuk tahun ajaran aktif'], 403);
        }

        $mahasiswa = $frs->mahasiswa;
        if (!$mahasiswa || !$mahasiswa->kelas) {
            return response()->json(['message' => 'Mahasiswa atau data kelas mahasiswa terkait FRS tidak ditemukan'], 404);
        }

        if ($mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json([
                'message' => 'Anda tidak memiliki wewenang untuk FRS mahasiswa ini atau mahasiswa bukan bagian dari perwalian Anda di tahun ajaran aktif.'
            ], 403);
        }

        // Logika tambahan: Dosen Wali idealnya hanya bisa mengedit FRS yang pending atau ditolak
        if (!in_array($frs->status, ['pending', 'ditolak'])) {
            return response()->json(['message' => 'Hanya FRS dengan status pending atau ditolak yang dapat diedit oleh Dosen Wali.'], 403);
        }

        // Update FRS
        $frs->id_jadwal_kuliah = $validatedData['id_mk_jadwal_baru']; // Sesuaikan 'id_jadwal_kuliah' dengan nama kolom FK di tabel FRS Anda
        
        // Setelah diedit oleh dosen wali, status bisa direset ke 'pending' lagi
        // atau tetap, tergantung alur bisnis Anda. Misal kita reset ke pending.
        $frs->status = 'pending'; 
        $frs->catatan_wali = $validatedData['catatan_wali_edit'] ?? "Mata kuliah diubah oleh Dosen Wali.";
        
        $frs->save();

        // Muat kembali semua relasi untuk respons
        $frs->load([
            'mahasiswa' => function ($query) {
                $query->with(['user', 'prodi', 'kelas']);
            },
            'jadwalKuliah' => function ($query) {
                $query->with([
                    'masterMatakuliah.prodi',
                    'dosen.user',
                    'kelas',
                    'tahunAjaran'
                ]);
            },
            'tahunAjaran'
        ]);

        return response()->json([
            'frs' => $frs,
            'message' => 'FRS berhasil diedit oleh Dosen Wali dan menunggu persetujuan kembali.'
        ]);
    }
}