<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FRS extends Model
{
    use HasFactory;

    protected $table = 'frs';
    protected $primaryKey = 'id_frs';
    
    protected $fillable = [
        'id_mahasiswa',
        'id_jadwal_kuliah',
        'id_mata_kuliah',
        'semester',
        'tahun_ajaran',
        'approved',
    ];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    
    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal_kuliah', 'id_jadwal');
    }
    
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_mata_kuliah', 'id_mk');
    }
    
    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_frs', 'id_frs');
    }
}