<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request)
    {
        $genders = Gender::all();

        return response()->json($genders);
    }
}
