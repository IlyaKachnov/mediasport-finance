<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class MustBeAdministrator {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard=null) {
        $user = $request->user();
         if (Auth::guard($guard)->check() && $user->isAdmin())
         {
            return $next($request);
        }
       return redirect('/dashboard');
    }

}
