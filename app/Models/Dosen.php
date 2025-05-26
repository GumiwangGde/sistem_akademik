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
        'user_id',
        'nidn',
        'is_dosen_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'id_dosen_wali', 'id_dosen');
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(Matakuliah::class, 'id_dosen', 'id_dosen');
    }
}
