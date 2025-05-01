<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_mk';
    
    protected $fillable = [
        'id_dosen',
        'id_nilai',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jam_mulai',
        'jam_selesai',
        'hari',
        'ruang',
    ];
    
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
    
    public function jadwalKuliah()
    {
        return $this->hasOne(JadwalKuliah::class, 'id_mk', 'id_mk');
    }
    
    public function nilai()
    {
        return $this->belongsTo(Nilai::class, 'id_nilai', 'id_nilai');
    }
    
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mata_kuliah', 'id_mk');
    }
}