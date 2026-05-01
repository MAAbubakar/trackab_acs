<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluation_questions')) {
            return;
        }

        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_form_id')->constrained('evaluation_forms')->cascadeOnDelete();
            $table->string('section_name', 150)->nullable();
            $table->text('question_text');
            $table->enum('question_type', ['text', 'textarea', 'radio', 'select', 'rating', 'yes_no']);
            $table->json('options_json')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('evaluation_questions')) {
            Schema::dropIfExists('evaluation_questions');
        }
    }
};
