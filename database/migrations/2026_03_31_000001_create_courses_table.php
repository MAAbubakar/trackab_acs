<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('track')->default('Track B');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('duration_weeks')->default(3);
            $table->time('class_start_time')->default('08:00:00');
            $table->time('class_end_time')->default('16:00:00');
            $table->boolean('siwes_enabled')->default(true);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
