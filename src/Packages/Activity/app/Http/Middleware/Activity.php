<?php

namespace {{App\}}Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Activity
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
        activity('Standard User Action');

        return $next($request);
    }
}
