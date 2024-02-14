<?php

namespace App\Livewire\Forms;

use App\Models\Question;
use Livewire\Attributes\Validate;
use Livewire\Form;

class QuestionForm extends Form
{
    public ?Question $question = null;

    #[Validate('string|required')]
    public ?string $question_text = '';

    #[Validate('string|nullable')]
    public ?string $code_snippet = '';

    #[Validate('string|nullable')]
    public ?string $answer_explanation = '';

    #[Validate('url|nullable')]
    public ?string $more_info_link = '';

    #[Validate([
        'questionOptions' => ['required', 'array'],
        'questionOptions.*.option' => ['required', 'string'],
    ], message: [
        'questionOptions.required' => 'You must add answer options.',
        'questionOptions.*.option.required' => 'The option #:position field must not be empty.',
    ])]
    public array $questionOptions = [];

    public bool $editing = false;

    public function setQuestion(Question $question): void
    {
        $this->question = $question;

        if ($question->exists) {
            foreach ($question->questionOptions as $option) {
                $this->questionOptions[] = [
                    'id' => $option->id,
                    'option' => $option->option,
                    'correct' => $option->correct,
                ];
            }
        }

        $this->question_text = $question->question_text;

        $this->code_snippet = $question->code_snippet;

        $this->answer_explanation = $question->answer_explanation;

        $this->more_info_link = $question->more_info_link;
    }

    public function addQuestionsOption(): void
    {
        $this->questionOptions[] = [
            'option' => '',
            'correct' => false,
        ];
    }

    public function removeQuestionsOption(int $index): void
    {
        unset($this->questionOptions[$index]);
        $this->questionOptions = array_values(($this->questionOptions));
    }
}
