<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'isi',
        'gambar_url',
        'target_role',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
            // Memastikan slug unik
            $originalSlug = $berita->slug;
            $count = 1;
            while (static::whereSlug($berita->slug)->where('id', '!=', $berita->id)->exists()) {
                $berita->slug = "{$originalSlug}-{$count}";
                $count++;
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul') && empty($berita->slug)) { // Hanya update slug jika judul berubah dan slug tidak diisi manual
                $berita->slug = Str::slug($berita->judul);
            }
            // Memastikan slug unik saat update jika slug diubah
            if ($berita->isDirty('slug')) {
                $originalSlug = $berita->slug;
                $count = 1;
                while (static::whereSlug($berita->slug)->where('id', '!=', $berita->id)->exists()) {
                    $berita->slug = "{$originalSlug}-{$count}";
                    $count++;
                }
            }
        });
    }

    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit')
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    public function scopeUntukDosen($query)
    {
        return $query->whereIn('target_role', ['dosen', 'semua']);
    }

    public function scopeUntukMahasiswa($query)
    {
        return $query->whereIn('target_role', ['mahasiswa', 'semua']);
    }
}
