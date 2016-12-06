<?php

namespace {{App\}}Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Permissions
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $requestPermissionCollection)
    {
        $requestPermissions = explode('|', $requestPermissionCollection);

        foreach ($request->user()->roles as $role) {
            foreach ($role->permissions as $permission) {
                $userPermissionCollection[] = $permission;
            }
        }

        foreach ($requestPermissions as $accessPermission) {
            if (in_array($accessPermission, $userPermissionCollection)) {
                return $next($request);
            }
        }

        return response()->view('errors.401', [], 401);
    }
}
