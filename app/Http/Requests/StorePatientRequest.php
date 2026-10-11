<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->name)) {
            $this->merge([
                'name' => trim(preg_replace('/[\s\p{C}]+/u', ' ', $this->name)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[<>]/'],
            'cpf' => ['bail', 'required', 'digits:11', Rule::unique('patients', 'cpf')],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'phone' => ['nullable', 'digits_between:10,11'],
            'health_unit_id' => ['required', 'integer', 'exists:health_units,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.unique' => __('patients.cpf_taken'),
        ];
    }
}
