<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackendAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->userType !== User::ROLE_ADMIN) {
                Auth::logout();

                //session()->flash('alert-danger', 'These credentials do not match our records.');
                return redirect()->guest('reset/success');
            }
            return $next($request);

        }
        return redirect()->guest('login');
    }
}
