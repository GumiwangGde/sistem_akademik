<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    protected $fillable = [
        'id_frs',
        'nilai_angka',
        'nilai_huruf',
        'status_penilaian',
    ];

    public function frs()
    {
        return $this->belongsTo(FRS::class, 'id_frs');
    }
}