<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService
{
    /**
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function success($message = null, $data = null, $type = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'type' => $type,
            'message' => $message,
        ]);
    }

    /**
     * @param $message
     * @param $data
     * @return JsonResponse
     */
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
