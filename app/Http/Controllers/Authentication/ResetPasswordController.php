<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\SaveNewPasswordRequest;
use App\Models\User\User;
use App\Services\Authentication\ForgottenPasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    public function __construct(private readonly ForgottenPasswordService $forgottenPasswordService) {}

    /**
     * @return RedirectResponse|Response
     */
    public function render(Request $request, $token)
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        $user = User::where('password_reset_token', $token)->first();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->password_reset_token_expires_at < now()) {
            session()->flash('error', 'Uh oh! This link is no longer valid. I\'m gonna need you to request a new password again!');

            return redirect()->route('login');
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(SaveNewPasswordRequest $request, $token): Response|RedirectResponse
    {
        $newPassword = $request->input('password');

        return $this->forgottenPasswordService->save($newPassword, $token);
    }
}
