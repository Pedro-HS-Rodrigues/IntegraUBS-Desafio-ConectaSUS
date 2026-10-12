<?php

use App\Models\HealthUnit;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects a search sent as an array', function () {
    $this->getJson('/api/patients?search[]=maria')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('search');
});

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

it('searches by health unit name', function () {
    $unit = HealthUnit::factory()->create(['name' => 'UBS Messejana']);
    Patient::factory()->create(['name' => 'Maria Silva', 'health_unit_id' => $unit->id]);
    Patient::factory()->create(['name' => 'Joao Souza']);

    $this->getJson('/api/patients?search=Messejana')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Maria Silva');
});

it('searches by cpf with or without mask', function () {
    Patient::factory()->create(['name' => 'Maria Silva', 'cpf' => '52998224725']);
    Patient::factory()->create(['name' => 'Joao Souza']);

    $this->getJson('/api/patients?search=529.982.247-25')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Maria Silva');
});

it('searches by phone', function () {
    Patient::factory()->create(['name' => 'Maria Silva', 'phone' => '85999887766']);
    Patient::factory()->create(['name' => 'Joao Souza', 'phone' => '85911112222']);

    $this->getJson('/api/patients?search=(85) 99988')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Maria Silva');
});

it('searches by the digit zero', function () {
    Patient::factory()->create(['name' => 'Maria Silva', 'cpf' => '01234567890', 'phone' => null]);
    Patient::factory()->create(['name' => 'Joao Souza', 'cpf' => '11111111111', 'phone' => null]);

    $this->getJson('/api/patients?search=0')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Maria Silva');
});

it('does not match digits from a non numeric search', function () {
    Patient::factory()->create(['name' => 'Maria Silva', 'cpf' => '52998224725']);

    $this->getJson('/api/patients?search='.urlencode('<script>alert(1)</script>'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
