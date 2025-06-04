<?php

namespace App\Http\Controllers\User\ReportUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReportUser\CreateUserReportRequest;
use App\Http\Requests\User\ReportUser\GetReportReasonsRequest;
use App\Http\Requests\User\ReportUser\GetUserReportsRequest;
use App\Models\ReportReason;
use App\Services\User\ReportUser\ReportUserService;
use Illuminate\Http\JsonResponse;

class ReportUserController extends Controller
{
    public function __construct(private readonly ReportUserService $reportUserService) {}

    public function index(GetUserReportsRequest $request): JsonResponse
    {
        return $this->reportUserService->getReports();
    }

    public function reasons(GetReportReasonsRequest $request): JsonResponse
    {
        return response()->json(ReportReason::all());
    }

    public function create(CreateUserReportRequest $request): JsonResponse
    {
        $userId = $request->id;
        $reportReason = $request->input('reason');

        $report = $this->reportUserService->create($userId, $reportReason);

        return response()->json($report);
    }
}
