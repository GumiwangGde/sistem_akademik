<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan jika belum ada
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory; // Disarankan untuk digunakan

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'user_id',
        'id_kelas',
        'nrp',
        'nama',
        'id_prodi' // Diubah dari 'prodi' (string) menjadi 'id_prodi' (foreign key)
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Pastikan foreign key 'user_id' benar
    }

    // Relasi dengan kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Relasi dengan prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // Relasi dengan FRS
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mahasiswa');
    }
}
