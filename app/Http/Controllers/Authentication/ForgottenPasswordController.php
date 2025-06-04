<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Services\Authentication\ForgottenPasswordService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ForgottenPasswordController extends Controller
{
    public function __construct(private readonly ForgottenPasswordService $forgottenPasswordService) {}

    public function render(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/ForgottenPassword');
    }

    public function store(ResetPasswordRequest $request): Response
    {
        $email = $request->input('email');
        $dateOfBirth = $request->input('date_of_birth');

        return $this->forgottenPasswordService->store($email, $dateOfBirth);
    }
}
