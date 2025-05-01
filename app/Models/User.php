<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }
    
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
    
    public function admin()
    {
        return $this->hasOne(Admin::class, 'username', 'email');
    }
    
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'email', 'email');
    }
    
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'email', 'email');
    }
}