<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran'; 
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'kode_tahun_ajaran',
        'nama_tahun_ajaran',
        'semester',
        'tahun_mulai',
        'tahun_selesai',
        'status',
        'tanggal_mulai_perkuliahan',
        'tanggal_selesai_perkuliahan',
        'tanggal_mulai_frs',
        'tanggal_selesai_frs',
    ];

    protected $casts = [
        'tanggal_mulai_perkuliahan' => 'date',
        'tanggal_selesai_perkuliahan' => 'date',
        'tanggal_mulai_frs' => 'date',
        'tanggal_selesai_frs' => 'date',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran');
    }

    public function jadwalKuliah() 
    {
        return $this->hasMany(Matakuliah::class, 'id_tahun_ajaran');
    }

    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_tahun_ajaran');
    }
}
