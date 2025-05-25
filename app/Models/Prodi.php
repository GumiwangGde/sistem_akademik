<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi'; // Nama tabel di database
    protected $primaryKey = 'id_prodi'; // Primary key tabel

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
    ];

    // Relasi: Satu Prodi bisa memiliki banyak Mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi');
    }

    // Relasi: Satu Prodi bisa memiliki banyak Kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_prodi');
    }

    // Relasi: Satu Prodi bisa memiliki banyak MasterMatakuliah
    public function masterMatakuliah()
    {
        return $this->hasMany(MasterMatakuliah::class, 'id_prodi');
    }
}
