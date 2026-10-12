<?php

use App\Models\HealthUnit;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function patientPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'João Pereira',
        'cpf' => '52998224725',
        'birth_date' => '1990-05-20',
        'phone' => '85988887777',
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

it('rejects a birth date that is not in the Y-m-d format', function (string $birthDate) {
    $response = $this->postJson('/api/patients', patientPayload(['birth_date' => $birthDate]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('birth_date');

    $this->assertDatabaseCount('patients', 0);
})->with([
    'day and month with slashes' => '10/05/1990',
    'with time' => '1990-05-10 10:00:00',
    'invalid day' => '1990-02-30',
]);

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

it('returns a generic 404 message when the api route does not exist', function () {
    $this->getJson('/api/unknown')
        ->assertNotFound()
        ->assertJsonPath('message', __('patients.route_not_found'));
});

it('returns a portuguese message when the http method is not allowed', function () {
    $this->postJson('/api/patients/1')
        ->assertMethodNotAllowed()
        ->assertHeader('Allow', 'GET, HEAD, PUT, PATCH, DELETE')
        ->assertJsonPath('message', __('patients.method_not_allowed'));
});

it('translates the validation summary to portuguese', function () {
    $this->postJson('/api/patients', [])
        ->assertUnprocessable()
        ->assertJsonPath('message', 'O campo nome é obrigatório. (e mais 3 erros)');
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

it('finds patients by partial name', function () {
    Patient::factory()->create(['name' => 'Maria Silva']);
    Patient::factory()->create(['name' => 'Mariana Costa']);
    Patient::factory()->create(['name' => 'João Pereira']);

    $response = $this->getJson('/api/patients?search=Mari');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'Maria Silva')
        ->assertJsonPath('data.1.name', 'Mariana Costa');
});

it('returns an empty list when no name matches the search', function () {
    Patient::factory()->create(['name' => 'Maria Silva']);

    $response = $this->getJson('/api/patients?search=Zzz');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

it('accepts any cpf with 11 digits', function () {
    $this->postJson('/api/patients', patientPayload(['cpf' => '12345678901']))
        ->assertCreated()
        ->assertJsonPath('data.cpf', '12345678901');
});

it('deletes a patient', function () {
    $patient = Patient::factory()->create();

    $response = $this->deleteJson("/api/patients/{$patient->id}");

    $response->assertOk()
        ->assertJsonPath('message', __('patients.deleted'));

    $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
});

it('returns 404 when deleting a patient that does not exist', function () {
    $response = $this->deleteJson('/api/patients/9999');

    $response->assertNotFound()
        ->assertJsonPath('message', __('patients.not_found'));
});

it('returns 404 when updating a patient that does not exist', function () {
    $response = $this->putJson('/api/patients/9999', patientPayload());

    $response->assertNotFound()
        ->assertJsonPath('message', __('patients.not_found'));
});

it('does not accept a patient id that is not a number', function () {
    $patient = Patient::factory()->create();

    $this->deleteJson("/api/patients/{$patient->id}abc")
        ->assertNotFound()
        ->assertJsonPath('message', __('patients.route_not_found'));

    $this->assertDatabaseHas('patients', ['id' => $patient->id]);
});

it('rejects the cpf of another patient when updating', function () {
    Patient::factory()->create(['cpf' => '52998224725']);
    $patient = Patient::factory()->create(['cpf' => '11144477735']);

    $response = $this->putJson("/api/patients/{$patient->id}", patientPayload([
        'cpf' => '52998224725',
        'health_unit_id' => $patient->health_unit_id,
    ]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('cpf')
        ->assertJsonPath('errors.cpf.0', __('patients.cpf_taken'));

    $this->assertDatabaseHas('patients', ['id' => $patient->id, 'cpf' => '11144477735']);
});

it('shows a patient with its health unit', function () {
    $patient = Patient::factory()->create();

    $response = $this->getJson("/api/patients/{$patient->id}");

    $response->assertOk()
        ->assertJsonPath('data.id', $patient->id)
        ->assertJsonPath('data.health_unit.id', $patient->health_unit_id)
        ->assertJsonPath('data.health_unit.name', $patient->healthUnit->name);
});

it('paginates the list in pages of ten', function () {
    Patient::factory()->count(12)->create();

    $firstPage = $this->getJson('/api/patients');

    $firstPage->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.total', 12)
        ->assertJsonPath('meta.last_page', 2)
        ->assertJsonPath('links.prev', null);

    $secondPage = $this->getJson('/api/patients?page=2');

    $secondPage->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('links.next', null);
});

it('lists the patients ordered by name', function () {
    Patient::factory()->create(['name' => 'Carla']);
    Patient::factory()->create(['name' => 'Ana']);
    Patient::factory()->create(['name' => 'Bruno']);

    $response = $this->getJson('/api/patients');

    $response->assertOk()
        ->assertJsonPath('data.0.name', 'Ana')
        ->assertJsonPath('data.1.name', 'Bruno')
        ->assertJsonPath('data.2.name', 'Carla');
});

it('rejects a cpf with mask or letters', function (string $cpf) {
    $response = $this->postJson('/api/patients', patientPayload(['cpf' => $cpf]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('cpf')
        ->assertJsonPath('errors.cpf.0', __('validation.digits', ['attribute' => 'CPF', 'digits' => 11]));

    $this->assertDatabaseCount('patients', 0);
})->with([
    'with mask' => '529.982.247-25',
    'only letters' => 'abcdefghijk',
]);

it('partially updates a patient with patch', function () {
    $patient = Patient::factory()->create(['name' => 'Nome Antigo', 'cpf' => '52998224725']);

    $response = $this->patchJson("/api/patients/{$patient->id}", ['name' => 'Nome Novo']);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Nome Novo')
        ->assertJsonPath('data.cpf', '52998224725');

    $this->assertDatabaseHas('patients', ['id' => $patient->id, 'name' => 'Nome Novo', 'cpf' => '52998224725']);
});

it('still validates the fields sent in a patch', function () {
    $patient = Patient::factory()->create();

    $this->patchJson("/api/patients/{$patient->id}", ['name' => ''])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

it('rejects a phone with mask, letters or wrong size', function (string $phone) {
    $response = $this->postJson('/api/patients', patientPayload(['phone' => $phone]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('phone');

    $this->assertDatabaseCount('patients', 0);
})->with([
    'with mask' => '(85) 98888-7777',
    'only letters' => 'abcdefghij',
    'too short' => '859888',
    'too long' => '859888877771',
]);

it('accepts a patient without phone', function () {
    $this->postJson('/api/patients', patientPayload(['phone' => null]))
        ->assertCreated()
        ->assertJsonPath('data.phone', null);
});

it('rejects html in the patient name and normalizes whitespace', function () {
    $unit = HealthUnit::factory()->create();
    $payload = ['cpf' => '52998224725', 'birth_date' => '1990-01-01', 'health_unit_id' => $unit->id];

    $this->postJson('/api/patients', $payload + ['name' => '<img src=x>'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('name');

    $this->postJson('/api/patients', $payload + ['name' => "Maria\t\n  Silva\0"])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Maria Silva');
});
