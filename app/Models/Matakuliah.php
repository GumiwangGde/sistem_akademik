<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $primaryKey = 'id_mk';

    protected $fillable = [
        'id_dosen', // Pastikan ini adalah foreign key ke tabel dosen untuk dosen pengampu
        'kelas_id',
        'ruang_id',
        'id_master_mk',
        'id_tahun_ajaran',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    public function masterMatakuliah()
    {
        return $this->belongsTo(MasterMatakuliah::class, 'id_master_mk');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id'); // 'id' adalah PK di tabel tahun_ajaran
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    // INI RELASI KE DOSEN PENGAMPU
    public function dosen() 
    {
        // Pastikan 'id_dosen' adalah nama kolom FK di tabel 'matakuliah'
        // dan 'id_dosen' adalah nama PK di tabel 'dosen' (jika berbeda, sesuaikan argumen ketiga)
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); 
    }

    public function kelas()
    {
        // Pastikan 'kelas_id' adalah nama kolom FK di tabel 'matakuliah'
        // dan 'id_kelas' adalah nama PK di tabel 'kelas' (jika berbeda, sesuaikan argumen ketiga)
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mk', 'id_mk'); // 'id_mk' di FRS merujuk ke 'id_mk' di matakuliah
    }
}