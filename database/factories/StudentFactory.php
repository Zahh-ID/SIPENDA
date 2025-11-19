<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil sekolah secara acak untuk dijadikan tujuan
        $school = School::inRandomOrder()->first();

        return [
            'nisn' => $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => $this->faker->name(),
            'password_hash' => Hash::make('password'), // password default
            'jenjang_tujuan' => $school->jenjang,
            'sekolah_tujuan' => $school->nama_sekolah,
            'jalur_pendaftaran' => $this->faker->randomElement(['Zonasi', 'Afirmasi', 'Prestasi', 'Mutasi']),
            'alamat' => $this->faker->address(),
            'status_seleksi' => $this->faker->randomElement(['Pending', 'Diterima', 'Ditolak']),
        ];
    }
}