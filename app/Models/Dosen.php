<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    
    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'password',
        'role',
        'is_dosen_wali',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'id_dosen', 'id_dosen');
    }
    
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_dosen', 'id_dosen');
    }
}