<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * @return RedirectResponse|\Inertia\Response
     */
    public function render(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        return Inertia::render('Home');
    }
}
