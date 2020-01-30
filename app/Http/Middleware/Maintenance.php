<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Maintenance
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
        if ( Auth::check() && Auth::User()->email == 'tech@sinergy.co.id' )
        {
            return $next($request);
        }

        return redirect('/sorry_this_page_is_under_maintenance');
    }
}
