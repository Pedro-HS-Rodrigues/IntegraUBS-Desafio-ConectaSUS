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

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'cpf' => ['sometimes', 'bail', 'required', 'digits:11', new Cpf, Rule::unique('patients', 'cpf')->ignore($this->route('patient'))],
            'birth_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'phone' => ['nullable', 'digits_between:10,11'],
            'health_unit_id' => ['sometimes', 'required', 'integer', 'exists:health_units,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.unique' => __('patients.cpf_taken'),
        ];
    }
}
