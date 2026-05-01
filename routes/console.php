<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('attendance:close-expired-checkpoints')->everyMinute();
Schedule::command('attendance:compute-daily-summaries')->hourly();
Schedule::command('attendance:evaluate-certificate-eligibility')->twiceDaily(12, 18);
