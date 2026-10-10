<?php

namespace Database\Seeders;

use App\Models\HealthUnit;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $units = HealthUnit::orderBy('id')->get()->slice(0, -1)->values();

        Patient::factory()->create([
            'name' => 'Maria da Silva',
            'health_unit_id' => $units->first()->id,
        ]);

        Patient::factory()
            ->count(19)
            ->state(fn () => ['health_unit_id' => $units->random()->id])
            ->create();
    }
}
