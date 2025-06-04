<?php

namespace App\Services\User\Logs;

use App\Models\AppLog;
use Illuminate\Http\JsonResponse;

/**
 * Class LogService
 */
class LogService
{
    public function accessLogs($userId): JsonResponse
    {
        return AppLog::where('user', $userId)
            ->where('type', AppLog::PROFILE_VIEW)
            ->paginate(10);
    }
}
