<?php

namespace App\Services\Chat;

use App\Http\Requests\Chat\ChatIndexRequest;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ChatService
 * @package App\Services\Chat
 */
class ChatService
{
    /**
     * @param ChatIndexRequest $request
     * @return Response
     */
    public function index(ChatIndexRequest $request): Response
    {
        return Inertia::render('Chat/Index');
    }
}
