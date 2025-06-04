<?php

namespace App\Services\User\Interests;

use App\Http\Requests\User\Interests\GetInterestsRequest;
use App\Models\User\InterestType;
use Illuminate\Http\JsonResponse;

/**
 * Class InterestService
 */
class InterestService
{
    public function index(GetInterestsRequest $request): JsonResponse
    {
        $interests = InterestType::all();

        return response()->json($interests);
    }
}
