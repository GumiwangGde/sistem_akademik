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
        'nrp',
        'nama',
        'prodi',
        'email',
        'password',
        'role',
        'id_kelas',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
    
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}