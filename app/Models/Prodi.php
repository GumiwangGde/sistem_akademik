<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi'; 
    protected $primaryKey = 'id_prodi'; 

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi');
    }

    public function kelas() 
    {
        return $this->hasMany(Kelas::class, 'id_prodi');
    }

    public function masterMatakuliah()
    {
        return $this->hasMany(MasterMatakuliah::class, 'id_prodi');
    }
}
