<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{

    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!auth()->user()){

            return response()->json(['message' => 'User not logged in!'], 401);

        }elseif(!auth()->user()->hasRole($role)) {

            return response()->json(['message' => 'Unauthorized'], 401);

        }

//        if($permission !== null && !auth()->user()->can($permission)) {
//
//            abort(403);
//        }

        return $next($request);

    }
}
