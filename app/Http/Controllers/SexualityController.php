<?php

namespace App\Http\Controllers;

use App\Models\User\SexualityType;
use Illuminate\Http\Request;

class SexualityController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request)
    {
        $sexes = SexualityType::all();

        return response()->json($sexes);
    }
}
