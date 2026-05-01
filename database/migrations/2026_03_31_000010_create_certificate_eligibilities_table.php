<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->decimal('attendance_percentage', 5, 2)->default(0);
            $table->unsignedInteger('partial_days')->default(0);
            $table->unsignedInteger('absent_days')->default(0);
            $table->string('siwes_status')->default('pending');
            $table->boolean('eligible')->default(false);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['participant_id'], 'participant_certificate_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_eligibilities');
    }
};
