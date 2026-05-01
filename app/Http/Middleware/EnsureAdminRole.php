<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Roles allowed to enter the admin area at all.
     */
    protected array $allowedRoles = [
        'super-admin',
        'programme-coordinator',
        'attendance-officer',
        'm&e-officer',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Access denied. Admin area only.');
        }

        foreach ($this->allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Access denied. Admin area only.');
    }
}
