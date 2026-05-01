<?php

use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureAnyRole;
use App\Http\Middleware\EnsureParticipantRole;
use App\Http\Middleware\EnsurePasswordChange;
use App\Http\Middleware\LogUserActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.role' => EnsureAdminRole::class,
            'role.any' => EnsureAnyRole::class,
            'participant.role' => EnsureParticipantRole::class,
            'password.change' => EnsurePasswordChange::class,
            'activity.log' => LogUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
