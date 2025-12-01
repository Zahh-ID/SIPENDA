<?php
namespace App\Models; // Namespace ini harus App\Models

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable // Class harus Student
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'nisn', 'nama_lengkap', 'jenjang_tujuan', 'sekolah_tujuan', 
        'jalur_pendaftaran', 'alamat', 'password_hash', 'status_seleksi', 'status_approval'
    ];

    protected $hidden = ['password_hash'];
    
    // Override default password column accessor untuk guard 'student'
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}