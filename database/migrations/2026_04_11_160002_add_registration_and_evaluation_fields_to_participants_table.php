<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('participants')) {
            return;
        }

        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'registration_status')) {
                $table->enum('registration_status', ['pending', 'registered', 'confirmed', 'cancelled'])
                    ->default('registered')
                    ->after('batch_id');
            }

            if (!Schema::hasColumn('participants', 'registration_date')) {
                $table->dateTime('registration_date')->nullable()->after('registration_status');
            }

            if (!Schema::hasColumn('participants', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('registration_date');
            }

            if (!Schema::hasColumn('participants', 'phone')) {
                $table->string('phone', 30)->nullable()->after('gender');
            }

            if (!Schema::hasColumn('participants', 'alternate_phone')) {
                $table->string('alternate_phone', 30)->nullable()->after('phone');
            }

            if (!Schema::hasColumn('participants', 'email')) {
                $table->string('email')->nullable()->after('alternate_phone');
            }

            if (!Schema::hasColumn('participants', 'organization')) {
                $table->string('organization')->nullable()->after('email');
            }

            if (!Schema::hasColumn('participants', 'designation')) {
                $table->string('designation')->nullable()->after('organization');
            }

            if (!Schema::hasColumn('participants', 'state_of_origin')) {
                $table->string('state_of_origin', 100)->nullable()->after('designation');
            }

            if (!Schema::hasColumn('participants', 'sponsor_name')) {
                $table->string('sponsor_name')->nullable()->after('state_of_origin');
            }

            if (!Schema::hasColumn('participants', 'category')) {
                $table->string('category', 100)->nullable()->after('sponsor_name');
            }

            if (!Schema::hasColumn('participants', 'training_location')) {
                $table->string('training_location', 150)->nullable()->after('category');
            }

            if (!Schema::hasColumn('participants', 'evaluation_completed')) {
                $table->boolean('evaluation_completed')->default(false)->after('training_location');
            }

            if (!Schema::hasColumn('participants', 'evaluation_completed_at')) {
                $table->dateTime('evaluation_completed_at')->nullable()->after('evaluation_completed');
            }

            if (!Schema::hasColumn('participants', 'certificate_ready')) {
                $table->boolean('certificate_ready')->default(false)->after('evaluation_completed_at');
            }

            if (!Schema::hasColumn('participants', 'certificate_ready_at')) {
                $table->dateTime('certificate_ready_at')->nullable()->after('certificate_ready');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('participants')) {
            return;
        }

        Schema::table('participants', function (Blueprint $table) {
            foreach ([
                'registration_status',
                'registration_date',
                'gender',
                'phone',
                'alternate_phone',
                'email',
                'organization',
                'designation',
                'state_of_origin',
                'sponsor_name',
                'category',
                'training_location',
                'evaluation_completed',
                'evaluation_completed_at',
                'certificate_ready',
                'certificate_ready_at',
            ] as $column) {
                if (Schema::hasColumn('participants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
