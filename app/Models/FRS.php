<?php
// app/Models/FRS.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FRS extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'frs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_frs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_mahasiswa',
        'id_mk',
        'status',
    ];

    /**
     * Get the mahasiswa that owns the FRS.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Get the matakuliah associated with the FRS.
     */
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_mk');
    }

    /**
     * Get the nilai record associated with the FRS.
     */
    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_frs');
    }
}