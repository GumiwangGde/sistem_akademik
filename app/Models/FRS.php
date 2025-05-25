<?php
// app/Models/FRS.php

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
        'id_mk', // Ini merujuk ke tabel matakuliah (jadwal kuliah)
        'id_tahun_ajaran', // Kolom baru
        'status',
    ];

    /**
     * Get the mahasiswa that owns the FRS.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Get the matakuliah (jadwal kuliah) associated with the FRS.
     */
    public function jadwalKuliah() // Mengganti nama relasi agar lebih jelas
    {
        return $this->belongsTo(Matakuliah::class, 'id_mk');
    }

    /**
     * Get the tahun ajaran associated with the FRS.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    /**
     * Get the nilai record associated with the FRS.
     */
    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_frs');
    }
}
