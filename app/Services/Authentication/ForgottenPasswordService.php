<?php

namespace App\Services\Authentication;

use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ForgottenPasswordService
 */
class ForgottenPasswordService
{
    public function store(string $email, string $dateOfBirth): Response
    {
        $user = User::where('email', $email)->first();
        $dateOfBirth = Carbon::parse($dateOfBirth)->format('Y-m-d');
        $userDateOfBirth = Carbon::parse($user->date_of_birth)->format('Y-m-d');

        if ($user && $dateOfBirth === $userDateOfBirth) {
            ResetPassword::dispatch($user->id)->onQueue('high');
        }

        return Inertia::render('Auth/ForgottenPassword', [
            'success' => 'If the information provided is correct, you will receive an email with instruction in resetting your password',
        ]);
    }

    public function save($newPassword, $token): Response|RedirectResponse
    {
        $user = User::where('password_reset_token', $token)->first();

        $user->password = bcrypt($newPassword);
        $user->password_reset_token = null;
        $user->password_reset_token_expires_at = null;
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Your password has been reset. Please login');
    }
}
