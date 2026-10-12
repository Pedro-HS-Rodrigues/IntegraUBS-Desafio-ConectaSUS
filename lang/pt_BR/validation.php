<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'date_format' => 'O campo :attribute deve ser uma data no formato :format.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
    'not_regex' => 'O campo :attribute contém caracteres inválidos.',
    'exists' => 'O valor informado para :attribute é inválido.',
    'unique' => 'O valor informado para :attribute já está em uso.',
    'before_or_equal' => 'O campo :attribute não pode ser uma data futura.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],

    'attributes' => [
        'name' => 'nome',
        'cpf' => 'CPF',
        'birth_date' => 'data de nascimento',
        'phone' => 'telefone',
        'health_unit_id' => 'unidade de saúde',
        'search' => 'busca',
    ],
];
