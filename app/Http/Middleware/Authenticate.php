<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\URL;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {

            return $this->getRedirectRoute();
        }
    }

    /**
     * Get the redirection url after session out
     *
     * @param null $middleware
     * @return string
     */
    protected function getRedirectRoute($middleware = null)
    {
        $middleware = empty($middleware) ? app('router')->getRoutes()->match(app('request')->create(URL::previous()))->gatherMiddleware() : $middleware;

        if (in_array('is_admin',$middleware)) {

            return route('admin-login');

        } else {

            return route('signin');

        }
    }
}

