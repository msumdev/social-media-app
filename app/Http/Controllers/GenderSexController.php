<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Sex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenderSexController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getGenders(Request $request): JsonResponse
    {
        return response()->json([
            'genders' => Gender::all()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSexes(Request $request): JsonResponse
    {
        return response()->json([
            'sexes' => Sex::all()
        ]);
    }
}
