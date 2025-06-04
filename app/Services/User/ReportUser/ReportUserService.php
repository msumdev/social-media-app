<?php

namespace App\Services\User\ReportUser;

use App\Models\User\UserReport;
use Illuminate\Http\JsonResponse;

/**
 * Class ReportUserService
 */
class ReportUserService
{
    public function getReports(): JsonResponse
    {
        $reports = UserReport::paginate(10);

        return response()->json($reports);
    }

    public function create(int $userId, string $reportReason): UserReport
    {
        return UserReport::create([
            'reporter_id' => auth()->id(),
            'user_id' => $userId,
            'reason' => $reportReason,
        ]);
    }
}
