<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    
    protected $fillable = [
        'nama_kelas',
        'status',
        'id_dosen_wali',
        'id_tahun_ajaran', // Kolom baru
        'id_prodi',        // Kolom baru
    ];

    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen_wali');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // Relasi dengan mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelas', 'id_kelas');
    }

    // Relasi dengan jadwal kuliah (matakuliah) yang diadakan di kelas ini
    public function jadwalKuliah()
    {
        return $this->hasMany(Matakuliah::class, 'kelas_id', 'id_kelas');
    }
}
