<?php

namespace App\Http\Controllers\User\Logs;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Logs\GetUserProfileAccessLogsRequest;
use App\Services\User\Logs\LogService;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileLogsController
 */
class UserProfileLogsController extends Controller
{
    public function __construct(private readonly LogService $logService) {}

    public function access(GetUserProfileAccessLogsRequest $request): JsonResponse
    {
        $logs = $this->logService->accessLogs($request->user()->id());

        return response()->json($logs);
    }
}
