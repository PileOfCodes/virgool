<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = explode('|', $role);
        abort_unless($request->user()->hasRole($roles), 403);
        return $next($request);
    }
}
