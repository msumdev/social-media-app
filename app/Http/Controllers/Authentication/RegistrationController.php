<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Services\Authentication\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegistrationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrationService)
    {

    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Inertia\Response
     */
    public function render(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/Registration');
    }

    /**
     * @param RegistrationRequest $request
     * @return Response
     */
    public function store(RegistrationRequest $request): Response
    {
        return $this->registrationService->register($request);
    }
}
