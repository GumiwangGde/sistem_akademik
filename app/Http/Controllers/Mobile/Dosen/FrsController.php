<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\FRS; // Pastikan FRS di-import dengan benar
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
// use App\Models\User; // Tidak digunakan secara langsung di method ini
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
        Log::info('approveFRS - Data Request yang Diterima:', $request->all());

        $validatedData = $request->validate([
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => ['required', Rule::in(['disetujui', 'ditolak', 'pending'])], 
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

        $frs->status = $validatedData['status'];
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

        $statusTerformat = strtoupper($validatedData['status']);
        $message = "Status FRS berhasil diubah menjadi $statusTerformat.";
        if ($validatedData['status'] == 'pending') {
            $message = "Status FRS berhasil dikembalikan ke PENDING.";
        }

        return response()->json([
            'frs' => $frs,
            'message' => $message
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

        if (!$mahasiswa->kelas || $mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen || $mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) {
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

        $namaMahasiswaUntukPesan = $mahasiswa->user->name ?? $mahasiswa->nama ?? 'Mahasiswa';


        return response()->json([
            'frs_mahasiswa' => $allFrsMahasiswa,
            'mahasiswa_detail' => $mahasiswa,
            'message' => 'Semua data FRS untuk mahasiswa ' . $namaMahasiswaUntukPesan . ' pada tahun ajaran aktif berhasil diambil'
        ]);
    }

    public function editFrsByDosenWali(Request $request, FRS $frs)
    {
        Log::info('editFrsByDosenWali RAW REQUEST:', $request->all());
        $validatedData = $request->validate([
            'id_mk_jadwal_baru' => 'required|exists:matakuliah,id_mk', 
        ]);

        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen || !$dosen->is_dosen_wali) {
            return response()->json(['message' => 'Akses ditolak atau data dosen tidak ditemukan.'], 403);
        }

        $activeTahunAjaranId = $this->getActiveTahunAjaranId();
        if (!$activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif.'], 404);
        }

        $frs->load('mahasiswa.kelas');

        if ($frs->id_tahun_ajaran != $activeTahunAjaranId || 
            !$frs->mahasiswa || 
            !$frs->mahasiswa->kelas ||
            $frs->mahasiswa->kelas->id_dosen_wali != $dosen->id_dosen ||
            $frs->mahasiswa->kelas->id_tahun_ajaran != $activeTahunAjaranId) {
            return response()->json(['message' => 'Tidak berwenang untuk FRS ini atau FRS tidak valid.'], 403);
        }

        if (!in_array($frs->status, ['pending', 'ditolak'])) {
            return response()->json(['message' => 'Hanya FRS dengan status pending atau ditolak yang dapat diedit mata kuliahnya.'], 403);
        }

        $frs->id_jadwal_kuliah = $validatedData['id_mk_jadwal_baru']; 
        $frs->status = 'pending'; 
        $frs->catatan_wali = "Mata kuliah diubah oleh Dosen Wali."; 


        $frs->save();
        $frs->load([]);
        return response()->json([
            'frs' => $frs,
            'message' => 'FRS berhasil diedit dan status kembali ke pending.'
        ]);
    }
}