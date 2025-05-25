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
        'id_dosen',
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
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mk');
    }
}
