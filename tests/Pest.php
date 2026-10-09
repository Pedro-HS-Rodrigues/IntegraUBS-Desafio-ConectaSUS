<?php

/*
| Configuração do Pest.
| Todos os testes de tests/Feature usam o TestCase do Laravel.
| O RefreshDatabase será adicionado quando os testes da API forem criados (etapa 5).
*/

use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature');
