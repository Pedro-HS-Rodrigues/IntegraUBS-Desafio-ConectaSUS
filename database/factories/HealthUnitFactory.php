<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HealthUnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'UBS '.fake()->unique()->lastName(),
            'city' => 'Fortaleza',
        ];
    }
}
