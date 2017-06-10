<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return '/admin/dashboard/index';
            } else if ($user->hasRole('staff') || $user->hasRole('lawyer') || $user->hasRole('logistics') || $user->hasRole('billing')) {
                return '/hhq/dashboard/index';
            } else if ($user->hasRole('client')) {
                return '/overview/index';
            }
        }

        return $next($request);
    }
}
