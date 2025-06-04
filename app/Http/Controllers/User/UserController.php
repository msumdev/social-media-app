<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function render(GetUserRequest $request): UserResource
    {
        return new UserResource($request->user());
    }
}
