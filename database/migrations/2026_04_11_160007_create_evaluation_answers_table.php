<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluation_answers')) {
            return;
        }

        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_submission_id')->constrained('evaluation_submissions')->cascadeOnDelete();
            $table->foreignId('evaluation_question_id')->constrained('evaluation_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->decimal('answer_number', 10, 2)->nullable();
            $table->string('answer_option')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('evaluation_answers')) {
            Schema::dropIfExists('evaluation_answers');
        }
    }
};
