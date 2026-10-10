<?php

use App\Rules\Cpf;
use Illuminate\Support\Facades\Validator;

function cpfPasses(string $cpf): bool
{
    return Validator::make(['cpf' => $cpf], ['cpf' => [new Cpf]])->passes();
}

it('accepts a valid cpf', function (string $cpf) {
    expect(cpfPasses($cpf))->toBeTrue();
})->with([
    'digits only' => '52998224725',
    'with mask' => '529.982.247-25',
    'another valid one' => '11144477735',
]);

it('rejects an invalid cpf', function (string $cpf) {
    expect(cpfPasses($cpf))->toBeFalse();
})->with([
    'all digits equal' => '11111111111',
    'wrong check digits' => '52998224724',
    'sequence' => '12345678901',
    'too short' => '5299822472',
]);

it('uses the translated message when the cpf is invalid', function () {
    $validator = Validator::make(['cpf' => '11111111111'], ['cpf' => [new Cpf]]);

    expect($validator->errors()->first('cpf'))->toBe(__('validation.cpf'));
});
