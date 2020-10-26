<?php

namespace Modules\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!in_array($request->user()->userRole->role, $roles)) {
            throw new AuthorizationException();
        }
        return $next($request);
    }
}
