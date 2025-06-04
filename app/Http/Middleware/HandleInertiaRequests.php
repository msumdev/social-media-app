<?php

namespace App\Http\Middleware;

use App\Models\Asset;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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
        //        dd(
        //            array_values(
        //                Route::getCurrentRoute()->middleware()
        //            )
        //        );

        return array_merge(parent::share($request), [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
            'token' => fn () => auth()->user()->jwt_token ?? null,
            'requires_auth' => in_array('auth', array_values(Route::getCurrentRoute()->middleware())),
            'id' => fn () => auth()->id(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($request->url() != route('login') && ! $user->registered) {
                Session::flash('error', 'Please verify your email address to continue');

                auth()->logout();
            }

            if ($request->url() != route('login') && $user->banned) {
                Session::flash('error', 'Your account has been banned');

                auth()->logout();
            }
        }

        return parent::handle($request, $next);
    }
}
