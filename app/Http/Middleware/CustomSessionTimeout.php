<?php

namespace App\Http\Middleware;

use Closure;

class CustomSessionTimeout
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
        if (auth()->user()->id_position == 'MANAGER' && auth()->user()->id_division == 'TECHNICAL' && auth()->user()->id_territory == 'OPERATION') {
            $sessionConfig =  1440;
        }else{
            $sessionConfig =  120;
        }

        config(['session.lifetime' => $sessionConfig]);

        return $next($request);
    }
}
