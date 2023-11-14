<?php

namespace App\Http\Middleware;

use Closure;

class UserSpecificSessionLifetime
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
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->nik === 1061184050) { // Replace 123 with the target user's ID
                config(['session.lifetime' => 1440]); // Set session lifetime for this user (in minutes)
            }
        }

        return $next($request);
    }
}
