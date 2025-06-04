<?php

namespace App\Services\Authentication;

use App\Http\Requests\Authentication\RegistrationRequest;
use App\Jobs\Auth\NewUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class RegistrationService
 */
class RegistrationService
{
    public function register(RegistrationRequest $request): RedirectResponse
    {
        $user = User::create($request->only([
            'username',
            'email',
            'date_of_birth',
            'first_name',
            'last_name',
            'country_id',
            'city_id',
            'gender_id',
            'sexuality_id',
            'sex_id',
        ]));

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $interests = $request->input('interests');

        foreach ($interests as $interest) {
            $user->interests()->create([
                'interest_type_id' => $interest,
            ]);
        }

        NewUser::dispatch($user->id)->onQueue('high');

        Session::flash('success', 'I\'ve sent you a confirmation email. Go ahead and click the link in there to get access!');

        return redirect()->route('login');
    }

    public function confirm($token): RedirectResponse
    {
        $user = User::where('token', $token)->first();

        if (! $user) {
            Session::flash('error', 'I can\'t find your account! Contact support for more assistance.');

            return redirect()->route('login');
        }

        $user->token = null;
        $user->registered = true;
        $user->save();

        Session::flash('success', 'Your account has been confirmed. You can now log in.');

        return redirect()->route('login');
    }

    public function resend(Request $request, $id): Response
    {
        $user = User::find($id);

        if (! $user) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'error' => 'That user does not exist.',
            ]);
        }

        if ($user->registered) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'error' => 'That user is already registered.',
            ]);
        }

        if ($user->last_registration_email_sent_at && Carbon::parse($user->last_registration_email_sent_at)->diffInMinutes(now()) < 2) {
            return Inertia::render('Auth/RegistrationConfirmation', [
                'user' => $user,
                'error' => 'You must wait at least 2 minutes before resending the confirmation email',
            ]);
        }

        NewUser::dispatch($user->id)->onQueue('high');

        return Inertia::render('Auth/RegistrationConfirmation', [
            'user' => $user,
            'success' => 'A new confirmation email has been sent.',
        ]);
    }
}
