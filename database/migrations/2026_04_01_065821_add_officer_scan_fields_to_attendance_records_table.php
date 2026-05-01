<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreignId('captured_by_user_id')->nullable()->after('attendance_checkpoint_id')->constrained('users')->nullOnDelete();
            $table->string('capture_method')->default('qr')->after('scan_time');
            $table->string('terminal_label')->nullable()->after('capture_method');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('captured_by_user_id');
            $table->dropColumn(['capture_method', 'terminal_label']);
        });
    }
};
