<?php

namespace App\Http\Controllers\User\Logs;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Logs\GetUserProfileAccessLogsRequest;
use App\Services\User\Logs\LogService;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileLogsController
 * @package App\Http\Controllers\User\Logs
 */
class UserProfileLogsController extends Controller
{
    public function __construct(private readonly LogService $logService)
    {

    }

    /**
     * @param GetUserProfileAccessLogsRequest $request
     * @return JsonResponse
     */
    public function access(GetUserProfileAccessLogsRequest $request): JsonResponse
    {
        return $this->logService->access($request);
    }
}
