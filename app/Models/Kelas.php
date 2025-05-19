<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi Laravel
    protected $table = 'kelas';
    
    // Definisikan primary key yang benar
    protected $primaryKey = 'id_kelas';
    
    protected $fillable = [
        'nama_kelas',
        'status',
        'id_dosen_wali',
    ];

    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen_wali');
    }

    // Relasi dengan mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelas', 'id_kelas');
    }
}