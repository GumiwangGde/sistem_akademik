<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMatakuliah extends Model
{
    use HasFactory;

    protected $table = 'master_matakuliah'; 
    protected $primaryKey = 'id_master_mk'; 

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester_default',
        'id_prodi',
        'deskripsi',
    ];

    protected $appends = ['sks_total'];

    public function getSksTotalAttribute()
    {
        return ($this->sks ?? 0);
    }


    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function jadwalKuliah() 
    {
        return $this->hasMany(Matakuliah::class, 'id_master_mk');
    }
}
