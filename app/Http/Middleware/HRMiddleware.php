<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

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
        $getRole = DB::table('roles')->join('role_user','role_user.role_id','roles.id')->select('roles.name')->where('user_id',Auth::User()->nik)->first();

        if ( Auth::check() && Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'HR STAFF' || Auth::User()->id_position == 'STAFF HR')
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
        elseif ( Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'BCD' ) 
        {
            return $next($request);  
        } 
        elseif ( Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'FINANCE' ) 
        {
            return $next($request);  
        } 
        elseif ($getRole->name == 'HR Legal') {
            return $next($request);
        }

        return redirect('/');

    }
}
