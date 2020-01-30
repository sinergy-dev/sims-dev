<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SIP
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
        // if ( Auth::check() && Auth::User()->id_company == '1' || Auth::User()->email == 'putri@sinergy.co.id') {
          
            return $next($request);

        // } else {

        //     Auth::logout();
        //     return redirect()->back()->with('message', 'You are not allowed to login in this application!');
            
        // }
    }
}
