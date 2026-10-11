<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthUnitResource;
use App\Models\HealthUnit;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HealthUnitController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return HealthUnitResource::collection(HealthUnit::orderBy('name')->get());
    }
}
