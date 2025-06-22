<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckRole.php
    // app/Http/Middleware/CheckRole.php
 public function handle(Request $request, Closure $next, string $role = null)
    {
        // If no role is specified, just continue
        if (!$role) {
            return $next($request);
        }

        // If user is not authenticated, redirect to login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        //if the user roles_id = 1, they are an admin
        if ($request->user()->roles_id == 1) {
            // If the user is an admin, allow access to the route
            return $next($request);
        }

        // For non-admin users trying to access admin routes
        return redirect()->route('dashboard')->with('error', 'You do not have the required privileges.');
    }
}
