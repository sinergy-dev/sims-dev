<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ManagerStaffMiddleware
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
        if ( Auth::check() && Auth::User()->id_position == 'MANAGER' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'STAFF' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'DIRECTOR' || Auth::check() && Auth::User()->id_position == 'OPERATION DIRECTOR' )
        {
            return $next($request);
        } 
        else if ( Auth::check() && Auth::User()->id_position == 'ENGINEER MANAGER' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'ENGINEER STAFF' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'ADMIN' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'EXPERT SALES' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'EXPERT ENGINEER' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'STAFF GA' )
        {
            return $next($request);
        }
        else if ( Auth::check() && Auth::User()->id_position == 'PM' )
        {
            return $next($request);
        }
        else if (Auth::check() && Auth::User()->id_position == 'PROCUREMENT' && Auth::User()->id_territory == 'OPERATION')
        {
            return $next($request);
        }
        return redirect('/');
    }
}
