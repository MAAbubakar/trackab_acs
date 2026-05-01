<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'qr_identifier')) {
                $table->string('qr_identifier')->nullable()->unique()->after('participant_no');
            }

            if (!Schema::hasColumn('participants', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('qr_identifier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (Schema::hasColumn('participants', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }

            if (Schema::hasColumn('participants', 'qr_identifier')) {
                $table->dropColumn('qr_identifier');
            }
        });
    }
};
