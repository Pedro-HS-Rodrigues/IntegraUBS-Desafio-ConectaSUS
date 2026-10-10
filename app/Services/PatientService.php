<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientService
{
    public function list(?string $search): LengthAwarePaginator
    {
        return Patient::with('healthUnit')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Patient
    {
        $patient = Patient::create($data);

        return $patient->load('healthUnit');
    }

    public function update(Patient $patient, array $data): Patient
    {
        $patient->update($data);

        return $patient->load('healthUnit');
    }

    public function delete(Patient $patient): void
    {
        $patient->delete();
    }
}
