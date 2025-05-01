<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    
    protected $fillable = [
        'id_mahasiswa',
        'id_frs',
        'nilai_angka',
        'nilai_huruf',
    ];
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    
    public function frs()
    {
        return $this->belongsTo(FRS::class, 'id_frs', 'id_frs');
    }
    
    public function mataKuliah()
    {
        return $this->hasOne(MataKuliah::class, 'id_nilai', 'id_nilai');
    }
}
