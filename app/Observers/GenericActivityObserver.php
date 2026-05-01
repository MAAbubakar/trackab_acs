<?php

namespace App\Observers;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

class GenericActivityObserver
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function created(Model $model): void
    {
        $this->activityLogService->logModel('model.created', $model, [
            'attributes' => $model->getAttributes(),
        ]);
    }

    public function updated(Model $model): void
    {
        $this->activityLogService->logModel('model.updated', $model, [
            'changes' => $model->getChanges(),
        ]);
    }

    public function deleted(Model $model): void
    {
        $this->activityLogService->logModel('model.deleted', $model, [
            'attributes' => $model->getOriginal(),
        ]);
    }
}
