<?php

namespace App\Providers;

use App\Models\AttendanceCheckpoint;
use App\Models\AttendanceDailySummary;
use App\Models\AttendanceFlag;
use App\Models\AttendanceRecord;
use App\Models\Batch;
use App\Models\CertificateEligibility;
use App\Models\Course;
use App\Models\Participant;
use App\Models\TrainingSession;
use App\Models\Venue;
use App\Observers\GenericActivityObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Course::observe(GenericActivityObserver::class);
        Venue::observe(GenericActivityObserver::class);
        Batch::observe(GenericActivityObserver::class);
        Participant::observe(GenericActivityObserver::class);
        TrainingSession::observe(GenericActivityObserver::class);
        AttendanceCheckpoint::observe(GenericActivityObserver::class);
        AttendanceRecord::observe(GenericActivityObserver::class);
        AttendanceDailySummary::observe(GenericActivityObserver::class);
        AttendanceFlag::observe(GenericActivityObserver::class);
        CertificateEligibility::observe(GenericActivityObserver::class);
    }
}
