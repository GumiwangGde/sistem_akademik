<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ini merepresentasikan tabel 'matakuliah' yang sekarang berfungsi sebagai 'Jadwal Kuliah'.
 */
class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah'; // Ini adalah tabel jadwal kuliah
    protected $primaryKey = 'id_mk';

    protected $fillable = [
        'id_dosen',
        'kelas_id',
        'ruang_id',
        'id_master_mk',     // Kolom baru, FK ke master_matakuliah
        'id_tahun_ajaran',  // Kolom baru, FK ke tahun_ajaran
        
        // Kolom-kolom ini mungkin menjadi redundan atau perlu dipertimbangkan ulang
        // karena informasinya bisa didapat dari master_matakuliah dan tahun_ajaran.
        // Namun, untuk fleksibilitas jadwal (misal, kode MK khusus untuk jadwal tertentu),
        // mungkin masih relevan.
        'kode_mk',          // Mungkin kode unik untuk jadwal ini, bukan kode master MK
        'nama_mk',          // Nama yang ditampilkan di jadwal, bisa berbeda dari master
        'sks',              // SKS yang berlaku untuk jadwal ini
        'semester',         // Semester penjadwalan (misal Ganjil/Genap, atau semester kurikulum)
        
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    /**
     * Relasi ke MasterMatakuliah.
     * Setiap jadwal kuliah (Matakuliah) merujuk ke satu MasterMatakuliah.
     */
    public function masterMatakuliah()
    {
        return $this->belongsTo(MasterMatakuliah::class, 'id_master_mk');
    }

    /**
     * Relasi ke TahunAjaran.
     * Setiap jadwal kuliah (Matakuliah) berlangsung pada satu TahunAjaran.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke FRS (Satu jadwal kuliah bisa diambil oleh banyak mahasiswa melalui FRS)
    public function frs()
    {
        return $this->hasMany(FRS::class, 'id_mk');
    }
}
