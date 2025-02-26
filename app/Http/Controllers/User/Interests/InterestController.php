<?php

namespace App\Http\Controllers\User\Interests;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Interests\GetInterestsRequest;
use App\Services\User\Interests\InterestService;
use Illuminate\Http\JsonResponse;

class InterestController extends Controller
{
    public function __construct(private readonly InterestService $interestService)
    {

    }

    /**
     * @param GetInterestsRequest $request
     * @return JsonResponse
     */
    public function index(GetInterestsRequest $request): JsonResponse
    {
        return $this->interestService->index($request);
    }
}
