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
        
        // 4. Buat Sekolah Spesifik untuk relasi
        School::create([
            'nama_sekolah' => 'SMAN 1 Surabaya',
            'jenjang' => 'SMA',
            'kuota' => 200, // Menggabungkan semua kuota ke satu kolom 'kuota'
            'kota_kab' => 'Surabaya',
            'detail' => 'Sekolah unggulan di Surabaya dengan fasilitas lengkap.',
        ]);

        // Panggil Seeder Sekolah yang baru (Generate data berdasarkan wilayah)
        $this->call(SchoolSeeder::class);
        
        // Buat 1 Operator Spesifik untuk testing login
        Operator::create([
            'nama_operator' => 'Operator SMAN 1 Surabaya',
            'username' => 'operator',
            'password_hash' => Hash::make('password'),
            'sekolah_tujuan' => 'SMAN 1 Surabaya',
        ]);

        // Buat data Operator Sekolah acak
        Operator::factory(5)->create();

        // Buat 1 Siswa Spesifik untuk testing login
        Student::create([
            'nisn' => '1234567890',
            'nama_lengkap' => 'Siswa Tester',
            'password_hash' => Hash::make('password'),
            'jenjang_tujuan' => 'SMA',
            'sekolah_tujuan' => 'SMAN 1 Surabaya',
            'jalur_pendaftaran' => 'Zonasi',
            'alamat' => 'Jl. Testing No. 1',
            'status_seleksi' => 'Pending',
            'status_approval' => 'Pending',
        ]);

        // Buat data siswa acak
        if (School::count() > 0) {
            Student::factory(20)->create();
        } else {
            $this->command->warn('Tidak ada data sekolah. Seeding untuk siswa dilewati.');
        }
    }
}
