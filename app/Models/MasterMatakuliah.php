<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMatakuliah extends Model
{
    use HasFactory;

    protected $table = 'master_matakuliah'; // Nama tabel di database
    protected $primaryKey = 'id_master_mk'; // Primary key tabel

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks_teori',
        'sks_praktek',
        'sks_lapangan',
        // 'sks_total' adalah virtual, tidak perlu di fillable
        'semester_default',
        'id_prodi',
        'deskripsi',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['sks_total'];

    /**
     * Accessor untuk sks_total.
     * Meskipun di database adalah generated column, kita bisa definisikan accessor
     * jika ingin memastikan nilainya selalu ada saat model di-serialize.
     * Jika kolom virtual di DB sudah bekerja dengan baik, ini mungkin tidak wajib.
     */
    public function getSksTotalAttribute()
    {
        return ($this->sks_teori ?? 0) + ($this->sks_praktek ?? 0) + ($this->sks_lapangan ?? 0);
    }


    // Relasi: Satu MasterMatakuliah dimiliki oleh satu Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // Relasi: Satu MasterMatakuliah bisa ada di banyak JadwalKuliah (Matakuliah)
    public function jadwalKuliah() // Menggunakan nama yang lebih deskriptif
    {
        return $this->hasMany(Matakuliah::class, 'id_master_mk');
    }
}
