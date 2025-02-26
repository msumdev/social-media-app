<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Services\Authentication\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegistrationConfirmationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrationService)
    {

    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Inertia\Response
     */
    public function render(Request $request): RedirectResponse | Response
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/RegistrationConfirmation');
    }

    /**
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function confirm(Request $request, $token): Response
    {
        return $this->registrationService->confirm($request, $token);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function resend(Request $request, $id): Response
    {
        return $this->registrationService->resend($request, $id);
    }
}
