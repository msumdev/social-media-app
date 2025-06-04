<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthenticationController extends Controller
{
    public function __construct(private readonly AuthenticationService $authenticationService) {}

    public function render(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/Login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        auth()->logout();

        return redirect()->route('login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        return $this->authenticationService->authenticate($request);
    }
}
