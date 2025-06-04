<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request)
    {
        $countries = Country::all();

        return response()->json($countries);
    }
}
