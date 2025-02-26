<?php

namespace App\Services\User\ReportUser;

use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\GetUserListRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\Profile\GetUserProfileRequest;
use App\Http\Requests\User\ReportUser\CreateUserReportRequest;
use App\Http\Requests\User\ReportUser\GetUserReportsRequest;
use App\Models\AppLog;
use App\Models\User\BlockedUser;
use App\Models\User\User;
use App\Models\User\UserReport;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ReportUserService
 * @package App\Services\User\ReportUser
 */
class ReportUserService
{
    /**
     * @param GetUserReportsRequest $request
     * @return JsonResponse
     */
    public function getReportsForUser(GetUserReportsRequest $request): JsonResponse
    {
        $reports = UserReport::paginate(10);

        return response()->json($reports);
    }

    /**
     * @param CreateUserReportRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserReportRequest $request): JsonResponse
    {
        if (auth()->id() == $request->id) {
            return response()->json(['message' => 'You cannot report yourself'], 400);
        }

        $report = UserReport::create([
            'reporter_id' => auth()->id(),
            'user_id' => $request->id,
            'reason' => $request->input('reason'),
        ]);

        return response()->json($report);
    }
}
