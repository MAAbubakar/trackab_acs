<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attendance_checkpoint_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scan_time');
            $table->string('method')->default('qr');
            $table->string('device_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('valid');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['participant_id', 'attendance_checkpoint_id'], 'participant_checkpoint_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
