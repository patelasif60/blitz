<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3 || auth()->user()->role_id == 5 || \Auth::user()->hasRole('jne') || \Auth::user()->hasRole('finance')) {

            if(auth()->user()->role_id == 3)
            {
                if(auth()->user()->mobile_verified){
                    return $next($request);    
                }
                return redirect('admin/changemobile');
            }
            return $next($request);
        }

        return redirect('/admin/login')->with('error', "You don't have admin access.");
    }
}
