<?php

// app/Models/Kelas.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Tentukan kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama_kelas',
        'status',
        'id_dosen_wali',
    ];

    /**
     * Relasi: Kelas memiliki satu Dosen Wali
     */
    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen_wali');
    }

    // /**
    //  * Relasi: Kelas memiliki banyak Mahasiswa (Many-to-Many)
    //  */
    // public function mahasiswa()
    // {
    //     return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa', 'id_kelas', 'id_mahasiswa');
    // }
}

