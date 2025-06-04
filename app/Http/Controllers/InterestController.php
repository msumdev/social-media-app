<?php

namespace App\Http\Controllers;

use App\Models\User\InterestType;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request)
    {
        $interests = InterestType::all();

        return response()->json($interests);
    }
}
