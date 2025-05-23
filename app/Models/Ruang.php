<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Sebaiknya tambahkan HasFactory jika menggunakan factory
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruang extends Model
{
    // use HasFactory; // Aktifkan jika Anda menggunakan factory untuk model ini

    protected $table = 'ruang';
    protected $primaryKey = 'id'; // Ini adalah default, jadi baris ini opsional tapi tidak masalah
    protected $fillable = ['nama_ruang', 'kapasitas'];

    /**
     * Mendefinisikan bahwa satu Ruang bisa memiliki banyak Matakuliah.
     * Ini adalah relasi one-to-many dari sisi "one" (Ruang).
     * 'ruang_id' adalah foreign key di tabel 'matakuliah'.
     * 'id' adalah local key (primary key) di tabel 'ruang'.
     */
    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'ruang_id', 'id');
    }
}