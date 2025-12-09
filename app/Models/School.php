<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn', 'nama_sekolah', 'jenjang', 'kuota', 'detail', 'kota_kab', 'kecamatan', 'link_administrasi'
    ];
}