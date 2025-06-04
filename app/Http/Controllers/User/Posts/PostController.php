<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\CreatePostRequest;
use App\Http\Requests\User\Posts\DeletePostRequest;
use App\Http\Requests\User\Posts\GetPostsRequest;
use App\Models\Posts\Post;
use App\Services\User\Posts\PostCommentService;
use App\Services\User\Posts\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly PostService $postService,
        private readonly PostCommentService $postCommentService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user()->load('friends', 'friend_requests');

        return view('posts.index', [
            'user' => $user,
        ]);
    }

    public function all(GetPostsRequest $request): AnonymousResourceCollection
    {
        return $this->postService->getPosts($request);
    }

    public function create(CreatePostRequest $request): RedirectResponse
    {
        $this->postService->create(
            $request->get('html'),
            $request->get('mentions') ?? [],
            $request->get('hashtags') ?? [],
            $request->file('images') ?? [],
            $request->file('audios') ?? []
        );

        return to_route('home');
    }

    public function delete(DeletePostRequest $request): JsonResponse
    {
        $post = Post::where('_id', $request->id)->first();

        $this->authorize('delete', $post);

        return $this->postService->delete($post);
    }
}
