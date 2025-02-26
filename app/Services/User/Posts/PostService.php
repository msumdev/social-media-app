<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\CreatePostRequest;
use App\Http\Requests\User\Posts\DeletePostRequest;
use App\Http\Requests\User\Posts\DislikeCommentRequest;
use App\Http\Requests\User\Posts\DislikePostRequest;
use App\Http\Requests\User\Posts\GetPostRequest;
use App\Http\Requests\User\Posts\GetPostsRequest;
use App\Http\Requests\User\Posts\LikeCommentRequest;
use App\Http\Requests\User\Posts\LikePostRequest;
use App\Http\Resources\Post\NewPostCollectionResource;
use App\Http\Resources\Post\PostCollectionResource;
use App\Jobs\PostAddedJob;
use App\Jobs\PostDeletedJob;
use App\Models\Asset;
use App\Models\Posts\HashTag;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\PostCommentDislike;
use App\Models\Posts\PostCommentLike;
use App\Models\Posts\PostDislike;
use App\Models\Posts\PostHashTag;
use App\Models\Posts\PostLike;
use App\Models\Posts\PostTest;
use App\Services\SanitizationService;
use App\Traits\Caching;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class PostService
 * @package App\Services\User\Posts
 */
class PostService
{
    use Caching;

    public function __construct()
    {
        $this->setCachePrefix('posts');
    }

    /**
     * @param GetPostsRequest $request
     * @return JsonResponse
     */
    public function getPosts(GetPostsRequest $request): JsonResponse
    {
//        $cacheKey = 'latest';
//
//        if ($this->hasCachedData($cacheKey) && !$request->has('page')) {
//            $posts = $this->get($cacheKey);
//        } else {
//            $posts = PostCollectionResource::collection(
//                Post::orderBy('created_at', 'desc')->paginate(20)
//            );
//
//            $posts = $posts->response()->getData();
//
//            if (!$request->has('page')) {
//                $this->set($cacheKey, $posts, false);
//            }
//        }
//
//        return response()->json($posts);

        $posts = PostCollectionResource::collection(
            Post::orderBy('created_at', 'desc')->paginate(20)
        )->response()->getData();

        return response()->json($posts);
    }

    /**
     * @param GetPostRequest $request
     * @return JsonResponse
     */
    public function getPost(GetPostRequest $request): JsonResponse
    {
        $post = Post::find($request->id);

        return response()->json($post);
    }

    /**
     * @param LikePostRequest $request
     * @return JsonResponse
     */
    public function likePost(LikePostRequest $request): JsonResponse
    {
        $like = PostLike::where('post_id', $request->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            PostLike::create([
                'post_id' => $request->id,
                'user_id' => auth()->id(),
            ]);

            PostDislike::where('post_id', $request->id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $post = Post::find($request->id);

        event(new \App\Events\Posts\PostLiked($post));

        return response()->json($post);
    }

    /**
     * @param DislikePostRequest $request
     * @return JsonResponse
     */
    public function dislikePost(DislikePostRequest $request): JsonResponse
    {
        $dislike = PostDislike::where('post_id', $request->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($dislike) {
            $dislike->delete();
        } else {
            PostDislike::create([
                'post_id' => $request->id,
                'user_id' => auth()->id(),
            ]);

            PostLike::where('post_id', $request->id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $post = Post::find($request->id);

        event(new \App\Events\Posts\PostDisliked($post));

        return response()->json($post);
    }

    /**
     * @param LikeCommentRequest $request
     * @return JsonResponse
     */
    public function likeComment(LikeCommentRequest $request): JsonResponse
    {
        $like = PostCommentLike::where('post_comment_id', $request->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            PostCommentLike::create([
                'post_comment_id' => $request->id,
                'user_id' => auth()->id(),
            ]);

            PostCommentDislike::where('post_comment_id', $request->id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $comment = PostComment::with([
            'replies',
            'replies.user',
            'likes',
            'likes.user',
            'dislikes',
            'dislikes.user'
        ])->find($request->id);

        $post = Post::find($comment->post_id);

        event(new \App\Events\Posts\PostUpdated($post));

        return response()->json($comment);
    }

    /**
     * @param DislikeCommentRequest $request
     * @return JsonResponse
     */
    public function dislikeComment(DislikeCommentRequest $request): JsonResponse
    {
        $like = PostCommentDislike::where('post_comment_id', $request->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            PostCommentDislike::create([
                'post_comment_id' => $request->id,
                'user_id' => auth()->id(),
            ]);

            PostCommentLike::where('post_comment_id', $request->id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $comment = PostComment::with([
            'replies',
            'replies.user',
            'likes',
            'dislikes',
            'dislikes.user'
        ])->find($request->id);

        $post = Post::find($comment->post_id);

        event(new \App\Events\Posts\PostUpdated($post));

        return response()->json($comment);
    }

    /**
     * @param CreatePostRequest $request
     * @return NewPostCollectionResource
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create(CreatePostRequest $request): NewPostCollectionResource
    {
        $sanitizationService = new SanitizationService();
        $clean_html = $sanitizationService->sanitize($request->get('content'));

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $clean_html,
            'mentions' => $request->get('mentions') ?? [],
            'hashtags' => $request->get('hashtags') ?? [],
        ]);

        $images = $request->file('images') ?? [];
        $audios = $request->file('audios') ?? [];

        foreach ($images as $image) {
            $asset = $post->assets()->create([
                'type' => Asset::POST_IMAGE,
                'path' => sha1(Str::random(16))
            ]);

            Storage::disk(Asset::POST_IMAGE)->put($asset->path, $image->get());
        }

        foreach ($audios as $audio) {
            $asset = $post->assets()->create([
                'type' => Asset::POST_AUDIO,
                'path' => sha1(Str::random(16))
            ]);

            Storage::disk('post-audios')->put($asset->path, $audio->get());
        }

        PostAddedJob::dispatch($post)->onQueue('high');

        $post->load(['user', 'assets']);

        $posts = Post::orderBy('created_at', 'desc')
            ->paginate(20);

        $posts->prepend($post);

        $this->set('latest', $posts);

        return new NewPostCollectionResource($post);
    }

    /**
     * @param DeletePostRequest $request
     * @return JsonResponse
     */
    public function delete(DeletePostRequest $request): JsonResponse
    {
        $post = Post::where('_id', $request->id)->first();

        if ($post->user->id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'unauthorized' => ['You cannot delete this post']
                ]
            ], 422);
        }

        PostDeletedJob::dispatch($post->_id, $post->user()->first())->onQueue('high');

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    /**
     * @param CreatePostCommentRequest $request
     * @return JsonResponse
     */
    public function createComment(CreatePostCommentRequest $request): JsonResponse
    {
        $post = Post::find($request->post_id);
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $postComment = $post->comments()
            ->create($data);
        $comment = PostComment::with([
            'replies',
            'replies.user',
            'likes',
            'likes.user',
            'dislikes',
            'dislikes.user'
        ])->find($postComment->id);

        $post = Post::find($request->post_id);

        event(new \App\Events\Posts\PostUpdated($post));

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }

    /**
     * @param DeletePostCommentRequest $request
     * @return JsonResponse
     */
    public function deleteComment(DeletePostCommentRequest $request): JsonResponse
    {
        $comment = PostComment::find($request->id);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'unauthorized' => ['You cannot delete this comment']
                ]
            ], 422);
        }

        $post = Post::find($comment->post_id);

        event(new \App\Events\Posts\PostUpdated($post));

        $comment->replies()->delete();
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
