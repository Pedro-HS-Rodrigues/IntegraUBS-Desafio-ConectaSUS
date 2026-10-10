<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['cpf', 'phone'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([$field => preg_replace('/\D/', '', $this->input($field)) ?: null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['bail', 'required', 'digits:11', new Cpf, Rule::unique('patients', 'cpf')->ignore($this->route('patient'))],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'phone' => ['nullable', 'string', 'max:20'],
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
