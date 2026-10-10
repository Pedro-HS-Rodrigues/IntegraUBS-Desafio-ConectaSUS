<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class PatientDocs
{
    #[OA\Get(
        path: '/api/patients',
        operationId: 'listPatients',
        summary: 'Lista os pacientes',
        description: 'Retorna os pacientes paginados de 10 em 10, ordenados por nome. O parâmetro search filtra pelo nome (busca parcial).',
        tags: ['Pacientes'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, description: 'Parte do nome do paciente.', schema: new OA\Schema(type: 'string', example: 'Maria')),
            new OA\Parameter(name: 'page', in: 'query', required: false, description: 'Número da página.', schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista paginada de pacientes.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Patient')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ],
                ),
            ),
        ],
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/patients',
        operationId: 'createPatient',
        summary: 'Cadastra um paciente',
        tags: ['Pacientes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PatientRequest'),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Paciente cadastrado.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Patient'),
                        new OA\Property(property: 'message', type: 'string', example: 'Paciente cadastrado com sucesso.'),
                    ],
                ),
            ),
            new OA\Response(response: 422, description: 'Dados inválidos ou CPF já cadastrado.', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ],
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/patients/{patient}',
        operationId: 'showPatient',
        summary: 'Consulta um paciente',
        tags: ['Pacientes'],
        parameters: [new OA\Parameter(ref: '#/components/parameters/PatientId')],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do paciente.',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'data', ref: '#/components/schemas/Patient')],
                ),
            ),
            new OA\Response(response: 404, description: 'Paciente não encontrado.', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
        ],
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/patients/{patient}',
        operationId: 'updatePatient',
        summary: 'Atualiza um paciente',
        description: 'Todos os campos obrigatórios devem ser enviados. O CPF do próprio paciente pode ser mantido.',
        tags: ['Pacientes'],
        parameters: [new OA\Parameter(ref: '#/components/parameters/PatientId')],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PatientRequest'),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paciente atualizado.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Patient'),
                        new OA\Property(property: 'message', type: 'string', example: 'Paciente atualizado com sucesso.'),
                    ],
                ),
            ),
            new OA\Response(response: 404, description: 'Paciente não encontrado.', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 422, description: 'Dados inválidos ou CPF já cadastrado em outro paciente.', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ],
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/patients/{patient}',
        operationId: 'deletePatient',
        summary: 'Exclui um paciente',
        tags: ['Pacientes'],
        parameters: [new OA\Parameter(ref: '#/components/parameters/PatientId')],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paciente excluído.',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Paciente excluído com sucesso.')],
                ),
            ),
            new OA\Response(response: 404, description: 'Paciente não encontrado.', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
        ],
    )]
    public function destroy(): void {}
}
