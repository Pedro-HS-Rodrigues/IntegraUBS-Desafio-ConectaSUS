<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: 'HealthUnit',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'UBS Centro'),
                new OA\Property(property: 'city', type: 'string', example: 'Fortaleza'),
            ],
        ),
        new OA\Schema(
            schema: 'PatientSummaryHealthUnit',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'UBS Centro'),
            ],
        ),
        new OA\Schema(
            schema: 'Patient',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'Maria da Silva'),
                new OA\Property(property: 'cpf', type: 'string', description: 'Somente os 11 dígitos.', example: '52998224725'),
                new OA\Property(property: 'birth_date', type: 'string', format: 'date', example: '1985-03-10'),
                new OA\Property(property: 'phone', type: 'string', nullable: true, description: 'Somente os dígitos.', example: '85988887777'),
                new OA\Property(property: 'health_unit_id', type: 'integer', example: 1),
                new OA\Property(property: 'health_unit', ref: '#/components/schemas/PatientSummaryHealthUnit'),
            ],
        ),
        new OA\Schema(
            schema: 'PatientRequest',
            required: ['name', 'cpf', 'birth_date', 'health_unit_id'],
            properties: [
                new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Maria da Silva'),
                new OA\Property(property: 'cpf', type: 'string', description: 'CPF válido (com os dígitos verificadores), com ou sem máscara. A API guarda só os 11 dígitos.', example: '529.982.247-25'),
                new OA\Property(property: 'birth_date', type: 'string', format: 'date', description: 'Não pode ser uma data futura.', example: '1985-03-10'),
                new OA\Property(property: 'phone', type: 'string', nullable: true, maxLength: 20, description: 'Opcional. Com ou sem máscara.', example: '(85) 98888-7777'),
                new OA\Property(property: 'health_unit_id', type: 'integer', description: 'ID de uma unidade de saúde existente.', example: 1),
            ],
        ),
        new OA\Schema(
            schema: 'PaginationLinks',
            properties: [
                new OA\Property(property: 'first', type: 'string', example: 'http://localhost:8000/api/patients?page=1'),
                new OA\Property(property: 'last', type: 'string', example: 'http://localhost:8000/api/patients?page=2'),
                new OA\Property(property: 'prev', type: 'string', nullable: true, example: null),
                new OA\Property(property: 'next', type: 'string', nullable: true, example: 'http://localhost:8000/api/patients?page=2'),
            ],
        ),
        new OA\Schema(
            schema: 'PaginationMeta',
            properties: [
                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                new OA\Property(property: 'from', type: 'integer', nullable: true, example: 1),
                new OA\Property(property: 'last_page', type: 'integer', example: 2),
                new OA\Property(
                    property: 'links',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'url', type: 'string', nullable: true, example: 'http://localhost:8000/api/patients?page=1'),
                            new OA\Property(property: 'label', type: 'string', example: '1'),
                            new OA\Property(property: 'page', type: 'integer', nullable: true, example: 1),
                            new OA\Property(property: 'active', type: 'boolean', example: true),
                        ],
                    ),
                ),
                new OA\Property(property: 'path', type: 'string', example: 'http://localhost:8000/api/patients'),
                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                new OA\Property(property: 'to', type: 'integer', nullable: true, example: 10),
                new OA\Property(property: 'total', type: 'integer', example: 20),
            ],
        ),
        new OA\Schema(
            schema: 'Message',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Paciente não encontrado.'),
            ],
        ),
        new OA\Schema(
            schema: 'ValidationError',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'O campo nome é obrigatório. (and 1 more error)'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    example: [
                        'name' => ['O campo nome é obrigatório.'],
                        'cpf' => ['O campo CPF deve ter 11 dígitos.'],
                    ],
                    additionalProperties: new OA\AdditionalProperties(type: 'array', items: new OA\Items(type: 'string')),
                ),
            ],
        ),
    ],
    parameters: [
        new OA\Parameter(
            parameter: 'PatientId',
            name: 'patient',
            in: 'path',
            required: true,
            description: 'ID do paciente.',
            schema: new OA\Schema(type: 'integer', example: 1),
        ),
    ],
)]
class SchemaDocs {}
