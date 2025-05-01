<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kuliah';
    protected $primaryKey = 'id_jadwal';
    
    protected $fillable = [
        'id_mk',
    ];
    
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk', 'id_mk');
    }
    
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jadwal', 'id_jadwal');
    }
    
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_jadwal_kuliah', 'id_jadwal');
    }
}