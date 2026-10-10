<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (! $this->isValid($digits)) {
            $fail('validation.cpf')->translate();
        }
    }

    private function isValid(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        foreach ([9, 10] as $length) {
            $sum = 0;

            for ($index = 0; $index < $length; $index++) {
                $sum += (int) $cpf[$index] * ($length + 1 - $index);
            }

            $checkDigit = ($sum * 10) % 11 % 10;

            if ((int) $cpf[$length] !== $checkDigit) {
                return false;
            }
        }

        return true;
    }
}
