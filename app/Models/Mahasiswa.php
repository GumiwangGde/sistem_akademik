<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory; 

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'user_id',
        'id_kelas',
        'nrp',
        'nama',
        'id_prodi' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mahasiswa');
    }
}
