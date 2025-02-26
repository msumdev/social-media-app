<?php

namespace App\Services\User\Comments;

use App\Facades\ElasticSearchServiceFacade;
use App\Http\Requests\User\Comments\CreateUserProfileCommentRequest;
use App\Http\Requests\User\Comments\DeleteUserProfileCommentRequest;
use App\Http\Requests\User\Comments\GetUserProfileCommentRequest;
use App\Models\Asset;
use App\Models\User\ProfileComment;
use App\Services\SanitizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class UserProfileCommentService
 * @package App\Services\User\Comments
 */
class UserProfileCommentService
{
    /**
     * @param GetUserProfileCommentRequest $request
     * @return JsonResponse
     */
    public function index(GetUserProfileCommentRequest $request): JsonResponse
    {
        $comments = ProfileComment::orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json($comments);
    }

    /**
     * @param CreateUserProfileCommentRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create(CreateUserProfileCommentRequest $request): JsonResponse
    {
        $sanitizationService = new SanitizationService();
        $content = $sanitizationService->sanitize($request->get('content'));

        $audios = $request->file('audios') ?? [];

        $comment = ProfileComment::create([
            'user' => auth()->id(),
            'content' => $content,
            'hashtags' => $request->get('hashtags'),
            'mentions' => $request->get('mentions')
        ]);

        foreach ($audios as $audio) {
            $asset = Asset::create([
                'profile_comment_id' => $comment->_id,
                'type' => Asset::PROFILE_COMMENT_AUDIO,
                'path' => sha1(Str::random(16))
            ]);

            Storage::disk('profile-comment-audios')->put($asset->path, $audio->get());
        }

        return response()->json([
            'message' => 'Comment created',
        ]);
    }

    /**
     * @param DeleteUserProfileCommentRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function destroy(DeleteUserProfileCommentRequest $request): JsonResponse
    {
        $comment = ProfileComment::where('_id', $request->id)->first();

        if ($comment->user != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $assets = Asset::where('profile_comment_id', $comment->_id)->get();

        foreach ($assets as $asset) {
            Storage::disk('profile-comment-audios')->delete($asset->path);
            $asset->delete();
        }

        $comment->delete();

        ElasticSearchServiceFacade::refreshIndex(ProfileComment::getIndexName());

        return response()->json(['message' => 'Comment deleted']);
    }
}
