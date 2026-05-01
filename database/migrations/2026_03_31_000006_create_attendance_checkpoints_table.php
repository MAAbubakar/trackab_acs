<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('checkpoint_type');
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->unsignedInteger('weight')->default(20);
            $table->boolean('is_random')->default(false);
            $table->boolean('requires_photo')->default(false);
            $table->boolean('requires_device_validation')->default(false);
            $table->boolean('requires_location_validation')->default(false);
            $table->string('qr_token')->nullable();
            $table->dateTime('token_expires_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_checkpoints');
    }
};
