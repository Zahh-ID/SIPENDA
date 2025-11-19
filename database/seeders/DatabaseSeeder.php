<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Hapus trait WithoutModelEvents agar event model (jika ada) tetap berjalan.
        // 2. Kosongkan tabel sebelum seeding untuk menghindari data duplikat.
        User::truncate();
        School::truncate();
        Student::truncate();
        Operator::truncate();
        
        // 3. Buat 1 User Admin untuk login ke dasbor.
        // Pastikan tabel 'users' Anda memiliki kolom 'username'.
        User::create([
            'name' => 'Admin PPDB',
            'username' => 'admin',
            'email' => 'admin@ppdb.test',
            'password' => Hash::make('password'), // Login dengan password: "password"
        ]);
        
        // 4. Buat 150 data sekolah secara otomatis menggunakan factory.
        School::factory(50)->create();
        
        // Buat 10 data Operator Sekolah
        Operator::factory(10)->create();

        // 5. Buat 150 data siswa pendaftar secara otomatis menggunakan factory.
        // Tambahkan pengecekan untuk memastikan sekolah sudah ada sebelum membuat siswa.
        if (School::count() > 0) {
            Student::factory(200)->create();
        } else {
            // Tampilkan peringatan di konsol jika tidak ada sekolah yang bisa dijadikan tujuan.
            $this->command->warn('Tidak ada data sekolah. Seeding untuk siswa dilewati.');
        }
    }
}
