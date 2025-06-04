<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationPrivacyPolicyController extends Controller
{
    /**
     * @return RedirectResponse|\Inertia\Response
     */
    public function render(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/RegistrationPrivacyPolicy');
    }
}
