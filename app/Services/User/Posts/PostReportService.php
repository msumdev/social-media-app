<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\Report\CreatePostReportRequest;
use App\Models\Posts\Post;
use App\Models\Posts\PostReport;
use App\Models\Posts\PostReportReason;
use Illuminate\Http\JsonResponse;

/**
 * Class PostReportService
 */
class PostReportService
{
    public function create(CreatePostReportRequest $request): JsonResponse
    {
        $post = Post::find($request->id);
        $postReport = PostReport::where('post_id', $request->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($post->user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot report your own post',
            ], 400);
        }

        if ($postReport) {
            return response()->json([
                'message' => 'You have already reported this post',
            ], 400);
        }

        $report = PostReport::create([
            'post_id' => $request->id,
            'user_id' => auth()->id(),
            'description' => $request->description,
        ]);

        foreach ($request->reasons as $reason) {
            PostReportReason::create([
                'post_report_id' => $report->id,
                'reason_id' => $reason,
            ]);
        }

        return response()->json([
            'message' => 'Post reported',
        ]);
    }
}
