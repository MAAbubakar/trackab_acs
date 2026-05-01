<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluation_forms')) {
            return;
        }

        Schema::create('evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('track_scope', ['Track A', 'Track B', 'Both'])->default('Both');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->dateTime('opens_at')->nullable();
            $table->dateTime('closes_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('evaluation_forms')) {
            Schema::dropIfExists('evaluation_forms');
        }
    }
};
