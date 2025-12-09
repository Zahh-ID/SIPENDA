<?php

namespace Database\Factories;

use App\Models\Operator; // Import the Operator model
use App\Models\School;   // Import the School model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // Import Hash facade

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
        // Ensure that schools are seeded before operators
        if (School::count() === 0) {
            // If no schools exist, create one to ensure foreign key constraint
            School::factory()->create();
        }

        return [
            'username' => $this->faker->unique()->userName(),
            'nama_operator' => $this->faker->name(),
            'sekolah_tujuan' => School::all()->random()->nama_sekolah, // Assign a random existing school Name
            'password_hash' => Hash::make('password'),
        ];
    }
}

