<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_weight')->default(0);
            $table->unsignedInteger('earned_weight')->default(0);
            $table->decimal('attendance_percentage', 5, 2)->default(0);
            $table->string('attendance_status')->default('absent');
            $table->unsignedInteger('flag_count')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['participant_id', 'training_session_id'], 'participant_session_summary_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_daily_summaries');
    }
};
