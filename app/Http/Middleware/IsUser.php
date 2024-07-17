<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role_id == 2) {
            if(auth()->user()->mobile_verified){
                return $next($request);    
            }
            return redirect('/mobileverification');            
        }

        return redirect('/signin')->with('error', "You don't have admin access.");
    }
}
