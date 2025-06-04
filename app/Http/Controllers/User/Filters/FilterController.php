<?php

namespace App\Http\Controllers\User\Filters;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Filters\GetSearchSettingsRequest;
use App\Http\Requests\User\Filters\UpdateFiltersRequest;
use App\Services\User\Filters\FilterService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FilterController extends Controller
{
    public function __construct(private readonly FilterService $filterService) {}

    public function updateFilters(UpdateFiltersRequest $request): JsonResponse
    {
        return $this->filterService->updateFilters($request);
    }

    public function getSearchSettings(GetSearchSettingsRequest $request): JsonResponse
    {
        return $this->filterService->getSearchSettings($request);
    }

    public function getCountry(Request $request, $id): JsonResponse
    {
        return $this->filterService->getCountry($id);
    }
}
