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

it('rejects a health unit that does not exist', function () {
    $response = $this->postJson('/api/patients', patientPayload([
        'health_unit_id' => 9999,
    ]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('health_unit_id');

    $this->assertDatabaseCount('patients', 0);
});

it('returns 404 when the patient does not exist', function () {
    $response = $this->getJson('/api/patients/9999');

    $response->assertNotFound()
        ->assertJsonPath('message', __('patients.not_found'));
});

it('updates a patient keeping the same cpf', function () {
    $patient = Patient::factory()->create(['cpf' => '52998224725']);

    $response = $this->putJson("/api/patients/{$patient->id}", patientPayload([
        'name' => 'Nome Atualizado',
        'health_unit_id' => $patient->health_unit_id,
    ]));

    $response->assertOk()
        ->assertJsonPath('data.name', 'Nome Atualizado')
        ->assertJsonPath('message', __('patients.updated'));

    $this->assertDatabaseHas('patients', [
        'id' => $patient->id,
        'name' => 'Nome Atualizado',
        'cpf' => '52998224725',
    ]);
});
