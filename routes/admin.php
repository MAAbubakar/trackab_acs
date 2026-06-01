<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AttendanceCheckpointController;
use App\Http\Controllers\Admin\AttendanceDailySummaryController;
use App\Http\Controllers\Admin\AttendanceFlagController;
use App\Http\Controllers\Admin\AttendanceMonitorController;
use App\Http\Controllers\Admin\AttendanceRecordController;
use App\Http\Controllers\Admin\AutomationController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\BiodataExportController;
use App\Http\Controllers\Admin\CertificateEligibilityController;
use App\Http\Controllers\Admin\CertificateEligibilityAdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvaluationFormController;
use App\Http\Controllers\Admin\EvaluationQuestionController;
use App\Http\Controllers\Admin\EvaluationReminderController;
use App\Http\Controllers\Admin\EvaluationResponseController;
use App\Http\Controllers\Admin\MessageLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OfficerScannerController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\ProfileCorrectionRequestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiwesLetterController;
use App\Http\Controllers\Admin\TrainingSessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\VerificationReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'password.change', 'admin.role', 'activity.log'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('role.any:super-admin,programme-coordinator,attendance-officer,m&e-officer');

        /*
        |--------------------------------------------------------------------------
        | Shared read-only pages: Super Admin + Programme Coordinator + M&E
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role.any:super-admin,programme-coordinator,attendance-officer,m&e-officer'])->group(function () {

            Route::get('profile-corrections', [ProfileCorrectionRequestController::class, 'index'])
                ->name('profile-corrections.index');

            /*
            |--------------------------------------------------------------------------
            | Courses
            |--------------------------------------------------------------------------
            */
            Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
            Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
            Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->whereNumber('course')->name('courses.edit');
            Route::get('courses/{course}', [CourseController::class, 'show'])->whereNumber('course')->name('courses.show');

            /*
            |--------------------------------------------------------------------------
            | Venues
            |--------------------------------------------------------------------------
            */
            Route::get('venues', [VenueController::class, 'index'])->name('venues.index');
            Route::get('venues/create', [VenueController::class, 'create'])->name('venues.create');
            Route::get('venues/{venue}/edit', [VenueController::class, 'edit'])->whereNumber('venue')->name('venues.edit');
            Route::get('venues/{venue}', [VenueController::class, 'show'])->whereNumber('venue')->name('venues.show');

            /*
            |--------------------------------------------------------------------------
            | Batches
            |--------------------------------------------------------------------------
            */
            Route::get('batches', [BatchController::class, 'index'])->name('batches.index');
            Route::get('batches/create', [BatchController::class, 'create'])->name('batches.create');
            Route::get('batches/{batch}/edit', [BatchController::class, 'edit'])->whereNumber('batch')->name('batches.edit');
            Route::get('batches/{batch}/qr-cards', [BatchController::class, 'qrCards'])->whereNumber('batch')->name('batches.qr-cards');
            Route::get('batches/{batch}', [BatchController::class, 'show'])->whereNumber('batch')->name('batches.show');

            /*
            |--------------------------------------------------------------------------
            | Participants
            |--------------------------------------------------------------------------
            */
            Route::get('participants', [ParticipantController::class, 'index'])->name('participants.index');
            Route::get('participants-import', [ParticipantController::class, 'importForm'])->name('participants.import-form');
            Route::get('participants-import-template', [ParticipantController::class, 'downloadImportTemplate'])->name('participants.import-template');
            Route::get('participants/create', [ParticipantController::class, 'create'])->name('participants.create');
            Route::get('participants/{participant}/edit', [ParticipantController::class, 'edit'])->whereNumber('participant')->name('participants.edit');
            Route::get('participants/{participant}/qr-card', [ParticipantController::class, 'qrCard'])->whereNumber('participant')->name('participants.qr-card');
            Route::get('participants/{participant}', [ParticipantController::class, 'show'])->whereNumber('participant')->name('participants.show');

            /*
            |--------------------------------------------------------------------------
            | Sessions
            |--------------------------------------------------------------------------
            */
            Route::get('sessions', [TrainingSessionController::class, 'index'])->name('sessions.index');
            Route::get('sessions/create', [TrainingSessionController::class, 'create'])->name('sessions.create');
            Route::get('sessions/{session}/edit', [TrainingSessionController::class, 'edit'])->whereNumber('session')->name('sessions.edit');
            Route::get('sessions/{session}/checkpoints', [AttendanceCheckpointController::class, 'index'])->whereNumber('session')->name('checkpoints.index');
            Route::get('sessions/{session}', [TrainingSessionController::class, 'show'])->whereNumber('session')->name('sessions.show');

            /*
            |--------------------------------------------------------------------------
            | Checkpoints and monitoring
            |--------------------------------------------------------------------------
            */
            Route::get('checkpoints/live/{checkpoint}', [AttendanceCheckpointController::class, 'live'])->name('checkpoints.live');
            Route::get('checkpoints/{checkpoint}/scanner', [OfficerScannerController::class, 'show'])->name('checkpoints.scanner');
            Route::post('checkpoints/{checkpoint}/scan-submit', [OfficerScannerController::class, 'submit'])->name('checkpoints.scan-submit');
            Route::get('checkpoints/{checkpoint}/monitor', [AttendanceMonitorController::class, 'show'])->name('checkpoints.monitor');
            Route::get('checkpoints/{checkpoint}/monitor/snapshot', [AttendanceMonitorController::class, 'snapshot'])->name('checkpoints.monitor.snapshot');

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/completed-biodata', [BiodataExportController::class, 'index'])->name('reports.completed-biodata');
            Route::get('reports/completed-biodata/batch/{batch}/export', [BiodataExportController::class, 'exportBatch'])->name('reports.completed-biodata.export');

            Route::get('reports/participants', [ReportController::class, 'participants'])->name('reports.participants');
            Route::get('reports/participants/export/excel', [ReportController::class, 'participantsExcel'])->name('reports.participants.excel');
            Route::get('reports/participants/export/pdf', [ReportController::class, 'participantsPdf'])->name('reports.participants.pdf');

            Route::get('reports/sessions', [ReportController::class, 'sessions'])->name('reports.sessions');
            Route::get('reports/flags', [ReportController::class, 'flags'])->name('reports.flags');
            Route::get('reports/certificates', [ReportController::class, 'certificates'])->name('reports.certificates');
            Route::get('reports/certificates/export/excel', [ReportController::class, 'certificatesExcel'])->name('reports.certificates.excel');
            Route::get('reports/certificates/export/pdf', [ReportController::class, 'certificatesPdf'])->name('reports.certificates.pdf');

            Route::get('reports/evaluation-completion', [ReportController::class, 'evaluationCompletion'])->name('reports.evaluation-completion');
            Route::get('reports/evaluation-completion/export/excel', [ReportController::class, 'evaluationCompletionExportExcel'])->name('reports.evaluation-completion.export-excel');
            Route::get('reports/evaluation-completion/export/pdf', [ReportController::class, 'evaluationCompletionExportPdf'])->name('reports.evaluation-completion.export-pdf');
            Route::get('reports/evaluation-completion/batch/{batch}', [ReportController::class, 'evaluationCompletionBatchDetails'])->name('reports.evaluation-completion.batch-details');

            Route::get('reports/verification/dli-a2a', [VerificationReportController::class, 'index'])->name('reports.verification.dli-a2a');
            Route::get('reports/verification/dli-a2a/excel', [VerificationReportController::class, 'exportExcel'])->name('reports.verification.dli-a2a.excel');
            Route::get('reports/verification/dli-a2a/pdf', [VerificationReportController::class, 'exportPdf'])->name('reports.verification.dli-a2a.pdf');

            /*
            |--------------------------------------------------------------------------
            | Attendance compliance read-only
            |--------------------------------------------------------------------------
            */
            Route::get('attendance-records', [AttendanceRecordController::class, 'index'])->name('attendance-records.index');
            Route::get('daily-summaries', [AttendanceDailySummaryController::class, 'index'])->name('daily-summaries.index');
            Route::get('attendance-flags', [AttendanceFlagController::class, 'index'])->name('attendance-flags.index');
            Route::get('certificate-eligibilities', [CertificateEligibilityController::class, 'index'])->name('certificate-eligibilities.index');

            /*
            |--------------------------------------------------------------------------
            | Shared communication pages
            |--------------------------------------------------------------------------
            */
            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::get('messages', [MessageLogController::class, 'index'])->name('messages.index');
            Route::get('messages/create', [MessageLogController::class, 'create'])->name('messages.create');
            Route::post('messages', [MessageLogController::class, 'store'])->name('messages.store');

            /*
            |--------------------------------------------------------------------------
            | Evaluation
            |--------------------------------------------------------------------------
            */
            Route::get('evaluation-responses', [EvaluationResponseController::class, 'index'])->name('evaluation-responses.index');
            Route::get('evaluation-reminders', [EvaluationReminderController::class, 'index'])->name('evaluation-reminders.index');
            Route::get('evaluation-reminders/export/excel', [EvaluationReminderController::class, 'exportExcel'])->name('evaluation-reminders.export-excel');
            Route::get('evaluation-reminders/export/pdf', [EvaluationReminderController::class, 'exportPdf'])->name('evaluation-reminders.export-pdf');

            /*
            |--------------------------------------------------------------------------
            | SIWES
            |--------------------------------------------------------------------------
            */
            Route::get('siwes/eligible', [SiwesLetterController::class, 'eligible'])->name('siwes.eligible');
            Route::get('siwes/issued', [SiwesLetterController::class, 'issued'])->name('siwes.issued');
            Route::get('siwes/{siwesLetter}', [SiwesLetterController::class, 'show'])->name('siwes.show');
        });

        /*
        |--------------------------------------------------------------------------
        | Super Admin only: writes and privileged pages
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role.any:super-admin'])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Profile corrections
            |--------------------------------------------------------------------------
            */
            Route::patch('profile-corrections/{profileCorrectionRequest}', [ProfileCorrectionRequestController::class, 'update'])->name('profile-corrections.update');
            Route::post('profile-corrections/{profileCorrectionRequest}/apply', [ProfileCorrectionRequestController::class, 'apply'])->name('profile-corrections.apply');

            /*
            |--------------------------------------------------------------------------
            | Courses
            |--------------------------------------------------------------------------
            */
            Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
            Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
            Route::patch('courses/{course}', [CourseController::class, 'update']);
            Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

            /*
            |--------------------------------------------------------------------------
            | Venues
            |--------------------------------------------------------------------------
            */
            Route::post('venues', [VenueController::class, 'store'])->name('venues.store');
            Route::put('venues/{venue}', [VenueController::class, 'update'])->name('venues.update');
            Route::patch('venues/{venue}', [VenueController::class, 'update']);
            Route::delete('venues/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            Route::get('users/grouped', [UserController::class, 'grouped'])->name('users.grouped');
            Route::resource('users', UserController::class)->except(['index', 'show']);
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::patch('users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
            Route::post('users/{user}/link-participant', [UserController::class, 'linkParticipant'])->name('users.link-participant');
            Route::post('users/{user}/unlink-participant', [UserController::class, 'unlinkParticipant'])->name('users.unlink-participant');
            Route::patch('users/bulk-status', [UserController::class, 'bulkStatus'])->name('users.bulk-status');
            Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
            Route::post('users/bulk-create-participant-users', [UserController::class, 'bulkCreateParticipantUsers'])->name('users.bulk-create-participant-users');
            Route::post('users/{user}/send-invitation', [UserController::class, 'sendInvitation'])->name('users.send-invitation');
            Route::post('users/{user}/resend-reset', [UserController::class, 'resendReset'])->name('users.resend-reset');

            /*
            |--------------------------------------------------------------------------
            | Batches
            |--------------------------------------------------------------------------
            */
            Route::post('batches', [BatchController::class, 'store'])->name('batches.store');
            Route::put('batches/{batch}', [BatchController::class, 'update'])->name('batches.update');
            Route::patch('batches/{batch}', [BatchController::class, 'update']);
            Route::delete('batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');

            /*
            |--------------------------------------------------------------------------
            | Participants
            |--------------------------------------------------------------------------
            */
            Route::post('participants-import', [ParticipantController::class, 'import'])->name('participants.import');
            Route::post('participants', [ParticipantController::class, 'store'])->name('participants.store');
            Route::put('participants/{participant}', [ParticipantController::class, 'update'])->name('participants.update');
            Route::patch('participants/{participant}', [ParticipantController::class, 'update']);
            Route::delete('participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
            Route::post('participants/{participant}/regenerate-qr', [ParticipantController::class, 'regenerateQr'])->name('participants.regenerate-qr');
            Route::post('participants/{participant}/create-user', [UserController::class, 'createFromParticipant'])->name('participants.create-user');

            /*
            |--------------------------------------------------------------------------
            | Sessions
            |--------------------------------------------------------------------------
            */
            Route::post('sessions', [TrainingSessionController::class, 'store'])->name('sessions.store');
            Route::put('sessions/{session}', [TrainingSessionController::class, 'update'])->name('sessions.update');
            Route::patch('sessions/{session}', [TrainingSessionController::class, 'update']);
            Route::delete('sessions/{session}', [TrainingSessionController::class, 'destroy'])->name('sessions.destroy');

            /*
            |--------------------------------------------------------------------------
            | Checkpoints actions
            |--------------------------------------------------------------------------
            */
            Route::post('checkpoints/{session}', [AttendanceCheckpointController::class, 'store'])->name('checkpoints.store');
            Route::post('checkpoints/{session}/generate-standard', [AttendanceCheckpointController::class, 'generateStandard'])->name('checkpoints.generate-standard');
            Route::post('checkpoints/{checkpoint}/launch', [AttendanceCheckpointController::class, 'launch'])->name('checkpoints.launch');
            Route::post('checkpoints/{checkpoint}/close', [AttendanceCheckpointController::class, 'close'])->name('checkpoints.close');

            /*
            |--------------------------------------------------------------------------
            | Attendance actions
            |--------------------------------------------------------------------------
            */
            Route::post('daily-summaries/compute/{participant}/{session}', [AttendanceDailySummaryController::class, 'compute'])->name('daily-summaries.compute');
            Route::post('attendance-flags', [AttendanceFlagController::class, 'store'])->name('attendance-flags.store');
            Route::post('attendance-flags/{flag}/resolve', [AttendanceFlagController::class, 'resolve'])->name('attendance-flags.resolve');
            Route::post('certificate-eligibilities/ensure-batch/{batch}', function (\App\Models\Batch $batch) {
                return back()->with('success', 'Batch eligibility check completed.');
            })->whereNumber('batch')->name('certificate-eligibilities.ensure-batch');

            Route::post('certificate-eligibilities/recompute-batch/{batch}', function (\App\Models\Batch $batch) {
                return back()->with('success', 'Batch eligibility recomputation completed.');
            })->whereNumber('batch')->name('certificate-eligibilities.recompute-batch');

            Route::post('certificate-eligibilities/compute/{participant}', [CertificateEligibilityController::class, 'compute'])->name('certificate-eligibilities.compute');

            /*
            |--------------------------------------------------------------------------
            | Activity / notifications
            |--------------------------------------------------------------------------
            */
            Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::post('notifications/mark-read/{id}', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

            /*
            |--------------------------------------------------------------------------
            | Evaluation forms and questions
            |--------------------------------------------------------------------------
            */
            Route::resource('evaluation-forms', EvaluationFormController::class);
            Route::post('evaluation-reminders/send-batch', [EvaluationReminderController::class, 'sendBatch'])->name('evaluation-reminders.send-batch');

            Route::get('evaluation-forms/{evaluationForm}/questions', [EvaluationQuestionController::class, 'index'])->name('evaluation-forms.questions.index');
            Route::get('evaluation-forms/{evaluationForm}/questions/create', [EvaluationQuestionController::class, 'create'])->name('evaluation-forms.questions.create');
            Route::post('evaluation-forms/{evaluationForm}/questions', [EvaluationQuestionController::class, 'store'])->name('evaluation-forms.questions.store');
            Route::get('evaluation-forms/{evaluationForm}/questions/{question}/edit', [EvaluationQuestionController::class, 'edit'])->name('evaluation-forms.questions.edit');
            Route::put('evaluation-forms/{evaluationForm}/questions/{question}', [EvaluationQuestionController::class, 'update'])->name('evaluation-forms.questions.update');
            Route::delete('evaluation-forms/{evaluationForm}/questions/{question}', [EvaluationQuestionController::class, 'destroy'])->name('evaluation-forms.questions.destroy');

            /*
            |--------------------------------------------------------------------------
            | SIWES actions
            |--------------------------------------------------------------------------
            */
            Route::post('siwes/issue/{participant}', [SiwesLetterController::class, 'issue'])->name('siwes.issue');

            /*
            |--------------------------------------------------------------------------
            | Automation
            |--------------------------------------------------------------------------
            */
            Route::get('automation', [AutomationController::class, 'index'])->name('automation.index');
            Route::post('automation/run/{task}', [AutomationController::class, 'run'])->name('automation.run');
        });
    });


