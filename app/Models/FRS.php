<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FRS extends Model
{
    use HasFactory;

    protected $table = 'frs';
    protected $primaryKey = 'id_frs';

    protected $fillable = [
        'id_mahasiswa',
        'id_mk', 
        'id_tahun_ajaran', 
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function jadwalKuliah() 
    {
        return $this->belongsTo(Matakuliah::class, 'id_mk');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_frs');
    }
}
