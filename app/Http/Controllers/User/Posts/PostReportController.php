<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\Report\CreatePostReportRequest;
use App\Services\User\Posts\PostReportService;
use Illuminate\Http\JsonResponse;

class PostReportController extends Controller
{
    public function __construct(private readonly PostReportService $postReportService)
    {

    }

    /**
     * @param CreatePostReportRequest $request
     * @return JsonResponse
     */
    public function create(CreatePostReportRequest $request): JsonResponse
    {
        return $this->postReportService->create($request);
    }
}
