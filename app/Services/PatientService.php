<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientService
{
    public function list(?string $search): LengthAwarePaginator
    {
        return Patient::with('healthUnit')
            ->when($search, fn ($query) => $this->applySearch($query, $search))
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

    private function applySearch(Builder $query, string $search): void
    {
        $term = '%'.$this->escapeLike($search).'%';
        $digits = preg_match('/^[\d\s.\-()]+$/', $search) ? preg_replace('/\D/', '', $search) : '';

        $query->where(function (Builder $query) use ($term, $digits) {
            $query->whereRaw("name like ? escape '!'", [$term])
                ->orWhereHas('healthUnit', fn (Builder $unit) => $unit->whereRaw("name like ? escape '!'", [$term]));

            if ($digits !== '') {
                $digitsTerm = '%'.$digits.'%';
                $query->orWhere('cpf', 'like', $digitsTerm)
                    ->orWhere('phone', 'like', $digitsTerm);
            }
        });
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value);
    }
}
