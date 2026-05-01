<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\EvaluationForm;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluationFormSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::role('super-admin')->first() ?? User::first();
        $batch = Batch::query()->latest('id')->first();

        $form = EvaluationForm::updateOrCreate(
            [
                'title' => 'Track B End-of-Training Evaluation Form',
            ],
            [
                'description' => 'Standard participant evaluation form for Track B trainings.',
                'track_scope' => 'Track B',
                'batch_id' => $batch?->id,
                'is_active' => true,
                'opens_at' => now(),
                'closes_at' => now()->addDays(30),
                'created_by' => $creator?->id,
            ]
        );

        $questions = [
            ['Training Content', 'The training objectives were clear.', 'rating', null, true, 1],
            ['Training Content', 'The content was relevant to my work.', 'rating', null, true, 2],
            ['Training Content', 'The training materials were useful.', 'rating', null, true, 3],
            ['Facilitation', 'Facilitators explained concepts clearly.', 'rating', null, true, 4],
            ['Facilitation', 'Facilitators responded well to questions.', 'rating', null, true, 5],
            ['Logistics', 'The training venue was conducive.', 'rating', null, true, 6],
            ['Logistics', 'The registration process was satisfactory.', 'rating', null, true, 7],
            ['Logistics', 'Time management during the training was effective.', 'rating', null, true, 8],
            ['Impact', 'I can apply what I learned to my work.', 'rating', null, true, 9],
            ['Impact', 'What did you find most useful in this training?', 'textarea', null, false, 10],
            ['Impact', 'What areas need improvement?', 'textarea', null, false, 11],
            ['Impact', 'Would you recommend this training to others?', 'yes_no', null, true, 12],
        ];

        foreach ($questions as [$section, $text, $type, $options, $required, $order]) {
            EvaluationQuestion::updateOrCreate(
                [
                    'evaluation_form_id' => $form->id,
                    'sort_order' => $order,
                ],
                [
                    'section_name' => $section,
                    'question_text' => $text,
                    'question_type' => $type,
                    'options_json' => $options,
                    'is_required' => $required,
                ]
            );
        }
    }
}
