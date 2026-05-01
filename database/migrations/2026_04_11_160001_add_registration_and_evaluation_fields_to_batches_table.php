<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('batches')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'registration_open_date')) {
                $table->date('registration_open_date')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('batches', 'registration_close_date')) {
                $table->date('registration_close_date')->nullable()->after('registration_open_date');
            }
            if (!Schema::hasColumn('batches', 'evaluation_open_date')) {
                $table->date('evaluation_open_date')->nullable()->after('registration_close_date');
            }
            if (!Schema::hasColumn('batches', 'evaluation_close_date')) {
                $table->date('evaluation_close_date')->nullable()->after('evaluation_open_date');
            }
            if (!Schema::hasColumn('batches', 'certificate_requires_evaluation')) {
                $table->boolean('certificate_requires_evaluation')->default(true)->after('evaluation_close_date');
            }
            if (!Schema::hasColumn('batches', 'certificate_requires_attendance')) {
                $table->boolean('certificate_requires_attendance')->default(true)->after('certificate_requires_evaluation');
            }
            if (!Schema::hasColumn('batches', 'minimum_attendance_percent')) {
                $table->decimal('minimum_attendance_percent', 5, 2)->default(80.00)->after('certificate_requires_attendance');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('batches')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            foreach ([
                'registration_open_date',
                'registration_close_date',
                'evaluation_open_date',
                'evaluation_close_date',
                'certificate_requires_evaluation',
                'certificate_requires_attendance',
                'minimum_attendance_percent',
            ] as $column) {
                if (Schema::hasColumn('batches', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
