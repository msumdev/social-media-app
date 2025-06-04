<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\CreatePostCommentRequest;
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
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\PostCommentDislike;
use App\Models\Posts\PostCommentReaction;
use App\Models\Posts\PostDislike;
use App\Models\Posts\PostLike;
use App\Services\SanitizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class PostService
 */
class PostService
{
    public function getPosts(GetPostsRequest $request): AnonymousResourceCollection
    {
        $posts = Post::with([
                'postImageAssets',
                'postAudioAssets',
                'likes',
                'user.gender',
                'user.sex',
                'user.sexuality',
                'user.city',
                'user.country',
                'user.followers',
                'user.profilePicture',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return PostCollectionResource::collection($posts);
    }

    public function getPost(GetPostRequest $request): JsonResponse
    {
        $post = Post::find($request->id);

        return response()->json($post);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create(string $content, array $mentions, array $hashtags, array $images, array $audios): NewPostCollectionResource
    {
        $sanitizationService = new SanitizationService;
        $clean_html = $sanitizationService->sanitize($content);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $clean_html,
            'mentions' => $mentions,
            'hashtags' => $hashtags,
        ]);

        foreach ($images as $image) {
            $imageAsset = $post->postImageAssets()->create([
                'type' => Asset::POST_IMAGE,
                'path' => sha1(Str::random(16)),
            ]);

            Storage::disk(Asset::POST_IMAGE)->put(
                $imageAsset->path,
                $image['file']->get()
            );

            $imageAsset->url = Storage::disk(Asset::POST_IMAGE)->url($imageAsset->path);
            $imageAsset->save();
        }

        foreach ($audios as $audio) {
            $audioAsset = $post->assets()->create([
                'type' => Asset::POST_AUDIO,
                'path' => sha1(Str::random(16)),
            ]);

            Storage::disk('post-audio')->put($audioAsset->path, $audio['file']->get());

            $audioAsset->url = Storage::disk(Asset::POST_AUDIO)->url($audioAsset->path);
            $audioAsset->save();
        }

        PostAddedJob::dispatch($post)->onQueue('high');

        $posts = Post::orderBy('created_at', 'desc')
            ->paginate(20);

        $posts->prepend($post);

        return new NewPostCollectionResource($post);
    }

    public function delete(Post $post): JsonResponse
    {
        PostDeletedJob::dispatch($post->_id, $post->user()->first())->onQueue('high');

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }
}
