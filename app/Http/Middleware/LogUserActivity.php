<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $this->activityLogService->logRequest($request, 'request.hit', [
                'status_code' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
