<?php

it('opens the swagger interface', function () {
    $response = $this->get('/api/documentation');

    $response->assertOk()
        ->assertSee('IntegraUBS API');
});

it('generates the documentation with every endpoint of the api', function () {
    $response = $this->getJson('/docs?api-docs.json');

    $response->assertOk()
        ->assertJsonPath('info.title', 'IntegraUBS API')
        ->assertJsonStructure(['paths' => ['/api/patients', '/api/patients/{id}', '/api/health-units']]);

    $operations = collect($response->json('paths'))
        ->flatMap(fn (array $path) => array_keys($path));

    expect($operations)->toHaveCount(7);
});
