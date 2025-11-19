<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; <-- HAPUS BARIS INI

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Hapus HasApiTokens dari sini

    protected $fillable = [
        'name',
        'email',
        'password',
        // 'username', <-- Hapus jika tabel users tidak memiliki kolom ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}