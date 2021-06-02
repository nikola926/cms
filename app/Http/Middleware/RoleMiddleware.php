<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{

    public function handle($request, Closure $next, $role, $permission = null)
    {

        if(!auth()->user()->hasRole($role)) {

            abort(401);

        }

        if($permission !== null && !auth()->user()->can($permission)) {

            abort(403);
        }

        return $next($request);

    }
}
