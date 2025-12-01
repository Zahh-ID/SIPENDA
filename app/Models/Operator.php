<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operator extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'username', 'nama_operator', 'sekolah_tujuan', 'password_hash',
    ];

    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    
    protected $guard = 'operator'; // Guard yang digunakan di AuthController
}