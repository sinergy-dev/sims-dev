<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class HRMiddleware
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
        if ( Auth::check() && Auth::User()->id_position == 'HR MANAGER' )
        {
            return $next($request);
        } 
        elseif ( Auth::User()->id_position == 'DIRECTOR' ) 
        {
            return $next($request);  
        }
        elseif ( Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' ) 
        {
            return $next($request);  
        } 
        elseif ( Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'MSM' ) 
        {
            return $next($request);  
        }

        return redirect('/');

    }
}
