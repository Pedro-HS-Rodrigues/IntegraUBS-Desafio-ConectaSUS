<?php

namespace App\Http\Requests;

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
        if (is_string($this->name)) {
            $this->merge([
                'name' => trim(preg_replace('/[\s\p{C}]+/u', ' ', $this->name)),
            ]);
        }
    }

    public function rules(): array
    {
        $presenceRules = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'name' => [...$presenceRules, 'string', 'max:255', 'not_regex:/[<>]/'],
            'cpf' => ['bail', ...$presenceRules, 'digits:11', Rule::unique('patients', 'cpf')->ignore($this->route('patient'))],
            'birth_date' => [...$presenceRules, 'date_format:Y-m-d', 'before_or_equal:today'],
            'phone' => ['nullable', 'digits_between:10,11'],
            'health_unit_id' => [...$presenceRules, 'integer', 'exists:health_units,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.unique' => __('patients.cpf_taken'),
        ];
    }
}
