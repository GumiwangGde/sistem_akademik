<?php

namespace App\Http\Controllers\Mobile\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Matakuliah; 
use App\Models\FRS;
use App\Models\Nilai;
use App\Models\TahunAjaran; 
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    protected function getActiveTahunAjaran()
    {
        $activeTA = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTA) {
            Log::warning('NilaiController (Dosen)::getActiveTahunAjaran - Tidak ada Tahun Ajaran aktif ditemukan.');
        }
        return $activeTA;
    }

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

        $matakuliah = Matakuliah::where('id_mk', $id_mk_jadwal)
            ->where('id_dosen', $dosen->id_dosen)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->first();
            
        if (!$matakuliah) {
            return response()->json([
                'message' => 'Mata kuliah (jadwal) tidak ditemukan, bukan mata kuliah yang Anda ampu, atau bukan dari tahun ajaran aktif ini.'
            ], 404); 
        }
        
        $mahasiswaData = FRS::with(['mahasiswa.user', 'nilai'])
            ->where('id_mk', $id_mk_jadwal)
            ->where('id_tahun_ajaran', $activeTahunAjaran->id)
            ->where('status', 'disetujui')
            ->get()
            ->map(function ($frs) {
                if (!$frs->mahasiswa || !$frs->mahasiswa->user) {
                    Log::warning('Data FRS tidak lengkap (mahasiswa atau user tidak ada) untuk getMahasiswaByMatakuliah (Dosen)', ['id_frs' => $frs->id_frs]);
                    return null; 
                }
                $nilaiRecord = $frs->nilai ?? new Nilai(); 

                return [
                    'id_frs' => $frs->id_frs,
                    'id_mahasiswa' => $frs->mahasiswa->id_mahasiswa,
                    'nrp' => $frs->mahasiswa->nrp,
                    'nama_mahasiswa' => $frs->mahasiswa->nama, 
                    'id_nilai' => $nilaiRecord->id_nilai, 
                    'nilai_angka' => $nilaiRecord->nilai_angka,
                    'nilai_huruf' => $nilaiRecord->nilai_huruf,
                    'status_penilaian' => $nilaiRecord->status_penilaian ?? 'belum_dinilai',
                ];
            })->filter()->values();
            
        return response()->json([
            'matakuliah_detail' => $matakuliah->load(['masterMatakuliah', 'ruang', 'kelas', 'dosen.user']), 
            'mahasiswa_list' => $mahasiswaData,
            'message' => 'Data mahasiswa untuk input nilai berhasil diambil'
        ]);
    }
    
    public function inputNilai(Request $request)
    {
        $validatedData = $request->validate([
            'id_frs' => 'required|exists:frs,id_frs', 
            'nilai_angka' => 'required|numeric|min:0|max:100',
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
        
        $frs = FRS::with('jadwalKuliah')
                  ->find($validatedData['id_frs']);

        if (!$frs) {
            return response()->json(['message' => 'Data FRS tidak ditemukan'], 404);
        }

        if ($frs->id_tahun_ajaran != $activeTahunAjaran->id) {
            return response()->json([
                'message' => 'Tidak dapat menginput nilai untuk FRS dari tahun ajaran yang tidak aktif.'
            ], 403);
        }

        if (!$frs->jadwalKuliah) {
            Log::error('Relasi jadwalKuliah tidak ditemukan untuk FRS ID: ' . $frs->id_frs . 
                       '. Periksa data id_mk di FRS (ID: ' . $frs->id_frs . ', id_mk: ' . $frs->id_mk . ') apakah valid dan merujuk ke jadwal yang ada.');
            return response()->json([
                'message' => 'Data mata kuliah (jadwal) terkait FRS ini tidak ditemukan. Tidak dapat memvalidasi dosen pengampu.'
            ], 404); 
        }

        if ($frs->jadwalKuliah->id_dosen != $dosen->id_dosen) {
            return response()->json([
                'message' => 'Anda tidak berwenang mengisi nilai untuk mata kuliah (jadwal) ini.'
            ], 403);
        }

        $nilai = Nilai::firstOrNew(['id_frs' => $frs->id_frs]);
        
        $nilaiHuruf = 'E'; 
        $nilaiAngka = (float) $validatedData['nilai_angka'];
        
        if ($nilaiAngka >= 85) { $nilaiHuruf = 'A'; }
        elseif ($nilaiAngka >= 80) { $nilaiHuruf = 'A-'; }
        elseif ($nilaiAngka >= 75) { $nilaiHuruf = 'B+'; }
        elseif ($nilaiAngka >= 70) { $nilaiHuruf = 'B'; }
        elseif ($nilaiAngka >= 65) { $nilaiHuruf = 'B-'; }
        elseif ($nilaiAngka >= 60) { $nilaiHuruf = 'C+'; }
        elseif ($nilaiAngka >= 55) { $nilaiHuruf = 'C'; }
        elseif ($nilaiAngka >= 50) { $nilaiHuruf = 'D'; }
        
        $nilai->nilai_angka = $nilaiAngka;
        $nilai->nilai_huruf = $nilaiHuruf; 
        $nilai->status_penilaian = 'sudah_dinilai';
        $nilai->save();
        
        $nilai->load(['frs.mahasiswa.user', 'frs.jadwalKuliah.masterMatakuliah']);
        
        return response()->json([
            'nilai_diinput' => $nilai, 
            'message' => 'Nilai berhasil diinput/diperbarui'
        ]);
    }
}
