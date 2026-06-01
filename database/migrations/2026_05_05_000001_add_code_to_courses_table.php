<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'code')) {
                $table->string('code')->nullable()->after('title');
            }
        });

        DB::table('courses')
            ->whereNull('code')
            ->orderBy('id')
            ->get()
            ->each(function ($course) {
                DB::table('courses')
                    ->where('id', $course->id)
                    ->update([
                        'code' => 'COURSE-' . str_pad((string) $course->id, 3, '0', STR_PAD_LEFT),
                    ]);
            });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('code')->nullable(false)->change();
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }
        });
    }
};
