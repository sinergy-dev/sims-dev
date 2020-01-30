<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class DirectorMiddleware
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
        if ( Auth::check() && Auth::User()->id_position == 'DIRECTOR' )
        {
            return $next($request);
        }elseif ( Auth::check() && Auth::User()->id_position == 'MANAGER' && Auth::check() && Auth::User()->id_division == 'TECHNICAL' )
        {
            return $next($request);
        }

        return redirect('/');
    }
}
