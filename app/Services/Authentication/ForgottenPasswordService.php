<?php

namespace App\Services\Authentication;

use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Http\Requests\Authentication\SaveNewPasswordRequest;
use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

/**
 * Class ForgottenPasswordService
 * @package App\Services\Authentication
 */
class ForgottenPasswordService
{
    /**
     * @param ResetPasswordRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function store(ResetPasswordRequest $request): Response
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return Inertia::render('Auth/ForgottenPassword', [
                'error' => 'That email does not exist'
            ]);
        }

        if (Carbon::parse($request->date_of_birth)->format('Y-m-d') != $user->date_of_birth) {
            return Inertia::render('Auth/ForgottenPassword', [
                'error' => 'The date of birth does not match the one on file'
            ]);
        }

        ResetPassword::dispatch($user->id)->onQueue('high');

        return Inertia::render('Auth/ForgottenPassword', [
            'success' => 'A link to reset your password has been sent to your email'
        ]);
    }

    /**
     * @param SaveNewPasswordRequest $request
     * @param $token
     * @return Response|RedirectResponse
     * @throws ValidationException
     */
    public function save(SaveNewPasswordRequest $request, $token): Response|RedirectResponse
    {
        $user = User::where('password_reset_token', $token)->first();

        if (!$user) {
            return Inertia::render('Auth/ForgottenPassword', [
                'error' => 'That token does not exist'
            ]);
        }

        if ($user->password_reset_at && Carbon::parse($user->password_reset_at)->addHours(2)->lt(now())) {
            return Inertia::render('Auth/ForgottenPassword', [
                'error' => 'The password reset token has expired. Please request a new one.'
            ]);
        }

        $user->password = bcrypt($request->password);
        $user->password_reset_token = null;
        $user->password_reset_at = null;
        $user->save();

        return redirect()
            ->route('login.render')
            ->with('success', 'Your password has been reset. Please login');
    }
}
