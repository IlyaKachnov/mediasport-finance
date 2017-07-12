<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfManager {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $user = $request->user();
        $gyms = $user->selected_gyms;
        if (Auth::guard($guard)->check()) {
            if ($user->isManager()) {
                if ($request->is('gyms/*') && !in_array($request->segment(2), $gyms)) {

                    return back();
                }

                return $next($request);
            } elseif ($user->isAdmin()|| $user->isOrganizer()) {
                return $next($request);
            } else {
                return back();
            }
        }
    }

}
