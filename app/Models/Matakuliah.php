<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini
use Illuminate\Database\Eloquent\Model;
// use App\Models\Ruang; // Tidak perlu di-use jika namespace sama atau sudah di-import di atas
// use App\Models\Dosen; // Tidak perlu di-use jika namespace sama
// use App\Models\Kelas; // Tidak perlu di-use jika namespace sama

class Matakuliah extends Model
{
    use HasFactory; // Disarankan untuk digunakan

    protected $table = 'matakuliah';
    protected $primaryKey = 'id_mk';

    protected $fillable = [
        'id_dosen',
        'kelas_id',
        'ruang_id', // Pastikan 'ruang_id' ada di fillable jika Anda mengisinya melalui create() atau update() dengan mass assignment
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    /**
     * Mendefinisikan bahwa satu Matakuliah dimiliki oleh satu Ruang.
     * Relasi many-to-one.
     */
    public function ruang()
    {
        // Argumen kedua adalah foreign key di tabel 'matakuliah' (ruang_id)
        // Argumen ketiga adalah owner key (primary key) di tabel 'ruang' (id)
        return $this->belongsTo(Ruang::class, 'ruang_id', 'id');
    }

    /**
     * Relasi ke tabel dosen.
     * Satu Matakuliah diajar oleh satu Dosen.
     */
    public function dosen()
    {
        // Argumen kedua adalah foreign key di tabel 'matakuliah' (id_dosen)
        // Argumen ketiga adalah owner key (primary key) di tabel 'dosen' (id_dosen)
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    /**
     * Relasi ke tabel kelas.
     * Satu Matakuliah bisa jadi terkait dengan satu Kelas.
     */
    public function kelas()
    {
        // Argumen kedua adalah foreign key di tabel 'matakuliah' (kelas_id)
        // Argumen ketiga adalah owner key (primary key) di tabel 'kelas' (id_kelas)
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }
}
