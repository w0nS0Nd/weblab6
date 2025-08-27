<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureKeycloakRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:RoleA,RoleB')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $resource = config('keycloak.allowed_resources');
        if (!is_array($roles) || empty($roles)) {
            return response()->json(['message' => 'Role not specified'], 403);
        }

        foreach ($roles as $role) {
            if (\Auth::hasRole($resource, $role)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Forbidden: missing role', 'required_any_of' => $roles], 403);
    }
}
