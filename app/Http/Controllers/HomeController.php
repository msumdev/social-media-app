<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetProfileRequest;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|\Inertia\Response
     */
    public function render(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login.render');
        }

        return Inertia::render('Home');
    }

    /**
     * @param GetProfileRequest $request
     * @return View
     */
    public function profile(GetProfileRequest $request): View
    {
        $user = User::where('username', $request->username)->firstOrFail();

        return view('profile', [
            'user' => $user
        ]);
    }
}
