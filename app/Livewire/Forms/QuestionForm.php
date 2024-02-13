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

    public bool $editing = false;

    public function setQuestion(Question $question): void
    {
        $this->question = $question;

        $this->question_text = $question->question_text;

        $this->code_snippet = $question->code_snippet;

        $this->answer_explanation = $question->answer_explanation;

        $this->more_info_link = $question->more_info_link;
    }
}
