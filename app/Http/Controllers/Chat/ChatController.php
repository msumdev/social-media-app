<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ChatIndexRequest;
use App\Http\Requests\Chat\Rooms\CreateChatRequest;
use App\Services\Chat\ChatService;
use Inertia\Response;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chatService)
    {

    }

    /**
     * @param ChatIndexRequest $request
     * @return \Inertia\Response
     */
    public function index(ChatIndexRequest $request): Response
    {
        return $this->chatService->index($request);
    }

    /**
     * @param CreateChatRequest $request
     * @return mixed
     */
    public function create(CreateChatRequest $request)
    {
        return $this->chatService->create($request);
    }
}
