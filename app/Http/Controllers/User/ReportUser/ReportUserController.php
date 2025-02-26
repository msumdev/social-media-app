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
    public function __construct(private readonly ReportUserService $reportUserService)
    {

    }

    /**
     * @param GetUserReportsRequest $request
     * @return JsonResponse
     */
    public function index(GetUserReportsRequest $request): JsonResponse
    {
        return $this->reportUserService->getReportsForUser($request);
    }

    /**
     * @param GetReportReasonsRequest $request
     * @return JsonResponse
     */
    public function reasons(GetReportReasonsRequest $request): JsonResponse
    {
        return response()->json(ReportReason::all());
    }

    /**
     * @param CreateUserReportRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserReportRequest $request): JsonResponse
    {
        return $this->reportUserService->create($request);
    }
}
