<?php

namespace App\Http\Controllers\User\Chat;

use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**#
     * Show the messages page.
     */
    public function index(): \Illuminate\View\View
    {
        return view('chat.index');
    }
}
