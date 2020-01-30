<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SalesMiddleware
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
        if ( Auth::check() && Auth::User()->id_division == 'SALES' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'DIRECTOR' )
        {
            return $next($request);
        }

        return redirect('/');
    }
}
