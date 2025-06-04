<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Services\Authentication\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RegistrationConfirmationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrationService) {}

    public function render(Request $request, $token): RedirectResponse
    {
        return $this->registrationService->confirm($token);
    }

    public function resend(Request $request, $id): Response
    {
        return $this->registrationService->resend($request, $id);
    }
}
