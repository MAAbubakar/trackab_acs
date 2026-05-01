<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function logRequest(Request $request, string $action, ?array $metadata = null): void
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'participant_id' => $user?->participant?->id,
            'action' => $action,
            'subject_type' => null,
            'subject_id' => null,
            'route_name' => $request->route()?->getName(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    public function logModel(string $action, Model $model, ?array $metadata = null): void
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'participant_id' => $user?->participant?->id,
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'route_name' => request()?->route()?->getName(),
            'method' => request()?->method(),
            'url' => request()?->fullUrl(),
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
