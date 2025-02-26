<?php

namespace App\Services\Authentication;

use App\Facades\Authentication\JWTServiceFacade;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Jobs\Auth\NewUser;
use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class AuthenticationService
 * @package App\Services\Authentication
 */
class AuthenticationService
{
    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            RateLimiter::hit(request()->ip());

            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        RateLimiter::clear(request()->ip());

        $request->session()->regenerate();

        $user = auth()->user();

        if (!$user->jwt_token_expires_at) {
            $token = JWTServiceFacade::generate();

            $user->jwt_token = base64_encode($token['token']);
            $user->jwt_token_expires_at = $token['expires_at'];
        }

        if ($user->jwt_token_expires_at < now()) {
            $token = JWTServiceFacade::generate();

            $user->jwt_token = base64_encode($token['token']);
            $user->jwt_token_expires_at = $token['expires_at'];
        }

        $user->save();

        return redirect()->route('home');
    }

    /**
     * @param $request
     * @return void
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited($request): void
    {
        if (! RateLimiter::tooManyAttempts(request()->ip(), 6)) {
            return;
        }

        $seconds = RateLimiter::availableIn(request()->ip());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
