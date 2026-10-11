<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class HealthUnitDocs
{
    #[OA\Get(
        path: '/api/health-units',
        operationId: 'listHealthUnits',
        summary: 'Lista as unidades de saúde',
        description: 'Retorna todas as unidades de saúde ordenadas por nome. O id é o valor usado em health_unit_id no cadastro de pacientes.',
        tags: ['Unidades de saúde'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de unidades de saúde.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/HealthUnit')),
                    ],
                ),
            ),
        ],
    )]
    public function index(): void {}
}
