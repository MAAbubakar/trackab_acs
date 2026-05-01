<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Change these field names if your users table uses a different column.
        $mustChange =
            (bool) ($user->must_change_password ?? false) ||
            (bool) ($user->force_password_change ?? false) ||
            (bool) ($user->password_change_required ?? false);

        if (
            $mustChange &&
            !$request->routeIs('password.force.change') &&
            !$request->routeIs('password.force.update') &&
            !$request->routeIs('logout')
        ) {
            return redirect()->route('password.force.change');
        }

        return $next($request);
    }
}
