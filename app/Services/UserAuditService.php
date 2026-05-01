<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAudit;

class UserAuditService
{
    public function log(User $user, string $action, ?string $notes = null, array $meta = []): void
    {
        UserAudit::create([
            'user_id' => $user->id,
            'acted_by_user_id' => auth()->id(),
            'action' => $action,
            'notes' => $notes,
            'meta' => $meta,
        ]);
    }
}
