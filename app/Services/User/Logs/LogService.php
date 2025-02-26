<?php

namespace App\Services\User\Logs;

use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\GetUserListRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\Logs\GetUserProfileAccessLogsRequest;
use App\Models\AppLog;
use App\Models\User\BlockedUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class LogService
 * @package App\Services\User\Logs
 */
class LogService
{
    /**
     * @param GetUserProfileAccessLogsRequest $request
     * @return JsonResponse
     */
    public function access(GetUserProfileAccessLogsRequest $request): JsonResponse
    {
        $logs = AppLog::where('user', auth()->id())
            ->where('type', AppLog::PROFILE_VIEW)
            ->paginate(10);

        return response()->json($logs);
    }
}
