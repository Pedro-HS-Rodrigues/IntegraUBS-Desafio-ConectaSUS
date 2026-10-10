<?php

use App\Models\HealthUnit;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function patientPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'João Pereira',
        'cpf' => '529.982.247-25',
        'birth_date' => '1990-05-20',
        'phone' => '(85) 98888-7777',
        'health_unit_id' => HealthUnit::factory()->create()->id,
    ], $overrides);
}

it('creates a patient and stores only the digits of cpf and phone', function () {
    $response = $this->postJson('/api/patients', patientPayload());

    $response->assertCreated()
        ->assertJsonPath('data.name', 'João Pereira')
        ->assertJsonPath('data.cpf', '52998224725')
        ->assertJsonPath('message', __('patients.created'));

    $this->assertDatabaseHas('patients', [
        'name' => 'João Pereira',
        'cpf' => '52998224725',
        'phone' => '85988887777',
    ]);
});

it('rejects a duplicated cpf', function () {
    Patient::factory()->create(['cpf' => '52998224725']);

    $response = $this->postJson('/api/patients', patientPayload());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('cpf');

    $this->assertDatabaseCount('patients', 1);
});

it('rejects a future birth date', function () {
    $response = $this->postJson('/api/patients', patientPayload([
        'birth_date' => now()->addDay()->format('Y-m-d'),
    ]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('birth_date');

    $this->assertDatabaseCount('patients', 0);
});
