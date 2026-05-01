<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluation_submissions')) {
            return;
        }

        Schema::create('evaluation_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_form_id')->constrained('evaluation_forms')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('participants')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->enum('submission_status', ['draft', 'submitted'])->default('submitted');
            $table->dateTime('submitted_at');
            $table->decimal('average_rating', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['evaluation_form_id', 'participant_id', 'batch_id'], 'uniq_eval_submission');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('evaluation_submissions')) {
            Schema::dropIfExists('evaluation_submissions');
        }
    }
};
