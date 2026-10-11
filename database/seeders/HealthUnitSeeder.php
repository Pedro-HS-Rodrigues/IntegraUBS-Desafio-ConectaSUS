<?php

namespace Database\Seeders;

use App\Models\HealthUnit;
use Illuminate\Database\Seeder;

class HealthUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Posto de Saúde Centro', 'city' => 'Fortaleza'],
            ['name' => 'Posto de Saúde Aracapé', 'city' => 'Fortaleza'],
            ['name' => 'Posto de Saúde Meireles', 'city' => 'Fortaleza'],
            ['name' => 'Posto de Saúde Barra do Ceará', 'city' => 'Fortaleza'],
        ];

        foreach ($units as $unit) {
            HealthUnit::create($unit);
        }
    }
}
