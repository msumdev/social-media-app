<?php

namespace App\Services\User\Languages;

use App\Http\Requests\User\Languages\GetLanguagesRequest;
use App\Models\User\LanguageType;
use Illuminate\Http\JsonResponse;

/**
 * Class LanguageService
 */
class LanguageService
{
    public function index(GetLanguagesRequest $request): JsonResponse
    {
        $languages = LanguageType::all();

        return response()->json($languages);
    }
}
