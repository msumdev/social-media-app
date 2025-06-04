<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request, $id)
    {
        $cities = City::where('country_id', $id)->get();

        return response()->json($cities);
    }
}
