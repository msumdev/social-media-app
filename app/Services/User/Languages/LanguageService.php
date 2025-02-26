<?php

namespace App\Services\User\Languages;

use App\Http\Requests\User\Languages\GetInterestsRequest;
use App\Http\Requests\User\Languages\GetLanguagesRequest;
use App\Models\User\LanguageType;
use Illuminate\Http\JsonResponse;

/**
 * Class LanguageService
 * @package App\Services\User\Languages
 */
class LanguageService
{
    /**
     * @param GetLanguagesRequest $request
     * @return JsonResponse
     */
    public function index(GetLanguagesRequest $request): JsonResponse
    {
        $languages = LanguageType::all();

        return response()->json($languages);
    }
}
