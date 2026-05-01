<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationReadController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('participant')) {
        return redirect()->route('participant.dashboard');
    }

    if (
        $user->hasRole('super-admin') ||
        $user->hasRole('attendance-officer') ||
        $user->hasRole('programme-coordinator')
    ) {
        return redirect()->route('admin.dashboard');
    }

    abort(403, 'No dashboard is assigned to your account.');
});

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('participant')) {
        return redirect()->route('participant.dashboard');
    }

    if (
        $user->hasRole('super-admin') ||
        $user->hasRole('attendance-officer') ||
        $user->hasRole('programme-coordinator')
    ) {
        return redirect()->route('admin.dashboard');
    }

    abort(403, 'No dashboard is assigned to your account.');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/force-password-change', [ForcePasswordChangeController::class, 'edit'])->name('password.force.change');
    Route::post('/force-password-change', [ForcePasswordChangeController::class, 'update'])->name('password.force.update');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/participant.php';

Route::middleware(['auth'])->get('/notifications/{notificationId}/open', [NotificationReadController::class, 'markAndRedirect'])->name('notifications.open');

Route::middleware(['auth'])->post('/notifications/mark-all-read', [NotificationReadController::class, 'markAllRead'])->name('notifications.mark-all-read');
