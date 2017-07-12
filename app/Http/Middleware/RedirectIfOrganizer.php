<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfOrganizer {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->isAdmin() || Auth::user()->isOrganizer()) {
                return $next($request);
            } else {
                return back();
            }
        }
        return back();
    }

}
