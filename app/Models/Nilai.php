<?php
// app/Models/Nilai.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nilai';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_nilai';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_frs',
        'nilai_angka',
        'nilai_huruf',
        'status_penilaian',
    ];

    /**
     * Get the FRS that owns the nilai.
     */
    public function frs()
    {
        return $this->belongsTo(FRS::class, 'id_frs');
    }
}