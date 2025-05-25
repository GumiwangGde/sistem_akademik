<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah; // Model untuk Jadwal Kuliah
use App\Models\FRS;
use App\Models\Nilai;
use App\Models\TahunAjaran; // Diperlukan untuk konteks tahun ajaran aktif
use App\Models\Mahasiswa;   // Mungkin diperlukan untuk detail mahasiswa
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class NilaiController extends Controller
{
    /**
     * Helper untuk mendapatkan Tahun Ajaran yang aktif.
     * @return \App\Models\TahunAjaran|null
     */
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('NilaiController (Dosen)::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

    /**
     * Get mahasiswa for a specific matakuliah (jadwal kuliah)
     * for inputting nilai, considering the active academic year.
     *
     * @param Request $request
     * @param int $id_mk_jadwal ID dari tabel matakuliah (jadwal kuliah)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMahasiswaByMatakuliah(Request $request, $id_mk_jadwal)
    {
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }
        
        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif yang ditemukan'], 404);
        }

        // Verifikasi bahwa matakuliah (jadwal) ini memang diampu oleh dosen tersebut pada tahun ajaran aktif
        $matakuliah = Matakuliah::where('id_mk', $id_mk_jadwal) // id_mk adalah PK dari tabel matakuliah (jadwal)
            ->where('id_dosen', $dosen->id_dosen) // id_dosen adalah FK di tabel matakuliah (jadwal)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id) // Filter berdasarkan tahun ajaran aktif
            ->first();
            
        if (!$matakuliah) {
            return response()->json([
                'message' => 'Mata kuliah (jadwal) tidak ditemukan, bukan mata kuliah yang Anda ampu, atau bukan dari tahun ajaran aktif ini.'
            ], 404); // Atau 403
        }
        
        // Ambil mahasiswa yang FRS-nya disetujui untuk mata kuliah (jadwal) ini pada tahun ajaran aktif
        $mahasiswaData = FRS::with(['mahasiswa.user', 'nilai'])
            ->where('id_mk', $id_mk_jadwal) // id_mk di FRS merujuk ke id_mk di tabel matakuliah (jadwal)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id) // Pastikan FRS dari tahun ajaran aktif
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($frs) {
                if (!$frs->mahasiswa || !$frs->mahasiswa->user) {
                    Log::warning('Data FRS tidak lengkap (mahasiswa atau user tidak ada) untuk getMahasiswaByMatakuliah (Dosen)', ['id_frs' => $frs->id_frs]);
                    return null; // Abaikan jika data mahasiswa tidak lengkap
                }
                $nilaiRecord = $frs->nilai ?? new Nilai(); // Buat instance Nilai baru jika belum ada, untuk konsistensi struktur

                return [
                    'id_frs' => $frs->id_frs,
                    'id_mahasiswa' => $frs->mahasiswa->id_mahasiswa,
                    'nrp' => $frs->mahasiswa->nrp,
                    'nama_mahasiswa' => $frs->mahasiswa->nama, // Mengambil dari tabel mahasiswa
                    // 'nama_mahasiswa_user' => $frs->mahasiswa->user->name, // Alternatif jika nama utama di tabel user
                    'id_nilai' => $nilaiRecord->id_nilai, // Bisa null jika record nilai belum ada di DB
                    'nilai_angka' => $nilaiRecord->nilai_angka,
                    'nilai_huruf' => $nilaiRecord->nilai_huruf,
                    'status_penilaian' => $nilaiRecord->status_penilaian ?? 'belum_dinilai',
                ];
            })->filter()->values(); // Hapus item null dan reset keys
            
        return response()->json([
            'matakuliah_detail' => $matakuliah->load(['masterMatakuliah', 'ruang', 'kelas', 'dosen.user']), // Detail jadwal kuliah yang dipilih
            'mahasiswa_list' => $mahasiswaData,
            'message' => 'Data mahasiswa untuk input nilai berhasil diambil'
        ]);
    }
    
    /**
     * Input nilai for mahasiswa.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inputNilai(Request $request)
    {
        $validatedData = $request->validate([
            'id_frs' => 'required|exists:frs,id_frs', 
            'nilai_angka' => 'required|numeric|min:0|max:100',
            // 'nilai_huruf' => 'nullable|string|max:2' // Opsional jika dosen input huruf juga
        ]);
        
        $user = $request->user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return response()->json(['message' => 'Data dosen tidak ditemukan'], 404);
        }

        $activeTahunAjaran = $this->getActiveTahunAjaran();
        if (!$activeTahunAjaran) {
            return response()->json(['message' => 'Tidak ada tahun ajaran aktif untuk proses input nilai'], 404);
        }
        
        // Dapatkan FRS dan relasi jadwalKuliah (yang merujuk ke Matakuliah/Jadwal)
        $frs = FRS::with('jadwalKuliah') // Menggunakan relasi 'jadwalKuliah' dari FRS model
                  ->find($validatedData['id_frs']);

        if (!$frs) {
            return response()->json(['message' => 'Data FRS tidak ditemukan'], 404);
        }

        // Pastikan FRS ini dari tahun ajaran aktif
        if ($frs->id_tahun_ajaran != $activeTahunAjaran->id) {
            return response()->json([
                'message' => 'Tidak dapat menginput nilai untuk FRS dari tahun ajaran yang tidak aktif.'
            ], 403);
        }

        // PENTING: Cek apakah relasi jadwalKuliah berhasil dimuat (tidak null)
        if (!$frs->jadwalKuliah) {
            Log::error('Relasi jadwalKuliah tidak ditemukan untuk FRS ID: ' . $frs->id_frs . 
                       '. Periksa data id_mk di FRS (ID: ' . $frs->id_frs . ', id_mk: ' . $frs->id_mk . ') apakah valid dan merujuk ke jadwal yang ada.');
            return response()->json([
                'message' => 'Data mata kuliah (jadwal) terkait FRS ini tidak ditemukan. Tidak dapat memvalidasi dosen pengampu.'
            ], 404); // atau 500 jika ini dianggap kesalahan data server
        }

        // Verifikasi apakah dosen yang login adalah dosen pengampu mata kuliah (jadwal) ini
        // Menggunakan $frs->jadwalKuliah yang merupakan objek Matakuliah
        if ($frs->jadwalKuliah->id_dosen != $dosen->id_dosen) {
            return response()->json([
                'message' => 'Anda tidak berwenang mengisi nilai untuk mata kuliah (jadwal) ini.'
            ], 403);
        }

        // Temukan atau buat record Nilai baru untuk FRS ini
        // Record nilai seharusnya sudah dibuat saat FRS dibuat mahasiswa, jadi kita update.
        $nilai = Nilai::firstOrNew(['id_frs' => $frs->id_frs]);
        
        // Kalkulasi nilai_huruf berdasarkan nilai_angka
        // Sesuaikan skala penilaian ini jika perlu
        $nilaiHuruf = 'E'; // Default
        $nilaiAngka = (float) $validatedData['nilai_angka'];
        
        // Contoh skala (sesuaikan dengan kebijakan institusi Anda)
        if ($nilaiAngka >= 85) { $nilaiHuruf = 'A'; }
        elseif ($nilaiAngka >= 80) { $nilaiHuruf = 'A-'; }
        elseif ($nilaiAngka >= 75) { $nilaiHuruf = 'B+'; }
        elseif ($nilaiAngka >= 70) { $nilaiHuruf = 'B'; }
        elseif ($nilaiAngka >= 65) { $nilaiHuruf = 'B-'; }
        elseif ($nilaiAngka >= 60) { $nilaiHuruf = 'C+'; }
        elseif ($nilaiAngka >= 55) { $nilaiHuruf = 'C'; }
        elseif ($nilaiAngka >= 50) { $nilaiHuruf = 'D'; }
        // else $nilaiHuruf tetap 'E'
        
        $nilai->nilai_angka = $nilaiAngka;
        $nilai->nilai_huruf = $nilaiHuruf; // Diisi berdasarkan konversi
        $nilai->status_penilaian = 'sudah_dinilai';
        // $nilai->tanggal_penilaian = now(); // Opsional, jika ingin mencatat tanggal input/update nilai
        $nilai->save();
        
        // Muat relasi yang relevan untuk respons
        $nilai->load(['frs.mahasiswa.user', 'frs.jadwalKuliah.masterMatakuliah']);
        
        return response()->json([
            'nilai_diinput' => $nilai, 
            'message' => 'Nilai berhasil diinput/diperbarui'
        ]);
    }
}
