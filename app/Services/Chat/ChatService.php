<?php

namespace App\Services\Chat;

use App\Http\Requests\Chat\ChatIndexRequest;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ChatService
 */
class ChatService
{
    public function index(ChatIndexRequest $request): Response
    {
        return Inertia::render('Chat/Index');
    }
}
