<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'id_mk';

    protected $fillable = [
        'id_dosen',
        'kelas_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jam_mulai',
        'jam_selesai',
        'hari',
        'ruang',
    ];

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