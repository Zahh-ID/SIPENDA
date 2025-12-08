<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenjang = $this->faker->randomElement(['SD', 'SMP', 'SMA']);
        $kota = $this->faker->randomElement(['Malang', 'Nganjuk', 'Blitar', 'Kediri', 'Surabaya']);
        
        return [
            'nama_sekolah' => $this->faker->unique()->lexify($jenjang . 'N ' . $this->faker->numberBetween(1, 150) . ' ' . $kota . ' ?'),
            'jenjang' => $jenjang,
            'kota_kab' => $kota,
            'kuota' => $this->faker->numberBetween(80, 350),
            'detail' => 'Akreditasi A, Sekolah Unggulan di ' . $kota,
        ];
    }
}