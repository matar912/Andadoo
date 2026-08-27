<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Bloque l'accès si l'utilisateur connecté n'a pas le rôle attendu.
     * Utilisation : ->middleware('role:admin') ou ->middleware('role:client').
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        abort_if(! $request->user() || $request->user()->role !== $role, 403);

        return $next($request);
    }
}
