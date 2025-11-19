<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 10)->unique();
            $table->string('nama_lengkap');
            $table->string('jenjang_tujuan');
            $table->string('sekolah_tujuan');
            $table->string('jalur_pendaftaran');
            $table->text('alamat');
            $table->string('password_hash'); 
            
            // KOLOM STATUS SELEKSI (HANYA SATU KALI)
            $table->enum('status_seleksi', ['Pending', 'Diterima', 'Ditolak'])->default('Pending'); 
            
            // Kolom Baru untuk Approval Admin Dinas
            $table->enum('status_approval', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->date('jadwal_test')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};