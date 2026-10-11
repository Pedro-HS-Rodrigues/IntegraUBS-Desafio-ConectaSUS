<?php

namespace Database\Factories;

use App\Models\HealthUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cpf' => fake()->unique()->cpf(false),
            'birth_date' => fake()->dateTimeBetween('-90 years', '-1 year')->format('Y-m-d'),
            'phone' => fake()->numerify('859########'),
            'health_unit_id' => HealthUnit::factory(),
        ];
    }
}
