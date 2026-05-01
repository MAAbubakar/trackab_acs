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
            if (!Schema::hasColumn('participants', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('participants', 'nationality')) {
                $table->string('nationality', 100)->nullable()->after('age');
            }
            if (!Schema::hasColumn('participants', 'academic_background')) {
                $table->string('academic_background', 150)->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('participants', 'employment_status')) {
                $table->enum('employment_status', ['employed', 'unemployed'])->nullable()->after('academic_background');
            }
            if (!Schema::hasColumn('participants', 'employment_sector')) {
                $table->enum('employment_sector', ['public', 'private', 'other'])->nullable()->after('employment_status');
            }
            if (!Schema::hasColumn('participants', 'employer_name')) {
                $table->string('employer_name')->nullable()->after('employment_sector');
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
                'age',
                'nationality',
                'academic_background',
                'employment_status',
                'employment_sector',
                'employer_name',
            ] as $column) {
                if (Schema::hasColumn('participants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
