<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory; 

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'user_id',
        'id_kelas',
        'nrp',
        'nama',
        'id_prodi' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mahasiswa');
    }

    public function hitungIpkKumulatif()
    {
        $totalBobotKaliSks = 0;
        $totalSksDiambil = 0;
        $bobotNilaiHuruf = ['A' => 4.0, 'A-' => 3.75, 'B+' => 3.25, 'B' => 3.0, 'B-' => 2.75, 'C+' => 2.25, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0];

        $frsMahasiswaDenganNilai = $this->frs()
                                    ->where('status', 'disetujui')
                                    ->whereHas('nilai', function ($query) {
                                        $query->where('status_penilaian', 'sudah_dinilai');
                                    })
                                    ->with(['nilai', 'jadwalKuliah.masterMatakuliah'])
                                    ->get();

        if ($frsMahasiswaDenganNilai->isEmpty()) {
            return 0.00;
        }

        foreach ($frsMahasiswaDenganNilai as $frs) {
            if ($frs->nilai && $frs->jadwalKuliah && $frs->jadwalKuliah->masterMatakuliah) {
                $sks = $frs->jadwalKuliah->masterMatakuliah->sks_total ?? $frs->jadwalKuliah->masterMatakuliah->sks ?? 0;
                $nilaiHuruf = $frs->nilai->nilai_huruf;

                if ($sks > 0 && isset($bobotNilaiHuruf[$nilaiHuruf])) {
                    $totalSksDiambil += $sks;
                    $totalBobotKaliSks += ($bobotNilaiHuruf[$nilaiHuruf] * $sks);
                }
            }
        }

        if ($totalSksDiambil == 0) {
            return 0.00;
        }

        return round($totalBobotKaliSks / $totalSksDiambil, 2);
    }

    public function hitungBatasSks()
    {
        $ipk = $this->hitungIpkKumulatif();
        if ($ipk >= 3.00) {
            return 24;
        } elseif ($ipk >= 2.50) {
            return 22;
        } elseif ($ipk >= 2.00) {
            return 20;
        } else {
            return 18;
        }
    }
}
