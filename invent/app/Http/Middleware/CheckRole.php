<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRoleId = $request->user()->roles_id;

        // Mapping role name to ID
        $roleMap = [
            'admin' => 1,
            'user' => 2,
            'superadmin' => 3,
        ];

        $allowedRoleIds = collect($roles)
            ->map(fn($role) => $roleMap[$role] ?? null)
            ->filter()
            ->toArray();

        if (in_array($userRoleId, $allowedRoleIds)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'You do not have the required privileges.');
    }
}
