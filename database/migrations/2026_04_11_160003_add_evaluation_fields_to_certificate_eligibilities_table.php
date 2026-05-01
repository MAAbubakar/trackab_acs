<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('certificate_eligibilities')) {
            return;
        }

        Schema::table('certificate_eligibilities', function (Blueprint $table) {
            if (!Schema::hasColumn('certificate_eligibilities', 'evaluation_required')) {
                $table->boolean('evaluation_required')->default(true)->after('participant_id');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'evaluation_completed')) {
                $table->boolean('evaluation_completed')->default(false)->after('evaluation_required');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'attendance_required')) {
                $table->boolean('attendance_required')->default(true)->after('evaluation_completed');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'attendance_met')) {
                $table->boolean('attendance_met')->default(false)->after('attendance_required');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'eligibility_status')) {
                $table->enum('eligibility_status', ['pending', 'eligible', 'not_eligible'])
                    ->default('pending')
                    ->after('attendance_met');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'ineligibility_reason')) {
                $table->text('ineligibility_reason')->nullable()->after('eligibility_status');
            }
            if (!Schema::hasColumn('certificate_eligibilities', 'evaluated_at')) {
                $table->dateTime('evaluated_at')->nullable()->after('ineligibility_reason');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('certificate_eligibilities')) {
            return;
        }

        Schema::table('certificate_eligibilities', function (Blueprint $table) {
            foreach ([
                'evaluation_required',
                'evaluation_completed',
                'attendance_required',
                'attendance_met',
                'eligibility_status',
                'ineligibility_reason',
                'evaluated_at',
            ] as $column) {
                if (Schema::hasColumn('certificate_eligibilities', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
