<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruang;
use App\Models\Dosen;
use App\Models\Kelas;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'id_mk';

    protected $fillable = [
        'id_dosen',
        'kelas_id',
        'ruang_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    // Relasi ke tabel dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relasi ke tabel kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}