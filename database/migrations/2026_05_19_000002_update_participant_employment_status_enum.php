<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('participants') && Schema::hasColumn('participants', 'employment_status')) {
            DB::statement("ALTER TABLE participants MODIFY employment_status ENUM('employed','unemployed','self-employed') NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('participants') && Schema::hasColumn('participants', 'employment_status')) {
            DB::statement("ALTER TABLE participants MODIFY employment_status ENUM('employed','unemployed') NULL");
        }
    }
};
