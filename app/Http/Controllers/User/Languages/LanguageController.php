<?php

namespace App\Http\Controllers\User\Languages;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Languages\GetLanguagesRequest;
use App\Services\User\Languages\LanguageService;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    public function __construct(private readonly LanguageService $languageService)
    {

    }

    /**
     * @param GetLanguagesRequest $request
     * @return JsonResponse
     */
    public function index(GetLanguagesRequest $request): JsonResponse
    {
        return $this->languageService->index($request);
    }
}
