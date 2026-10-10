<?php

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('treats the percent sign in the search as a literal character', function () {
    Patient::factory()->create(['name' => 'Maria Silva']);
    Patient::factory()->create(['name' => 'Joao 100% Silva']);

    $response = $this->getJson('/api/patients?search=%25');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Joao 100% Silva');
});

it('treats the underscore in the search as a literal character', function () {
    Patient::factory()->create(['name' => 'Maria Silva']);
    Patient::factory()->create(['name' => 'Ana_Paula']);

    $response = $this->getJson('/api/patients?search=_');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Ana_Paula');
});

it('treats the escape character in the search as a literal character', function () {
    Patient::factory()->create(['name' => 'Maria Silva']);
    Patient::factory()->create(['name' => 'Joana!']);

    $response = $this->getJson('/api/patients?search=!');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Joana!');
});
