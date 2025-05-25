<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran'; // Nama tabel di database
    protected $primaryKey = 'id'; // Primary key tabel

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

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai_perkuliahan' => 'date',
        'tanggal_selesai_perkuliahan' => 'date',
        'tanggal_mulai_frs' => 'date',
        'tanggal_selesai_frs' => 'date',
    ];

    // Relasi: Satu TahunAjaran bisa memiliki banyak Kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran');
    }

    // Relasi: Satu TahunAjaran bisa memiliki banyak Matakuliah (Jadwal Kuliah)
    public function jadwalKuliah() // Menggunakan nama yang lebih deskriptif
    {
        return $this->hasMany(Matakuliah::class, 'id_tahun_ajaran');
    }

    // Relasi: Satu TahunAjaran bisa memiliki banyak FRS
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_tahun_ajaran');
    }
}
