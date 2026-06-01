<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participant\ParticipantDashboardController;
use App\Http\Controllers\Participant\ParticipantScanController;
use App\Http\Controllers\Participant\ParticipantSummaryController;
use App\Http\Controllers\Participant\ParticipantEligibilityController;
use App\Http\Controllers\Participant\ParticipantProfileController;
use App\Http\Controllers\Participant\EvaluationController;
use App\Http\Controllers\Participant\SiwesLetterController;
use App\Http\Controllers\Participant\ProfileCorrectionRequestController;
use App\Http\Controllers\Participant\ParticipantNotificationController;
use App\Http\Controllers\Participant\ParticipantCorrectionHistoryController;
use App\Http\Controllers\Participant\ParticipantQrController;

Route::middleware(['auth', 'password.change', 'participant.role', 'activity.log'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');

        Route::match(['get', 'post'], '/scan', [ParticipantScanController::class, 'index'])->name('scan');

        Route::get('/summaries', [ParticipantSummaryController::class, 'index'])->name('summaries');

        Route::get('/eligibility', [ParticipantEligibilityController::class, 'index'])->name('eligibility');

        Route::get('/profile', [ParticipantProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [ParticipantProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ParticipantProfileController::class, 'update'])->name('profile.update');
        Route::get('/my-qr', [ParticipantQrController::class, 'show'])->name('qr.show');
        Route::get('/profile/correction-request', [ProfileCorrectionRequestController::class, 'create'])->name('profile-corrections.create');
        Route::post('/profile/correction-request', [ProfileCorrectionRequestController::class, 'store'])->name('profile-corrections.store');
        Route::get('/profile/corrections/history', [ParticipantCorrectionHistoryController::class, 'index'])->name('profile-corrections.history');
        Route::get('/notifications', [ParticipantNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/evaluation', [EvaluationController::class, 'show'])->name('evaluation.show');
        Route::post('/evaluation', [EvaluationController::class, 'submit'])->name('evaluation.submit');
        Route::get('/siwes-letter', [SiwesLetterController::class, 'index'])->name('siwes.index');
        Route::get('/siwes-letter/view', [SiwesLetterController::class, 'show'])->name('siwes.show');
        Route::get('/siwes-letter/download', [SiwesLetterController::class, 'download'])->name('siwes.download');
    });


