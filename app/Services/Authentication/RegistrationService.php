<?php

namespace App\Services\Authentication;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Jobs\Auth\NewUser;
use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class RegistrationService
 * @package App\Services\Authentication
 */
class RegistrationService
{
    /**
     * @param RegistrationRequest $request
     * @return Response
     */
    public function register(RegistrationRequest $request): Response
    {
        $user = User::create($request->validated());

        Log::info('New user registered: ' . $user->email);

        NewUser::dispatch($user->id)->onQueue('high');

        return Inertia::render('Auth/RegistrationConfirmation', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function confirm(Request $request, $token): Response
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'error' => 'That token is invalid.'
            ]);
        }

        $user->token = null;
        $user->registered = true;
        $user->save();

        return Inertia::render('Auth/Login', [
            'success' => 'Your account has been confirmed. You can now log in.'
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function resend(Request $request, $id): Response
    {
        $user = User::find($id);

        if (!$user) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'error' => 'That user does not exist.'
            ]);
        }

        if ($user->registered) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'error' => 'That user is already registered.'
            ]);
        }

        if ($user->last_registration_email_sent_at && Carbon::parse($user->last_registration_email_sent_at)->diffInMinutes(now()) < 2) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'user' => $user,
                'error' => "You must wait at least 2 minutes before resending the confirmation email"
            ]);
        }

        NewUser::dispatch($user->id)->onQueue('high');

        return Inertia::render('Auth/RegistrationConfirmation', [
            'user' => $user,
            'success' => 'A new confirmation email has been sent.'
        ]);
    }
}
