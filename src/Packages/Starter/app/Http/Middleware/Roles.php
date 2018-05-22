<?php

namespace {{App\}}Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        return response()->view('errors.401', [], 401);
    }
}
