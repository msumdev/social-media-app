<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

/**
 * Class BaseService
 */
class BaseService
{
    public function success($message = null, $data = null, $type = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'type' => $type,
            'message' => $message,
        ]);
    }

    public function fail($message = null, $data = null, $type = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'type' => $type,
            'message' => $message,
        ]);
    }
}
