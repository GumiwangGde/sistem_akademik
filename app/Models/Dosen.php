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

    /**
     * Get the user record associated with the dosen.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the kelas where this dosen is a wali.
     * Assumes 'id_dosen_wali' is the foreign key in the 'kelas' table.
     */
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'id_dosen_wali', 'id_dosen');
    }

    /**
     * Get the jadwal kuliah (matakuliah) taught by this dosen.
     * Assumes 'id_dosen' is the foreign key in the 'matakuliah' table (which acts as jadwal_kuliah).
     */
    public function jadwalKuliah()
    {
        return $this->hasMany(Matakuliah::class, 'id_dosen', 'id_dosen');
    }
}
