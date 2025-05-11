<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';

    protected $fillable = [
        'user_id',
        'nidn',
        'is_dosen_wali',
    ];

    // Relasi ke tabel users
    public function user()
    {   
        return $this->belongsTo(User::class);
    }
}
