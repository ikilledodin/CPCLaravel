<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        if(!Auth::check()) {
            return redirect('users/login');
        } else {
            $user = Auth::user();
            // +rodel. Activate Manager middleware. 
            // Comment this if/else condition statement above and uncomment the line below to deactivate Manager Middleware.
            //if($user->hasRole('HQSuperAdmin')) {
            //    return $next($request);
            //} else {
            //    return redirect('/');
            //}
            // +rodel. Deactivate Manager middleware. 
            // Uncomment if/else condition statement above and comment out the line below to activate Manager Middleware.
             return $next($request);
        }
    }
}
