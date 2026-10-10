<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PatientController extends Controller
{
    public function __construct(private readonly PatientService $patientService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $patients = $this->patientService->list($request->query('search'));

        return PatientResource::collection($patients);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = $this->patientService->create($request->validated());

        return (new PatientResource($patient))
            ->additional(['message' => __('patients.created')])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Patient $patient): PatientResource
    {
        return new PatientResource($patient->load('healthUnit'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient = $this->patientService->update($patient, $request->validated());

        return (new PatientResource($patient))
            ->additional(['message' => __('patients.updated')])
            ->response();
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $this->patientService->delete($patient);

        return response()->json(['message' => __('patients.deleted')]);
    }
}
