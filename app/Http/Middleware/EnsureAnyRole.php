<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAnyRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        if (count($roles) === 0) {
            abort(403, 'No roles supplied.');
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Officer Hard Lockdown
        |--------------------------------------------------------------------------
        | Attendance officers are allowed to access only dashboard, sessions,
        | checkpoints/scanner/monitoring, attendance records, daily summaries,
        | attendance flags, and notifications.
        |--------------------------------------------------------------------------
        */
        if (
            $user->hasRole('attendance-officer') &&
            ! $user->hasAnyRole(['super-admin', 'programme-coordinator', 'm&e-officer'])
        ) {
            $allowedRouteNames = [
                'admin.dashboard',

                'admin.sessions.index',
                'admin.sessions.show',

                'admin.checkpoints.index',
                'admin.checkpoints.live',
                'admin.checkpoints.scanner',
                'admin.checkpoints.scan-submit',
                'admin.checkpoints.monitor',
                'admin.checkpoints.monitor.snapshot',

                'admin.attendance-records.index',
                'admin.daily-summaries.index',
                'admin.attendance-flags.index',

                'admin.notifications.index',
                'admin.notifications.mark-read',
            ];

            $routeName = $request->route()?->getName();

            if (! $routeName || ! in_array($routeName, $allowedRouteNames, true)) {
                abort(403, 'Access denied for your role.');
            }
        }

        foreach ($roles as $role) {
            if ($user->hasRole(trim($role))) {
                return $next($request);
            }
        }

        abort(403, 'Access denied for your role.');
    }
}
