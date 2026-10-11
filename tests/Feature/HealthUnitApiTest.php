<?php

use App\Models\HealthUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists the health units ordered by name', function () {
    HealthUnit::factory()->create(['name' => 'UBS Zeta']);
    HealthUnit::factory()->create(['name' => 'UBS Alfa']);

    $response = $this->getJson('/api/health-units');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'UBS Alfa')
        ->assertJsonPath('data.1.name', 'UBS Zeta')
        ->assertJsonStructure(['data' => ['*' => ['id', 'name', 'city']]]);
});

it('returns an empty list when there are no health units', function () {
    $response = $this->getJson('/api/health-units');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});
