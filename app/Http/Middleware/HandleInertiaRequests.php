<?php

namespace App\Http\Middleware;

use App\Facades\Authentication\JWTServiceFacade;
use App\Models\Asset;
use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
            'token' => fn () => auth()->user()->jwt_token ?? null,
            'id' => fn () => auth()->id()
        ]);
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
//        if (env('APP_ENV') === 'local') {
//            $user = User::where('email', 'admin@test.com')->first();
//
//            if (!auth()->check() && $user) {
//                Auth::loginUsingId($user->id, true);
//            }
//        }

        if (auth()->check()) {
            $user = auth()->user();

            if (!$user) {
                auth()->logout();
            }

            if ($request->url() != route('login.render') && !$user->registered) {
                Session::flash('error', 'Please verify your email address to continue');

                auth()->logout();
            }

            if ($request->url() != route('login.render') && $user->banned) {
                Session::flash('error', 'Your account has been banned');

                auth()->logout();
            }
        } else {
            if ($request->url() != route('login.render') && $request->url() != route('home')) {
                Session::flash('error', 'You must be logged in to access this page');

                return redirect()->route('login.render');
            }
        }

        return parent::handle($request, $next);
    }
}
