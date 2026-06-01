<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('participants') && ! Schema::hasColumn('participants', 'lga')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->string('lga', 150)->nullable()->after('state_of_origin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('participants') && Schema::hasColumn('participants', 'lga')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->dropColumn('lga');
            });
        }
    }
};
