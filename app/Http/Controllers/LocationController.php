<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountries(Request $request): JsonResponse
    {
        return response()->json([
            'countries' => Country::all()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getCountry(Request $request, $id): JsonResponse
    {
        $id = (int) $id;

        return response()->json([
            'cities' => City::whereCountryId($id)->get()
        ]);
    }
}
