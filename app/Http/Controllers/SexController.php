<?php

namespace App\Http\Controllers;

use App\Models\Sex;
use Illuminate\Http\Request;

class SexController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request)
    {
        $sexes = Sex::all();

        return response()->json($sexes);
    }
}
