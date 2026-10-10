<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'IntegraUBS API',
    description: 'API para cadastrar e consultar pacientes vinculados a unidades de saúde.',
)]
#[OA\Server(url: '/', description: 'Servidor atual')]
#[OA\Tag(name: 'Pacientes', description: 'Cadastro, consulta, edição e exclusão de pacientes')]
#[OA\Tag(name: 'Unidades de saúde', description: 'Consulta das unidades de saúde')]
class ApiDocs {}
