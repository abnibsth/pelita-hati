<?php

namespace Database\Factories;

use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Balita>
 */
class BalitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('##############'),
            'name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'gender' => fake()->randomElement(['L', 'P']),
            'mother_name' => fake()->name(),
            'mother_nik' => fake()->numerify('##############'),
            'father_name' => fake()->name(),
            'parent_phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'rt_rw' => fake()->numerify('###/###'),
            'posyandu_id' => Posyandu::factory(),
            'status' => 'aktif',
            'registration_date' => now(),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    public function pindah(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pindah',
        ]);
    }
}
