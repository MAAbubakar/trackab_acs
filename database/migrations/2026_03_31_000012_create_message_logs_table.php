<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('participant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message_type');
            $table->string('channel')->default('database');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['message_type', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_logs');
    }
};
