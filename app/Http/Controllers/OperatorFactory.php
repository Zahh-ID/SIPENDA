<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\Operator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 */
class OperatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Operator::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil sekolah secara acak untuk dijadikan tanggung jawab operator
        $school = School::inRandomOrder()->first();
        $schoolName = strtolower(explode(' ', $school->nama_sekolah)[2]); // e.g., 'malang'

        return [
            // Membuat username yang unik dan mudah ditebak, cth: op_malang_1
            'username' => $this->faker->unique()->numerify('op_' . $schoolName . '_##'),
            'nama_operator' => $this->faker->name(),
            'sekolah_tujuan' => $school->nama_sekolah,
            'password_hash' => Hash::make('password'), // Password default untuk semua operator: "password"
        ];
    }
}